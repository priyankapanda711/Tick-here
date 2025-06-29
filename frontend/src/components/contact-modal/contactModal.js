export function loadContactModal() {
    const modalContainer = document.getElementById("contact-modal-container");
    fetch("/components/contact-modal/index.html")
        .then((response) => {
        if (!response.ok) {
            throw new Error("Failed to fetch contact modal");
        }
        return response.text();
    })
        .then((html) => {
        if (modalContainer) {
            modalContainer.innerHTML = html;
            const modal = document.getElementById("contact-modal");
            const closeModal = document.getElementById("close-modal");
            const contactButtons = document.querySelectorAll(".contact-button");
            contactButtons.forEach((button) => {
                button.addEventListener("click", () => {
                    modal.classList.remove("hidden");
                    modal.classList.add("flex");
                });
            });
            closeModal.addEventListener("click", () => {
                modal.classList.remove("flex");
                modal.classList.add("hidden");
            });
            modal.addEventListener("click", (e) => {
                if (e.target === modal) {
                    modal.classList.remove("flex");
                    modal.classList.add("hidden");
                }
            });
        }
    })
        .catch((error) => {
        console.error("Error loading contact modal:", error);
    });
}
