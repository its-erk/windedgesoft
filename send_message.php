<?php
/**
 * Windedgesoft contact form handler.
 *
 * Receives the form on contact.html, validates it, and emails it to TO_EMAIL.
 * Replies with JSON when called by contact.js, or a small HTML page when the
 * form is submitted without JavaScript.
 *
 * Needs PHP 7.4+ and a working mail() setup on the server (see notes below).
 */

// ---- Settings -------------------------------------------------------------
const TO_EMAIL    = 'windedgesoft@gmail.com';
// Use an address on YOUR domain here. Mail "from" a domain the server doesn't
// own is often rejected or sent to spam. Replies still go to the visitor.
const FROM_EMAIL  = 'no-reply@windedgesoft.com';
const SITE_NAME   = 'Windedgesoft';
const RATE_LIMIT  = 5;     // max messages per IP...
const RATE_WINDOW = 3600;  // ...per this many seconds
const MIN_SECONDS = 3;     // forms submitted faster than this are treated as bots

const SERVICES = [
    'Not sure yet', 'Website', 'Web application', 'Business system / ERP',
    'Mobile solution', 'Backend / API', 'Other',
];
// ---------------------------------------------------------------------------

function wants_json(): bool
{
    $accept = $_SERVER['HTTP_ACCEPT'] ?? '';
    $xhr    = $_SERVER['HTTP_X_REQUESTED_WITH'] ?? '';
    return stripos($accept, 'application/json') !== false || strcasecmp($xhr, 'XMLHttpRequest') === 0;
}

function respond(bool $ok, string $message, array $errors = [], int $status = 200): void
{
    http_response_code($status);
    header('Cache-Control: no-store');

    if (wants_json()) {
        header('Content-Type: application/json; charset=UTF-8');
        echo json_encode(['ok' => $ok, 'message' => $message, 'errors' => (object) $errors]);
        exit;
    }

    header('Content-Type: text/html; charset=UTF-8');
    $title = $ok ? 'Message sent' : 'Message not sent';
    $text  = htmlspecialchars($message, ENT_QUOTES, 'UTF-8');
    if (!$ok && $errors) {
        $text .= '<br>' . htmlspecialchars(implode(' ', $errors), ENT_QUOTES, 'UTF-8');
    }
    echo '<!DOCTYPE html><html lang="en"><head><meta charset="UTF-8">'
       . '<meta name="viewport" content="width=device-width, initial-scale=1">'
       . '<meta name="robots" content="noindex"><title>' . $title . ' | ' . SITE_NAME . '</title>'
       . '<link href="assets/bootstrap/css/bootstrap.min.css" rel="stylesheet"></head>'
       . '<body class="bg-dark text-light d-flex align-items-center" style="min-height:100vh">'
       . '<div class="container text-center"><h1 class="h3 mb-3">' . $title . '</h1>'
       . '<p class="lead">' . $text . '</p>'
       . '<a class="btn btn-primary rounded-pill" href="' . ($ok ? 'index.html' : 'contact.html') . '">'
       . ($ok ? 'Back to home' : 'Back to the form') . '</a></div></body></html>';
    exit;
}

/** Remove control characters and line breaks (blocks email header injection). */
function clean_line(string $value): string
{
    return trim(preg_replace('/[\x00-\x1F\x7F]+/u', ' ', $value) ?? '');
}

/** Keep line breaks in the message body but drop other control characters. */
function clean_text(string $value): string
{
    $value = str_replace(["\r\n", "\r"], "\n", $value);
    return trim(preg_replace('/[\x00-\x08\x0B\x0C\x0E-\x1F\x7F]+/u', '', $value) ?? '');
}

/** Returns true if this IP is over the limit; otherwise records the attempt. */
function rate_limited(string $ip): bool
{
    $file = sys_get_temp_dir() . '/wes_rl_' . sha1($ip) . '.json';
    $now  = time();
    $fh   = @fopen($file, 'c+');
    if (!$fh) {
        return false; // can't track, so don't block real visitors
    }
    flock($fh, LOCK_EX);
    $raw   = stream_get_contents($fh);
    $times = $raw ? (json_decode($raw, true) ?: []) : [];
    $times = array_values(array_filter($times, fn($t) => is_int($t) && $t > $now - RATE_WINDOW));

    $limited = count($times) >= RATE_LIMIT;
    if (!$limited) {
        $times[] = $now;
    }
    ftruncate($fh, 0);
    rewind($fh);
    fwrite($fh, json_encode($times));
    flock($fh, LOCK_UN);
    fclose($fh);
    return $limited;
}

// ---- Request checks -------------------------------------------------------
if (($_SERVER['REQUEST_METHOD'] ?? '') !== 'POST') {
    header('Allow: POST');
    respond(false, 'Please use the contact form to send a message.', [], 405);
}

// Same-site check: if the browser says where the request came from, it must be us.
$origin = $_SERVER['HTTP_ORIGIN'] ?? '';
if ($origin !== '') {
    $originHost = parse_url($origin, PHP_URL_HOST);
    $thisHost   = preg_replace('/:\d+$/', '', $_SERVER['HTTP_HOST'] ?? '');
    if ($originHost && $thisHost && strcasecmp($originHost, $thisHost) !== 0) {
        respond(false, 'Request not allowed.', [], 403);
    }
}

// Honeypot: bots fill in every field, people never see this one.
if (!empty($_POST['website'])) {
    respond(true, 'Thanks! Your message has been sent.'); // pretend success
}

// Timing check: ignore forms submitted impossibly fast.
$started = isset($_POST['started']) ? (int) $_POST['started'] : 0;
if ($started > 0 && (time() - intdiv($started, 1000)) < MIN_SECONDS) {
    respond(true, 'Thanks! Your message has been sent.'); // pretend success
}

$ip = $_SERVER['REMOTE_ADDR'] ?? 'unknown';
if (rate_limited($ip)) {
    respond(false, 'You have sent several messages already. Please try again later or email us directly.', [], 429);
}

// ---- Validate -------------------------------------------------------------
$name    = clean_line((string) ($_POST['name'] ?? ''));
$email   = clean_line((string) ($_POST['email'] ?? ''));
$phone   = clean_line((string) ($_POST['phone'] ?? ''));
$service = clean_line((string) ($_POST['service'] ?? 'Not sure yet'));
$message = clean_text((string) ($_POST['message'] ?? ''));

$errors = [];
$len = fn(string $s): int => function_exists('mb_strlen') ? mb_strlen($s, 'UTF-8') : strlen($s);

if ($len($name) < 2 || $len($name) > 100) {
    $errors['name'] = 'Please enter your name.';
}
if (!filter_var($email, FILTER_VALIDATE_EMAIL) || $len($email) > 150) {
    $errors['email'] = 'Please enter a valid email address.';
}
if ($phone !== '' && !preg_match('/^[0-9+()\-\s]{7,20}$/', $phone)) {
    $errors['phone'] = 'Please enter a valid phone number.';
}
if (!in_array($service, SERVICES, true)) {
    $service = 'Not sure yet';
}
if ($len($message) < 10) {
    $errors['message'] = 'Please tell us a little more (at least 10 characters).';
} elseif ($len($message) > 5000) {
    $errors['message'] = 'Your message is too long (5000 characters maximum).';
}

if ($errors) {
    respond(false, 'Please fix the highlighted fields.', $errors, 422);
}

// ---- Send -----------------------------------------------------------------
$subject = '[' . SITE_NAME . ' website] New enquiry from ' . $name;
$encodedSubject = '=?UTF-8?B?' . base64_encode($subject) . '?=';

$body  = "New message from the " . SITE_NAME . " website contact form\n";
$body .= str_repeat('-', 48) . "\n";
$body .= "Name:    $name\n";
$body .= "Email:   $email\n";
$body .= 'Phone:   ' . ($phone !== '' ? $phone : '(not given)') . "\n";
$body .= "Service: $service\n";
$body .= str_repeat('-', 48) . "\n\n";
$body .= $message . "\n\n";
$body .= str_repeat('-', 48) . "\n";
$body .= 'Sent: ' . gmdate('Y-m-d H:i:s') . " UTC\n";
$body .= "IP: $ip\n";

$headers = [
    'From'         => SITE_NAME . ' Website <' . FROM_EMAIL . '>',
    'Reply-To'     => $name !== '' ? '"' . str_replace('"', '', $name) . '" <' . $email . '>' : $email,
    'MIME-Version' => '1.0',
    'Content-Type' => 'text/plain; charset=UTF-8',
    'X-Mailer'     => 'PHP/' . PHP_VERSION,
];

$sent = @mail(TO_EMAIL, $encodedSubject, $body, $headers, '-f' . FROM_EMAIL);
if (!$sent) {
    // Some hosts reject the -f flag; try once without it.
    $sent = @mail(TO_EMAIL, $encodedSubject, $body, $headers);
}

if (!$sent) {
    error_log('send_message.php: mail() failed for enquiry from ' . $email);
    respond(
        false,
        'Sorry, we could not send your message right now. Please email ' . TO_EMAIL . ' directly.',
        [],
        500
    );
}

respond(true, 'Thanks! Your message has been sent. We will get back to you soon.');
