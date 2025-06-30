export function loadVenueForm(): void {
  const venueFormContainer = document.getElementById("venue-form") as HTMLDivElement;

  fetch("/Admin/components/create_venue/index.html")
    .then((response) => {
      if (!response.ok) {
        throw new Error("Failed to fetch venue form");
      }
      return response.text();
    })
    .then((html) => {
      venueFormContainer.innerHTML = html;

      const modal = venueFormContainer;
      const nameInput = document.getElementById("venue-name") as HTMLInputElement;
      const locationInput = document.getElementById("venue-location") as HTMLInputElement;
      const costInput = document.getElementById("venue-cost") as HTMLInputElement;
      const seatsInput = document.getElementById("venue-seats") as HTMLInputElement;

      const createBtn = document.getElementById("createVenueBtn") as HTMLButtonElement;
      const cancelBtn = document.getElementById("cancelVenueCreate") as HTMLButtonElement;

      createBtn.addEventListener("click", async () => {
        const name = nameInput.value.trim();
        const location = locationInput.value.trim();
        const cost = costInput.value.trim();
        const seats = seatsInput.value.trim();

        if (!name || !location || !cost || !seats) {
          alert("Please fill in all fields.");
          return;
        }

        const payload: VenuePayload = {
          name,
          location,
          cost: Number(cost),
          seats: Number(seats),
        };

        try {
          await apiPost("/admin/venues", payload);
          alert("Venue created successfully!");
          clearFields();
          modal.classList.add("hidden");
        } catch (err) {
          console.error(err);
          alert(err instanceof Error ? err.message : "Error creating venue.");
        }
      });

      cancelBtn.addEventListener("click", () => {
        clearFields();
        modal.classList.add("hidden");
      });

      function clearFields() {
        nameInput.value = "";
        locationInput.value = "";
        costInput.value = "";
        seatsInput.value = "";
      }
    })
    .catch((err) => {
      console.error(err);
      alert("Could not load venue form.");
    });
}

type VenuePayload = {
  name: string;
  location: string;
  cost: number;
  seats: number;
};

async function apiPost(endpoint: string, data: any): Promise<any> {
  const token = localStorage.getItem("auth_token") || "";
  const response = await fetch("/api" + endpoint, {
    method: "POST",
    headers: {
      "Content-Type": "application/json",
      "Authorization": "Bearer " + token,
      "Accept": "application/json",
    },
    body: JSON.stringify(data),
  });

  if (!response.ok) {
    const error = await response.json();
    throw new Error(error.message || "API request failed");
  }

  return response.json();
}
