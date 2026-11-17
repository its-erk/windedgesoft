document.addEventListener("DOMContentLoaded", () => {
    const form = document.getElementById("contactForm");
    if (!form) return;

    const status = document.getElementById("formStatus");
    const submitBtn = document.getElementById("cf-submit");
    const spinner = document.getElementById("cf-spinner");
    const icon = document.getElementById("cf-icon");
    const label = document.getElementById("cf-label");

    const SUCCESS_HTML = "Thanks! Your message has been sent. We will get back to you soon.";
    const FALLBACK_HTML =
        'Sorry, we couldn\'t send your message. Please email ' +
        '<a href="mailto:windedgesoft@gmail.com">windedgesoft@gmail.com</a> ' +
        'or <a href="https://wa.me/254797656305" target="_blank" rel="noopener">chat with us on WhatsApp</a>.';

    function showStatus(type, html) {
        status.innerHTML = '<div class="alert alert-' + type + ' mb-4" role="alert">' + html + "</div>";
        status.scrollIntoView({ behavior: "smooth", block: "center" });
    }

    function clearErrors() {
        form.querySelectorAll(".is-invalid").forEach((el) => el.classList.remove("is-invalid"));
        form.querySelectorAll("[data-error-for]").forEach((el) => (el.textContent = ""));
        status.innerHTML = "";
    }

    function setFieldErrors(errors) {
        let first = null;
        Object.keys(errors).forEach((name) => {
            const input = form.elements[name];
            const msg = form.querySelector('[data-error-for="' + name + '"]');
            if (input) {
                input.classList.add("is-invalid");
                first = first || input;
            }
            if (msg) msg.textContent = errors[name];
        });
        if (first) first.focus();
    }

    function setBusy(busy) {
        submitBtn.disabled = busy;
        spinner.classList.toggle("d-none", !busy);
        icon.classList.toggle("d-none", busy);
        label.textContent = busy ? "Sending..." : "Send Message";
    }

    function validate() {
        const errors = {};
        const name = form.elements.name.value.trim();
        const email = form.elements.email.value.trim();
        const phone = form.elements.phone.value.trim();
        const message = form.elements.message.value.trim();

        if (name.length < 2) errors.name = "Please enter your name.";
        if (!/^[^\s@]+@[^\s@]+\.[^\s@]+$/.test(email)) errors.email = "Please enter a valid email address.";
        if (phone && !/^[0-9+()\-\s]{7,20}$/.test(phone)) errors.phone = "Please enter a valid phone number.";
        if (message.length < 10) errors.message = "Please tell us a little more (at least 10 characters).";
        return errors;
    }

    form.addEventListener("submit", async (event) => {
        event.preventDefault();
        clearErrors();

        const errors = validate();
        if (Object.keys(errors).length) {
            setFieldErrors(errors);
            return;
        }

        // Spam trap: a real visitor never fills this in. Pretend it worked.
        if (form.elements.website.value || (form.elements.botcheck && form.elements.botcheck.checked)) {
            form.reset();
            showStatus("success", SUCCESS_HTML);
            return;
        }

        // Setup check: the form service key hasn't been added yet
        const key = form.elements.access_key ? form.elements.access_key.value : "";
        if (!key || key.indexOf("YOUR_") === 0) {
            console.warn("contact.html: set your Web3Forms access key in the hidden access_key field.");
            showStatus("danger", FALLBACK_HTML);
            return;
        }

        setBusy(true);
        try {
            const response = await fetch(form.action, {
                method: "POST",
                body: new FormData(form),
                headers: { Accept: "application/json" },
            });

            let data = null;
            try {
                data = await response.json();
            } catch (e) {
                /* non-JSON response */
            }

            // Web3Forms replies { success: true }, Formspree replies { ok: true }
            if (response.ok && data && (data.success === true || data.ok === true)) {
                form.reset();
                showStatus("success", SUCCESS_HTML);
            } else {
                showStatus("danger", FALLBACK_HTML);
            }
        } catch (err) {
            showStatus("danger", FALLBACK_HTML);
        } finally {
            setBusy(false);
        }
    });
});
