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
document.addEventListener("DOMContentLoaded", () => {
  const rows = document.querySelectorAll(".event-row");

  rows.forEach((row) => {
    const editBtn = row.querySelector(".edit-btn") as HTMLDivElement;
    const deleteBtn = row.querySelector(".delete-btn") as HTMLDivElement;

    // ✏️ Handle Edit
    editBtn?.addEventListener("click", () => {
      const nameEl = row.querySelector(".event-name")!;
      const amountEl = row.querySelector(".event-amount")!;
      const dateEl = row.querySelector(".event-date")!;

      const eventName = nameEl.textContent?.trim() ?? "";
      const amount = amountEl.textContent?.trim() ?? "";
      const date = dateEl.textContent?.trim() ?? "";

      const formHtml = `
        <div class="edit-form bg-white p-4 mt-4 rounded shadow w-full">
          <label class="block mb-2">Event Name: <input value="${eventName}" class="input-name border px-2 py-1 w-full" /></label>
          <label class="block mb-2">Amount: <input value="${amount}" class="input-amount border px-2 py-1 w-full" /></label>
          <label class="block mb-2">Date: <input type="text" value="${date}" class="input-date border px-2 py-1 w-full" /></label>
          <button class="save-btn mt-2 bg-blue-500 text-white px-4 py-1 rounded">Save</button>
        </div>
      `;

      // Remove any existing form
      document.querySelector(".edit-form")?.remove();

      row.insertAdjacentHTML("afterend", formHtml);

      const saveBtn = document.querySelector(".save-btn");
      saveBtn?.addEventListener("click", () => {
        const newName = (document.querySelector(".input-name") as HTMLInputElement).value;
        const newAmount = (document.querySelector(".input-amount") as HTMLInputElement).value;
        const newDate = (document.querySelector(".input-date") as HTMLInputElement).value;

        nameEl.textContent = newName;
        amountEl.textContent = newAmount;
        dateEl.textContent = newDate;

        document.querySelector(".edit-form")?.remove();
      });
    });

    // ❌ Handle Delete
    deleteBtn?.addEventListener("click", () => {
      if (confirm("Are you sure you want to delete this event?")) {
        row.remove();
      }
    });
  });
});
