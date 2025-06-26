import { loadContactModal } from "../components/contact-modal/contactModal.js";

import { loadNavbar } from "../components/navbar/navbar.js"

document.addEventListener("DOMContentLoaded", () => {
    loadNavbar();
   
    loadContactModal();
});