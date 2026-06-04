document.addEventListener("DOMContentLoaded", () => {

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



    /* Auto Copyright Year */
    const yearElement = document.getElementById("year");

    if (yearElement) {
        yearElement.textContent = new Date().getFullYear();
    }

});