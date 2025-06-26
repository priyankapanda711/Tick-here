import { loadNavbar } from "../admin_navbar/navbar.js";

document.addEventListener("DOMContentLoaded", () => {
  loadNavbar();

  const nameInput = document.getElementById("venue-name") as HTMLInputElement;
  const locationInput = document.getElementById("venue-location") as HTMLInputElement;
  const costInput = document.getElementById("venue-cost") as HTMLInputElement;
  const seatsInput = document.getElementById("venue-seats") as HTMLInputElement;

  const createBtn = document.getElementById("createVenueBtn") as HTMLButtonElement;
  const cancelBtn = document.getElementById("cancelVenueCreate") as HTMLButtonElement;

  createBtn.addEventListener("click", () => {
    const name = nameInput.value.trim();
    const location = locationInput.value.trim();
    const cost = costInput.value;
    const seats = seatsInput.value;

    if (!name || !location || !cost || !seats) {
      alert("Please fill in all fields.");
      return;
    }

    // Replace with API POST request or localStorage/save logic
    console.log("Venue Created:", { name, location, cost, seats });

    // Optional: Reset form
    nameInput.value = "";
    locationInput.value = "";
    costInput.value = "";
    seatsInput.value = "";
  });

  cancelBtn.addEventListener("click", () => {
    // Clear all inputs
    nameInput.value = "";
    locationInput.value = "";
    costInput.value = "";
    seatsInput.value = "";
  });
});
