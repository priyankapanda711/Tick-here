import { loadNavbar } from "../components/navbar/navbar.js";
import { loadFooter } from "../components/footer/footer.js";
import { loadContactModal } from "../components/contact-modal/contactModal.js";
document.addEventListener("DOMContentLoaded", () => {
    loadNavbar();
    loadFooter();
    loadContactModal();
    //filtering by category
    const filters = document.querySelectorAll(".category-filter");
    const params = new URLSearchParams(window.location.search);
    let selectedCategories = params.getAll("category");
    function updateURL() {
        const url = new URL(window.location.href);
        url.searchParams.delete("category");
        selectedCategories.forEach((cat) => url.searchParams.append("category", cat));
        window.history.pushState({}, "", url.toString());
    }
    function applyActiveStyles() {
        filters.forEach((btn) => {
            const category = btn.dataset.category || "";
            const isActive = (category.toLowerCase() === "all" && selectedCategories.length === 0) ||
                selectedCategories.includes(category);
            btn.classList.toggle("category-active", isActive);
        });
    }
    filters.forEach((btn) => {
        btn.addEventListener("click", () => {
            const category = btn.dataset.category;
            if (!category)
                return;
            if (category.toLowerCase() === "all") {
                selectedCategories = [];
            }
            else {
                const index = selectedCategories.indexOf(category);
                if (index > -1) {
                    selectedCategories.splice(index, 1);
                }
                else {
                    selectedCategories.push(category);
                }
            }
            updateURL();
            applyActiveStyles();
            // todo reload events based on filters
            // todo fetchEventsByCategories(selectedCategories);
        });
    });
    applyActiveStyles();
});
