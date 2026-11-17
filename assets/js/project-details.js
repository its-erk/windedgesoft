
document.addEventListener("DOMContentLoaded", () => {

    const projects = {

        // 01 - SPORTIVIO
        "sportivoio": {
            number: "01",
            title: "Sportivoio",
            category: "Sports Technology",
            type: "Football Prediction Platform",
            status: "Live",

            summary:
                "A football prediction platform delivering daily match predictions, premium insights, match analysis and convenient subscription management.",

            description:
                "Sportivoio is a football-focused prediction platform built to make match insights and football analysis accessible through a modern digital experience. The platform features daily free predictions, premium prediction packages, detailed match insights and subscription options. Members can create accounts, explore available predictions, manage their subscriptions and access premium content. Integrated M-Pesa payment functionality makes subscribing to premium offerings convenient for users. The platform is complemented by an administration portal for managing predictions, subscriptions and platform operations.",

            images: [
                "assets/img/our_projects/sportivoio_home.webp",
                "assets/img/our_projects/sportivoio_predictions.webp",
                "assets/img/our_projects/sportivoio_member_dashboard.webp",
                "assets/img/our_projects/sportivoio_pricing.webp"
            ],

            features: [
                "Daily free football predictions",
                "Premium predictions and exclusive insights",
                "Detailed match analysis",
                "Member registration and account management",
                "Premium subscription plans and pricing",
                "M-Pesa payment integration",
                "Subscription management",
                "Dedicated administration portal"
            ],

            technologies: [
                "PHP",
                "MySQL",
                "JavaScript",
                "Bootstrap",
                "HTML5",
                "CSS3",
                "M-Pesa Integration"
            ]
        },

        // 02 - AVIZITRACK
        "avizitrack": {
            number: "02",
            title: "AviziTrack",
            category: "Business Management System",
            type: "Web Application / Dashboard",
            status: "Delivered",

            summary:
                "A custom business dashboard developed for Avizi Investment, bringing financial information and administrative tools into one intuitive experience.",

            description:
                "AviziTrack is a business dashboard developed for Avizi Investment. The platform provides users and administrators with insights into shares, loans, savings and withdrawals. Its reporting and administration interfaces help organize business information and support day-to-day operations.",

            images: [
                "assets/img/our_projects/avizi_investment_home.webp",
                "assets/img/our_projects/avizitrack_reports.webp",
                "assets/img/our_projects/avizitrack_admin_features.webp"
            ],

            features: [
                "Business financial dashboard",
                "Share and loan information",
                "Savings and withdrawal insights",
                "Financial reporting interface",
                "Administrative management features",
                "Member management and role assignment"
            ],

            technologies: [
                "PHP",
                "Bootstrap",
                "MySQLi",
                "JavaScript"
            ]
        },

        // 03 - MYJIBABA
        "myjibaba": {
            number: "03",
            title: "MyJibaba",
            category: "E-commerce",
            type: "Multi-platform Web Application",
            status: "Upcoming",

            summary:
                "An upcoming e-commerce platform for Jibaba Solutions, designed to connect product discovery, customers and business operations.",

            description:
                "MyJibaba is a planned e-commerce platform for Jibaba Solutions. Its vision is to provide an accessible shopping experience supported by business management capabilities. The planned platform includes product browsing, customer profiles, inventory management and vendor-facing functionality.",

            images: [
                "assets/img/our_projects/myjibaba.webp",
                "assets/img/our_projects/myjibaba2.webp",
                "assets/img/our_projects/elect_shop.webp"
            ],

            features: [
                "Product browsing and discovery",
                "Customer profiles",
                "Inventory management",
                "Product reviews",
                "Vendor dashboard",
                "Future mobile platform integration"
            ],

            technologies: [
                "PHP",
                "Bootstrap",
                "MySQLi"
            ]
        },

        // 04 - NOI CREATIONS
        "noi-creations": {
            number: "04",
            title: "Noi Creations",
            category: "Brand Experience",
            type: "Website / E-commerce",
            status: "Coming Soon",

            summary:
                "An elegant digital storefront for an international brand specializing in handcrafted beaded bags and shoes.",

            description:
                "Noi Creations is a digital brand experience designed around handcrafted beaded bags and shoes. The website aims to showcase the brand's collections and provide an elegant browsing and shopping experience for its international clientele.",

            images: [
                "assets/img/our_projects/noi_cover.webp"
            ],

            features: [
                "Brand-focused website experience",
                "Product collection presentation",
                "Online shopping experience",
                "Fashion-oriented visual presentation"
            ],

            technologies: []
        }

    };

    const params = new URLSearchParams(window.location.search);
    const projectId = params.get("id");

    const project = projects[projectId];

    if (!project) {
        document.getElementById("projectDetails").innerHTML = `
            <section class="container py-5 text-center">
                <h1>Project not found</h1>
                <p class="text-muted">
                    The project you're looking for may not exist.
                </p>
                <a href="index.html#projects"
                   class="btn btn-primary rounded-pill">
                    Return to Projects
                </a>
            </section>
        `;
        document.title = "Project Not Found | Windedgesoft";
        return;
    }

    document.title = `${project.title} | Windedgesoft`;

    // Populate project information
    document.getElementById("projectNumber").textContent =
        `PROJECT ${project.number} / SELECTED WORK`;

    document.getElementById("projectTitle").textContent =
        project.title;

    document.getElementById("projectCategory").textContent =
        project.category;

    document.getElementById("projectType").textContent =
        project.type;

    document.getElementById("projectStatus").textContent =
        project.status;

    document.getElementById("projectSummary").textContent =
        project.summary;

    document.getElementById("projectDescription").textContent =
        project.description;

    document.getElementById("artNumber").textContent =
        project.number;

    // Populate features
    const featureContainer =
        document.getElementById("projectFeatures");

    project.features.forEach(feature => {
        const li = document.createElement("li");
        li.textContent = feature;
        featureContainer.appendChild(li);
    });

    // Populate technology stack
    const techContainer =
        document.getElementById("projectTech");

    if (project.technologies.length) {
        project.technologies.forEach(tech => {
            const item = document.createElement("span");
            item.className = "case-tech-item";
            item.innerHTML =
                `<i class="fas fa-code"></i> ${tech}`;
            techContainer.appendChild(item);
        });
    } else {
        techContainer.innerHTML =
            "<p>Technology details will be added soon.</p>";
    }

    // Image gallery
    let currentImage = 0;

    const galleryImage =
        document.getElementById("galleryImage");

    const galleryCounter =
        document.getElementById("galleryCounter");

    const thumbnails =
        document.getElementById("galleryThumbnails");

    function showImage(index) {

        currentImage =
            (index + project.images.length) %
            project.images.length;

        galleryImage.src = project.images[currentImage];

        galleryImage.alt =
            `${project.title} screenshot ${currentImage + 1}`;

        galleryCounter.textContent =
            `${String(currentImage + 1).padStart(2, "0")} / ${String(project.images.length).padStart(2, "0")}`;

        thumbnails.querySelectorAll("button").forEach((button, i) => {
            button.classList.toggle(
                "active",
                i === currentImage
            );
        });
    }

    project.images.forEach((image, index) => {

        const button = document.createElement("button");

        button.type = "button";
        button.className = "gallery-thumbnail";

        const img = document.createElement("img");
        img.src = image;
        img.alt = `${project.title} screenshot ${index + 1}`;
        img.loading = "lazy";

        button.appendChild(img);

        button.addEventListener("click", () => {
            showImage(index);
        });

        thumbnails.appendChild(button);
    });

    document.getElementById("galleryPrev")
        .addEventListener("click", () => {
            showImage(currentImage - 1);
        });

    document.getElementById("galleryNext")
        .addEventListener("click", () => {
            showImage(currentImage + 1);
        });

    showImage(0);

    // Hide navigation for single-image projects
    if (project.images.length <= 1) {
        document.getElementById("galleryPrev").hidden = true;
        document.getElementById("galleryNext").hidden = true;
        thumbnails.hidden = true;
    }

    // Copyright year
    document.getElementById("year").textContent =
        new Date().getFullYear();

});
