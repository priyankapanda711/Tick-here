import { loadContactModal } from "../../components/contact-modal/contactModal.js";
import { loadNavbar } from "../components/navbar/navbar.js";


document.addEventListener("DOMContentLoaded", () => {
    loadNavbar();
   
    loadContactModal();
});
// add-event.ts
const addEventBtn = document.getElementById("addEventBtn");

addEventBtn?.addEventListener("click", () => {
  // Example: navigate to event form page
  window.location.href = "/admin/add-event";

  // OR: open a modal
  // document.getElementById("eventModal")?.classList.remove("hidden");
});
