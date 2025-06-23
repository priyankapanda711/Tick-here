import { loadNavbar } from "../components/navbar/navbar.js";
import { loadFooter } from "../components/footer/footer.js";
import { loadContactModal } from "../components/contact-modal/contactModal.js";
document.addEventListener("DOMContentLoaded", () => {
    loadNavbar();
    loadFooter();
    loadContactModal();
});
