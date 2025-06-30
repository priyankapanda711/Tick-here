export function loadLocationForm(): void {
  const locationForm = document.getElementById("location-form") as HTMLDivElement;

  fetch("/Admin/components/create_location/index.html")
    .then((response) => {
      if (!response.ok) {
        throw new Error("Failed to fetch location form");
      }
      return response.text();
    })
    .then((html) => {
      locationForm.innerHTML = html;

      const modal = locationForm;
      const cityInput = document.getElementById("city") as HTMLInputElement;
      const stateInput = document.getElementById("state") as HTMLInputElement;
      const countryInput = document.getElementById("country") as HTMLInputElement;

      const createBtn = document.getElementById("createLocationBtn") as HTMLButtonElement;
      const cancelBtn = document.getElementById("cancelBtn") as HTMLButtonElement;

      createBtn.addEventListener("click", async () => {
        const city = cityInput.value.trim();
        const state = stateInput.value.trim();
        const country = countryInput.value.trim();

        if (!city || !state || !country) {
          alert("Please fill out all fields.");
          return;
        }

        const payload = {
          city: city,
          state: state,
          country: country,
        };

        try {
          await apiPost("/admin/locations", payload);
          alert("Location created successfully!");
          clearFields();
          modal.classList.add("hidden");
        } catch (err) {
          console.error(err);
          alert(err instanceof Error ? err.message : "Error creating location.");
        }
      });

      cancelBtn.addEventListener("click", () => {
        clearFields();
        modal.classList.add("hidden");
      });

      function clearFields() {
        cityInput.value = "";
        stateInput.value = "";
        countryInput.value = "";
      }
    })
    .catch((err) => {
      console.error(err);
      alert("Could not load location form.");
    });
}

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
