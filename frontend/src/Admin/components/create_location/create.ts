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
      });
    });
}

