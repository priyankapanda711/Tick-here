import { loadNavbar } from "../components/admin_navbar/navbar.js";

type VenueEntry = {
  id: number;
  venue: string;
  date: string;
  time: string;
};

let savedVenues: VenueEntry[] = [];
let currentVenueId = 0;
let selectedImage: File | null = null;

document.addEventListener("DOMContentLoaded", () => {
  loadNavbar();

  const venueBlock = document.getElementById("venueBlock") as HTMLDivElement;
  const savedVenuesList = document.getElementById("savedVenuesList") as HTMLDivElement;
  const submitFormBtn = document.getElementById("submitFormBtn") as HTMLButtonElement;
  const toggleVenueBtn = document.getElementById("toggleVenueBtn") as HTMLButtonElement;
  const closeVenueBtn = document.getElementById("closeVenueBtn") as HTMLButtonElement;
  const cancelBtn = document.querySelector('button.border.border-gray-400') as HTMLButtonElement;

  const venueInput = document.getElementById("venue") as HTMLSelectElement;
  const dateInput = document.getElementById("to-date") as HTMLInputElement;
  const timeInput = document.getElementById("time") as HTMLInputElement;

  const imageUploadArea = document.querySelector('.border-dashed') as HTMLDivElement;
  const imageLink = imageUploadArea.querySelector('a') as HTMLAnchorElement;

  //Image Upload
  const hiddenFileInput = document.createElement("input");
  hiddenFileInput.type = "file";
  hiddenFileInput.accept = "image/*";

  hiddenFileInput.addEventListener("change", (e) => {
    const target = e.target as HTMLInputElement;
    if (target.files && target.files[0]) {
      selectedImage = target.files[0];
      const reader = new FileReader();
      reader.onload = (e) => {
        imageUploadArea.innerHTML = `<img src="${e.target?.result}" alt="Preview" class="max-w-full max-h-32 object-contain rounded">`;
      };
      reader.readAsDataURL(selectedImage);
    }
  });

  imageLink.addEventListener("click", (e) => {
    e.preventDefault();
    hiddenFileInput.click();
  });

  imageUploadArea.addEventListener("click", () => {
    hiddenFileInput.click();
  });

  //Venue
  toggleVenueBtn.addEventListener("click", () => {
    venueBlock.classList.remove("hidden");
  });

  closeVenueBtn.addEventListener("click", () => {
    venueBlock.classList.add("hidden");
    clearVenueForm();
  });

  toggleVenueBtn.addEventListener("click", () => {
    if (trySaveVenue()) {
      venueBlock.classList.remove("hidden");
    }
  });

  submitFormBtn.addEventListener("click", async () => {
    if (venueBlock && !venueBlock.classList.contains("hidden")) {
      if (venueInput.value !== "Select" || dateInput.value || timeInput.value !== "09:00") {
        if (!venueInput.value || venueInput.value === "Select" || !dateInput.value || !timeInput.value) {
          alert("Venue block is open. Please complete venue details or close it.");
          return;
        } else {
          trySaveVenue();
        }
      }
    }

    const title = (document.getElementById("title") as HTMLInputElement).value;
    const description = (document.getElementById("description") as HTMLTextAreaElement).value;
    const category = (document.getElementById("category") as HTMLSelectElement).value;
    const duration = (document.getElementById("duration") as HTMLInputElement).value;

    if (!title || !description || !category || !duration) {
      alert("Please fill all required fields.");
      return;
    }

    if (savedVenues.length === 0) {
      alert("Please add at least one venue.");
      return;
    }

    const formData = new FormData();
    formData.append("title", title);
    formData.append("description", description);
    formData.append("category_id", category);
    formData.append("duration", duration);

    if (selectedImage) {
      formData.append("image", selectedImage);
    }

    formData.append("venues", JSON.stringify(savedVenues));

    const token = localStorage.getItem("auth_token") || "";
    try {
      const response = await fetch("/api/admin/create-event", {
        method: "POST",
        headers: {
          "Authorization": `Bearer ${token}`,
          "Accept": "application/json",
        },
        body: formData,
      });

      if (!response.ok) {
        const data = await response.json();
        console.error(data);
        alert(`Failed: ${data.message || "Unknown error"}`);
        return;
      }

      alert("Event created successfully!");
      window.location.href = "/admin/manage-events";
    } catch (err) {
      console.error(err);
      alert("API error, check console.");
    }
  });

  cancelBtn.addEventListener("click", () => {
    (document.getElementById("title") as HTMLInputElement).value = "";
    (document.getElementById("description") as HTMLTextAreaElement).value = "";
    (document.getElementById("category") as HTMLSelectElement).value = "";
    (document.getElementById("duration") as HTMLInputElement).value = "1";
    venueInput.value = "Select";
    dateInput.value = "";
    timeInput.value = "09:00";

    selectedImage = null;
    imageUploadArea.innerHTML = `
      <i class="fa-solid fa-image text-3xl text-gray-400 mb-2"></i>
      <p class="text-sm text-gray-500">
        Drop here to attach or
        <a href="#" class="text-blue-500 underline">upload</a>
      </p>
    `;

    venueBlock.classList.add("hidden");
    savedVenues = [];
    currentVenueId = 0;
    savedVenuesList.innerHTML = "";

    window.location.href = "/admin/manage-events";
  });

  function trySaveVenue(): boolean {
    const venue = venueInput.value;
    const date = dateInput.value;
    const time = timeInput.value;

    if (!venue || venue === "Select" || !date || !time) {
      alert("Please fill all venue fields.");
      return false;
    }

    savedVenues.push({
      id: ++currentVenueId,
      venue,
      date,
      time,
    });

    clearVenueForm();
    renderSavedVenues();
    return true;
  }

  function clearVenueForm() {
    venueInput.value = "Select";
    dateInput.value = "";
    timeInput.value = "09:00";
  }

  function renderSavedVenues() {
    savedVenuesList.innerHTML = "";

    savedVenues.forEach((entry) => {
      const container = document.createElement("div");
      container.className = "border border-gray-300 bg-white rounded-md p-4 flex justify-between items-center shadow";

      const details = document.createElement("div");
      details.innerHTML = `
        <p class="font-medium text-sm mb-1">${entry.venue}</p>
        <p class="text-sm text-gray-600">Date: ${entry.date}</p>
        <p class="text-sm text-gray-600">Time: ${entry.time}</p>
      `;

      const actions = document.createElement("div");
      actions.className = "flex gap-4";

      const editBtn = document.createElement("button");
      editBtn.textContent = "Edit";
      editBtn.className = "text-purple-600 text-sm hover:underline";
      editBtn.addEventListener("click", () => {
        venueInput.value = entry.venue;
        dateInput.value = entry.date;
        timeInput.value = entry.time;

        savedVenues = savedVenues.filter((v) => v.id !== entry.id);
        renderSavedVenues();
        venueBlock.classList.remove("hidden");
      });

      const deleteBtn = document.createElement("button");
      deleteBtn.textContent = "Delete";
      deleteBtn.className = "text-red-500 text-sm hover:underline";
      deleteBtn.addEventListener("click", () => {
        savedVenues = savedVenues.filter((v) => v.id !== entry.id);
        renderSavedVenues();
      });

      actions.appendChild(editBtn);
      actions.appendChild(deleteBtn);

      container.appendChild(details);
      container.appendChild(actions);
      savedVenuesList.appendChild(container);
    });
  }
});
