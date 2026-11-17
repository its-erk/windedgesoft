#!/usr/bin/env python3
"""Package the Windedgesoft site into a slim zip, ready to upload.

Instead of zipping the whole folder, this script finds which files the site
actually uses and packs only those. It starts from your pages (*.html, *.php,
robots.txt, sitemap.xml, favicons), follows every CSS/HTML/JS reference to
other files (images, fonts, scripts, stylesheets), and leaves the rest behind.
That drops things like the thousands of unused Font Awesome SVGs, Bootstrap
source maps and unused images.

Your original folder is never modified. Image shrinking, domain replacement
and source-map clean-up are applied to the copy inside the zip only.

Usage:
    python3 package_site.py
    python3 package_site.py --domain https://www.example.co.ke
    python3 package_site.py --dry-run
    python3 package_site.py --include-backup --no-shrink

Optional: `pip install Pillow` enables automatic image shrinking.
"""

import argparse
import io
import re
import sys
import zipfile
from collections import defaultdict
from datetime import datetime
from pathlib import Path

try:
    from PIL import Image
except ImportError:  # image shrinking is optional
    Image = None

DEFAULT_SOURCE = "/var/www/html/windedgesoft"

# The URL written into canonical/OG tags and sitemap.xml in the source files.
# When you move to a custom domain, use --domain to swap it inside the zip.
PLACEHOLDER_DOMAIN = "https://its-erk.github.io/windedgesoft"

# --- Never upload these ------------------------------------------------------
EXCLUDE_DIRS = {".git", "__pycache__", "node_modules", ".idea", ".vscode"}
EXCLUDE_FILES = {".gitignore", ".gitattributes", ".DS_Store", "Thumbs.db"}
EXCLUDE_PATTERNS = ["*.pyc", "*.zip", "*.swp", "*~", "*.bak", "*.orig", "*.map"]

# --- Always upload these, even if nothing links to them ----------------------
ALWAYS_ROOT_PATTERNS = ["*.html", "*.php", "robots.txt", "sitemap.xml", ".htaccess"]
ALWAYS_DIRS = ["assets/img/favicon_io"]

# --- Files that are read to discover what they reference ----------------------
CRAWL_SUFFIXES = {".html", ".htm", ".php", ".css", ".js", ".webmanifest"}

# --- Image shrinking: (folder prefix, max width in px); first match wins ------
IMAGE_LIMITS = [
    ("assets/img/testimonials/", 800),
    ("assets/img/our_projects/", 1400),
    ("", 1600),
]
IMAGE_SUFFIXES = {".webp", ".jpg", ".jpeg", ".png"}

REF_RE = re.compile(
    r"[\w@%~+\-./]+\.(?:css|mjs|js|png|jpe?g|gif|webp|avif|svg|ico|woff2?|ttf|otf|eot|"
    r"webmanifest|json|pdf|mp4|webm|mp3|txt|xml|html?|php)\b",
    re.I,
)
URL_RE = re.compile(r"https?://[^\s\"'()<>]+", re.I)
SOURCEMAP_RE = re.compile(rb"\n?/[*/][#@]\s*sourceMappingURL=[^\s*]+(?:\s*\*/)?\s*$")


def human(num: float) -> str:
    for unit in ("B", "KB", "MB", "GB"):
        if num < 1024 or unit == "GB":
            return f"{num:.1f} {unit}" if unit != "B" else f"{int(num)} B"
        num /= 1024
    return f"{num:.1f} GB"


# ------------------------------------------------------------------ discovery
def list_candidates(source: Path, include_backup: bool):
    """Every file that is allowed to be uploaded, as {posix_relative_path: Path}."""
    found, policy_skipped = {}, 0
    for path in sorted(source.rglob("*")):
        if not path.is_file():
            continue
        rel = path.relative_to(source)
        parts = rel.parts
        skip = any(p in EXCLUDE_DIRS for p in parts[:-1])
        skip = skip or (parts[0] == "backup" and not include_backup)
        skip = skip or path.name in EXCLUDE_FILES
        skip = skip or any(path.match(pat) for pat in EXCLUDE_PATTERNS)
        if skip:
            policy_skipped += 1
            continue
        found[rel.as_posix()] = path
    return found, policy_skipped


def is_always_included(rel: str) -> bool:
    parts = rel.split("/")
    if len(parts) == 1:
        return any(Path(rel).match(pat) for pat in ALWAYS_ROOT_PATTERNS)
    return any(rel.startswith(d.rstrip("/") + "/") for d in ALWAYS_DIRS)


def references(text: str, own_domains):
    """Yield file-looking strings found in a page, stylesheet or script."""
    for dom in own_domains:  # https://mysite.com/a.png -> /a.png
        text = re.sub(re.escape(dom), "", text, flags=re.I)
    text = URL_RE.sub(" ", text)  # drop remaining external URLs (CDNs, fonts...)
    for match in REF_RE.finditer(text):
        ref = match.group(0)
        if not ref.startswith("//"):
            yield ref


def find_used_files(source: Path, files: dict, own_domains):
    wanted = {rel for rel in files if is_always_included(rel)}
    queue = list(wanted)
    broken = defaultdict(set)  # missing file -> pages that mention it

    while queue:
        rel = queue.pop()
        path = files[rel]
        suffix = path.suffix.lower()
        if suffix not in CRAWL_SUFFIXES:
            continue
        if suffix == ".js" and path.name.endswith(".min.js"):
            continue  # vendor bundles: nothing of ours to find inside
        text = path.read_text(encoding="utf-8", errors="ignore")

        for ref in references(text, own_domains):
            if ref.startswith("/"):
                options = [source / ref.lstrip("/")]
            else:
                options = [path.parent / ref, source / ref]
            hit = None
            for opt in options:
                try:
                    cand = opt.resolve().relative_to(source.resolve()).as_posix()
                except (ValueError, OSError):
                    continue
                if cand in files:
                    hit = cand
                    break
            if hit:
                if hit not in wanted:
                    wanted.add(hit)
                    queue.append(hit)
            elif ref.startswith(("assets/", "./", "../", "/assets/")):
                broken[ref].add(rel)
    return wanted, broken


# --------------------------------------------------------------- transforms
def shrink_image(rel: str, data: bytes):
    """Return smaller image bytes, or the original if nothing improves."""
    if Image is None:
        return data
    limit = next(w for prefix, w in IMAGE_LIMITS if rel.startswith(prefix))
    try:
        img = Image.open(io.BytesIO(data))
        fmt = (img.format or "").upper()
        if fmt not in {"WEBP", "JPEG", "PNG"}:
            return data
        resized = img.width > limit
        if not resized and len(data) < 250 * 1024:
            return data  # already small enough
        if resized:
            new_h = round(img.height * limit / img.width)
            img = img.resize((limit, new_h), Image.LANCZOS)
        out = io.BytesIO()
        if fmt == "WEBP":
            img.save(out, "WEBP", quality=82, method=6)
        elif fmt == "JPEG":
            img.convert("RGB").save(out, "JPEG", quality=82, optimize=True, progressive=True)
        else:
            img.save(out, "PNG", optimize=True)
        return out.getvalue() if len(out.getvalue()) < len(data) else data
    except Exception as exc:  # never fail the whole build over one image
        print(f"  ! could not shrink {rel}: {exc}", file=sys.stderr)
        return data


def transform(rel: str, data: bytes, args):
    suffix = Path(rel).suffix.lower()

    if suffix in {".css", ".js"}:
        data = SOURCEMAP_RE.sub(b"\n", data)  # the .map files aren't shipped

    if args.domain and suffix in {".html", ".xml", ".txt", ".php"}:
        text = data.decode("utf-8", errors="ignore")
        text = text.replace(PLACEHOLDER_DOMAIN, args.domain.rstrip("/"))
        data = text.encode("utf-8")

    if args.shrink and suffix in IMAGE_SUFFIXES:
        data = shrink_image(rel, data)
    return data


# ------------------------------------------------------------------- output
def build(args) -> int:
    source = Path(args.source).expanduser().resolve()
    if not source.is_dir():
        print(f"Error: source folder not found: {source}", file=sys.stderr)
        return 1

    own = {PLACEHOLDER_DOMAIN}
    if args.domain:
        own.add(args.domain.rstrip("/"))
    own |= {d.replace("https://", "https://www.") for d in list(own)}

    files, policy_skipped = list_candidates(source, args.include_backup)
    if "index.html" not in files:
        print("Warning: no index.html found at the site root.", file=sys.stderr)

    wanted, broken = find_used_files(source, files, own)
    unused = sorted(set(files) - wanted)

    if args.shrink and Image is None:
        print("Note: Pillow is not installed, so images won't be shrunk. "
              "Run `pip install Pillow` to enable it.\n")

    stamp = datetime.now().strftime("%Y%m%d-%H%M%S")
    output_dir = Path(args.output).expanduser().resolve()
    zip_path = output_dir / f"{args.name}-{stamp}.zip"

    print(f"Packaging {source}\n")
    original_total = packed_total = 0
    manifest = []
    buffer = io.BytesIO()

    with zipfile.ZipFile(buffer, "w", zipfile.ZIP_DEFLATED, compresslevel=9) as zf:
        for rel in sorted(wanted):
            path = files[rel]
            raw = path.read_bytes()
            data = transform(rel, raw, args)
            original_total += len(raw)
            packed_total += len(data)
            manifest.append((rel, len(data)))

            info = zipfile.ZipInfo.from_file(path, rel)
            info.compress_type = zipfile.ZIP_DEFLATED
            info.external_attr = 0o644 << 16
            zf.writestr(info, data)

    zip_size = len(buffer.getvalue())

    # What was left out, grouped by folder so the output stays readable
    groups = defaultdict(lambda: [0, 0])
    for rel in unused:
        parts = rel.split("/")
        key = "/".join(parts[:4]) + "/" if len(parts) > 4 else "/".join(parts[:-1]) + "/" if len(parts) > 1 else rel
        groups[key][0] += 1
        groups[key][1] += files[rel].stat().st_size
    left_out_total = sum(size for _, size in groups.values())

    print(f"Included : {len(wanted)} files")
    print(f"Left out : {len(unused)} files not used by any page ({human(left_out_total)})")
    if groups:
        print("\n  Biggest things left behind:")
        for key, (count, size) in sorted(groups.items(), key=lambda kv: -kv[1][1])[:8]:
            print(f"    {human(size):>10}  {count:>5} files  {key}")

    if broken:
        print("\n  Warning: pages point to files that don't exist:")
        for ref, pages in sorted(broken.items()):
            print(f"    {ref}   (in {', '.join(sorted(pages))})")

    print(f"\nSite size before shrinking : {human(original_total)}")
    print(f"Site size in the zip       : {human(packed_total)}")
    print(f"Zip file size              : {human(zip_size)}")

    if not args.domain:
        print(f"\nNote: canonical/OG tags and sitemap.xml use {PLACEHOLDER_DOMAIN}.\n"
              "      After moving to a custom domain, re-run with --domain https://your-domain")

    if args.dry_run:
        print("\nDry run: nothing written.")
        return 0

    output_dir.mkdir(parents=True, exist_ok=True)
    zip_path.write_bytes(buffer.getvalue())

    manifest_path = output_dir / "assets-manifest.txt"
    lines = [f"# Files packed from {source} on {stamp}", f"# {len(manifest)} files, {human(packed_total)}", ""]
    lines += [f"{size:>10}  {rel}" for rel, size in manifest]
    manifest_path.write_text("\n".join(lines) + "\n", encoding="utf-8")

    print(f"\nCreated: {zip_path}")
    print(f"Manifest: {manifest_path}")
    return 0


def main() -> int:
    parser = argparse.ArgumentParser(description="Package the site for upload (slim build).")
    parser.add_argument("--source", default=DEFAULT_SOURCE, help="Site folder to package")
    parser.add_argument("--output", default=str(Path.home()), help="Folder to save the zip in")
    parser.add_argument("--name", default="windedgesoft", help="Base name of the zip file")
    parser.add_argument("--domain", help="Real site URL, e.g. https://www.example.co.ke (replaces the placeholder in the zip)")
    parser.add_argument("--include-backup", action="store_true", help="Also include the backup/ folder")
    parser.add_argument("--no-shrink", dest="shrink", action="store_false", help="Don't resize oversized images")
    parser.add_argument("--dry-run", action="store_true", help="Show what would be packed without writing files")
    return build(parser.parse_args())


if __name__ == "__main__":
    sys.exit(main())
