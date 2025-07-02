import { loadNavbar } from "../components/admin_navbar/navbar.js";

type VenueEntry = {
  venue: string;
  date: string;
  time: string;
};

const savedVenues: VenueEntry[] = [];

document.addEventListener("DOMContentLoaded", () => {
  loadNavbar();

  const toggleVenueBtn = document.getElementById("toggleVenueBtn") as HTMLButtonElement;
  const venueBlock = document.getElementById("venueBlock") as HTMLDivElement;
  const closeVenueBtn = document.getElementById("closeVenueBtn") as HTMLButtonElement;
  const savedVenuesList = document.getElementById("savedVenuesList") as HTMLDivElement;
  const submitFormBtn = document.getElementById("submitFormBtn") as HTMLButtonElement;

  const venueInput = document.getElementById("venue") as HTMLSelectElement;
  const dateInput = document.getElementById("to-date") as HTMLInputElement;
  const timeInput = document.getElementById("time") as HTMLInputElement;

  toggleVenueBtn.addEventListener("click", () => {
    if (!venueBlock.classList.contains("hidden")) {
      if (trySaveVenue()) {
        venueBlock.classList.remove("hidden");
      }
    } else {
      venueBlock.classList.remove("hidden");
    }
  });

  closeVenueBtn.addEventListener("click", () => {
    venueBlock.classList.add("hidden");
  });

  submitFormBtn.addEventListener("click", () => {
    trySaveVenue();

    const title = (document.getElementById("title") as HTMLInputElement).value;
    const description = (document.getElementById("description") as HTMLTextAreaElement).value;
    const category = (document.getElementById("category") as HTMLSelectElement).value;
    const duration = (document.getElementById("duration") as HTMLInputElement).value;
    const price = (document.getElementById("ticket-price") as HTMLInputElement).value;

    console.log("Form Submitted", {
      title,
      description,
      category,
      duration,
      price,
      venues: savedVenues,
    });

    alert("Event Created!");
  });

  function trySaveVenue(): boolean {
    const venue = venueInput.value;
    const date = dateInput.value;
    const time = timeInput.value;

    if (venue && venue !== "Select" && date && time) {
      savedVenues.push({ venue, date, time });
      renderSavedVenues();

      venueInput.value = "Select";
      dateInput.value = "";
      timeInput.value = "09:00";

      return true;
    }

    return false;
  }

  function renderSavedVenues() {
    savedVenuesList.innerHTML = "";

    savedVenues.forEach((entry, index) => {
      const container = document.createElement("div");
      container.className = "border border-gray-300 bg-white rounded-md p-4 flex justify-between items-center shadow";

      const details = document.createElement("div");
      details.innerHTML = `
        <p class="font-medium text-sm mb-1">Venue ${index + 1}: ${entry.venue}</p>
        <p class="text-sm text-gray-600">Date: ${entry.date}</p>
        <p class="text-sm text-gray-600">Time: ${entry.time}</p>
      `;

      const editBtn = document.createElement("button");
      editBtn.textContent = "Edit";
      editBtn.className = "text-purple-600 text-sm hover:underline";
      editBtn.addEventListener("click", () => {
        venueInput.value = entry.venue;
        dateInput.value = entry.date;
        timeInput.value = entry.time;

        savedVenues.splice(index, 1);
        renderSavedVenues();
        venueBlock.classList.remove("hidden");
      });

      container.appendChild(details);
      container.appendChild(editBtn);
      savedVenuesList.appendChild(container);
    });
  }
});
