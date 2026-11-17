document.addEventListener("DOMContentLoaded", () => {

    // Welcome trasnsition

    const welcomeElement = document.getElementById("welcome-language");

    if (!welcomeElement) return;

    const welcomeWords = [
        "Karibu",          // Swahili
        "Welcome",         // English
        "Willkommen",      // German
        "Bienvenue",      // French
        "Bienvenido",     // Spanish
        "أهلاً وسهلاً",    // Arabic
        "Benvenuto",      // Italian
        "ようこそ",         // Japanese
        "欢迎",             // Chinese
        "Bem-vindo",      // Portuguese
        "Welkom",         // Dutch
        "स्वागत है"        // Hindi
    ];

    let currentIndex = 0;

    setInterval(() => {

        welcomeElement.classList.add("welcome-fade-out");

        setTimeout(() => {

            currentIndex = (currentIndex + 1) % welcomeWords.length;

            welcomeElement.textContent = welcomeWords[currentIndex];

            welcomeElement.classList.remove("welcome-fade-out");
            welcomeElement.classList.add("welcome-fade-in");

            setTimeout(() => {
                welcomeElement.classList.remove("welcome-fade-in");
            }, 500);

        }, 350);

    }, 2500);


    // Navbar auto

    const navLinks = document.querySelectorAll("#mainNav [data-nav]");

    if (!navLinks.length) return;

    const currentPath = window.location.pathname;
    const currentHash = window.location.hash;

    const homePath = new URL("./", window.location.href).pathname;

    function setActiveLink(activeNav) {

        navLinks.forEach(link => {
            const isActive = link.dataset.nav === activeNav;

            link.classList.toggle("active", isActive);

            if (isActive) {
                link.setAttribute("aria-current", "page");
            } else {
                link.removeAttribute("aria-current");
            }
        });

    }

    // Identify the current page.
    const isHomePage = currentPath === homePath ||
    currentPath.endsWith("/index.html");

    const isAboutPage = currentPath.endsWith("/about.html");
    const isContactPage = currentPath.endsWith("/contact.html");

    // Normal pages.
    if (isAboutPage) {
        setActiveLink("about");
        return;
    }

    if (isContactPage) {
        setActiveLink("contact");
        return;
    }

    if (!isHomePage) return;

    // Homepage section navigation.
    const sectionLinks = [...navLinks].filter(link =>
        ["services", "projects"].includes(link.dataset.nav)
    );

    const sections = sectionLinks
    .map(link => document.getElementById(link.dataset.nav))
    .filter(Boolean);

    function activateFromHash() {

        const sectionId = window.location.hash.substring(1);

        if (sectionId && document.getElementById(sectionId)) {
            setActiveLink(sectionId);
        } else {
            setActiveLink("home");
        }

    }

    // Handle direct links and browser history.
    activateFromHash();

    window.addEventListener("hashchange", activateFromHash);

    // Detect the visible section while scrolling.
    if ("IntersectionObserver" in window && sections.length) {

        const observer = new IntersectionObserver(entries => {

            const visibleSections = entries
            .filter(entry => entry.isIntersecting)
            .sort((a, b) =>
                b.intersectionRatio - a.intersectionRatio
            );

            if (visibleSections.length) {
                setActiveLink(visibleSections[0].target.id);
            }

        }, {
            rootMargin: "-25% 0px -60% 0px",
            threshold: [0, 0.1, 0.25, 0.5]
        });

        sections.forEach(section => observer.observe(section));

    }

    /* Dynamic Project Modal */
    const projectModal = document.getElementById("projectModal");

    if (projectModal) {
        projectModal.addEventListener("show.bs.modal", (event) => {

            const button = event.relatedTarget;

            const title = button?.dataset.title || "Project";
            const description = button?.dataset.description || "";
            const image = button?.dataset.image || "";

            projectModal.querySelector(".modal-title").textContent = title;
            projectModal.querySelector("#projectDescription").textContent = description;

            const modalImage = projectModal.querySelector("#projectImage");

            if (image) {
                modalImage.src = image;
                modalImage.alt = title;
                modalImage.style.display = "block";
            } else {
                modalImage.style.display = "none";
            }
        });
    }


    /* Project Category Filtering */
    const projectFilters = document.querySelectorAll(".project-filter");
    const projectItems = document.querySelectorAll(".project-item");

    projectFilters.forEach((filter) => {
        filter.addEventListener("click", () => {

            const category = filter.dataset.filter;

            // Update active filter
            projectFilters.forEach((button) => {
                button.classList.remove("active");
                button.setAttribute("aria-pressed", "false");
            });

            filter.classList.add("active");
            filter.setAttribute("aria-pressed", "true");

            // Filter projects
            projectItems.forEach((item) => {
                const matches =
                category === "all" ||
                item.dataset.category === category;

                item.classList.toggle("is-hidden", !matches);
            });
        });
    });

    /* Auto Copyright Year */
    const yearElement = document.getElementById("year");

    if (yearElement) {
        yearElement.textContent = new Date().getFullYear();
    }

});

