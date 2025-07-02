export function loadLocationForm(): void {
  const locationForm = document.getElementById(
    "location-form"
  ) as HTMLFormElement;
  fetch("/Admin/components/create_location/index.html")
    .then((response) => {
      if (!response.ok) {
        throw new Error("Failed to fetch location form");
      }
      return response.text();
    })
    .then((html) => {
      locationForm.innerHTML = html;
      const cityInput = document.getElementById("city") as HTMLInputElement;
      const stateInput = document.getElementById("state") as HTMLInputElement;
      const countryInput = document.getElementById(
        "country"
      ) as HTMLInputElement;
      const createBtn = document.getElementById(
        "createLocationBtn"
      ) as HTMLButtonElement;
      const cancelBtn = document.getElementById(
        "cancelBtn"
      ) as HTMLButtonElement;

      createBtn.addEventListener("click", () => {
        const city = cityInput.value.trim();
        const state = stateInput.value.trim();
        const country = countryInput.value.trim();

        if (!city || !state || !country) {
          alert("Please fill out all fields.");
          return;
        }

        console.log("Creating location:", { city, state, country });
      });
    });
}
