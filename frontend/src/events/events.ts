import { loadNavbar } from "../components/navbar/navbar.js";
import { loadFooter } from "../components/footer/footer.js";
import { loadContactModal } from "../components/contact-modal/contactModal.js";
import { loadLocationModal } from "../components/location-modal/locationModal.js";
import { renderTagCategories } from "../components/category/tagCategorySection.js";
import { createEventCard } from "../components/event-card/eventCard.js";

function loadEventsForEventsPage(selectedCategories: string[] = []): void {
  const selected = sessionStorage.getItem("selectedLocation");
  if (!selected) return;

  const location = JSON.parse(selected);
  const now = new Date();

  $.ajax({
    url: `http://127.0.0.1:8000/api/events/locations/${location.id}`,
    method: "GET",
    success: async function (res: any) {
      const allContainer = $("section:has(h2:contains('All Events')) .grid");
      const upcomingContainer = $(
        "section:has(h2:contains('Upcoming Events')) .grid"
      );

      allContainer.empty();
      upcomingContainer.empty();

      //to track the count of events
      let allEventsCount = 0;
      let upcomingEventsCount = 0;

      for (const event of res.data) {
        //if this event is not from the selected categories then skip this event, otherwise if no category is selected then render
        if (
          selectedCategories.length > 0 &&
          !selectedCategories.includes(event.category)
        ) {
          continue; // skip if not in selected category
        }

        const startDate = new Date(event.start_datetime);
        const cardHtml = await createEventCard(event);

        allContainer.append(cardHtml); // show in "All Events"
        allEventsCount++;

        if (startDate > now) {
          upcomingContainer.append(cardHtml); // show in "Upcoming" if it's in future
          upcomingEventsCount++;
        }
      }

      // If no events matched
      if (allEventsCount === 0) {
        allContainer.append(`
          <div class="col-span-full text-center text-gray-600 text-lg py-10">
            <i class="fa-regular fa-face-frown text-3xl text-purple-600 mb-4"></i>
            <p>No events found for selected category.</p>
          </div>
        `);
      }

      if (upcomingEventsCount === 0) {
        upcomingContainer.append(`
          <div class="col-span-full text-center text-gray-600 text-lg py-10">
            <i class="fa-regular fa-calendar-xmark text-3xl text-purple-600 mb-4"></i>
            <p>No upcoming events available at the moment.</p>
          </div>
        `);
      }
    },
    error: function () {
      console.error("Failed to load events.");
    },
  });
}

document.addEventListener("DOMContentLoaded", () => {
  loadLocationModal();
  loadNavbar();
  loadFooter();
  loadContactModal();

  renderTagCategories(".tag-category-grid", () => {
    //filtering by category
    const filters =
      document.querySelectorAll<HTMLDivElement>(".category-filter");

    //extract the category param values from the url and store them in a selectedCategories array(stack)
    const params = new URLSearchParams(window.location.search);
    let selectedCategories: string[] = params.getAll("category");

    // check if any category is selected from the home page . if yes then 1st add it in the selectedCategories stack and change the url and then remove it from the session storage
    const rawCategory = sessionStorage.getItem("selectedCategory");
    const fromHome = rawCategory ? JSON.parse(rawCategory) : null;

    if (fromHome && !selectedCategories.includes(fromHome)) {
      selectedCategories.push(fromHome);
      const url = new URL(window.location.href);
      url.searchParams.append("category", fromHome);
      window.history.replaceState({}, "", url.toString());
      sessionStorage.removeItem("selectedCategory"); // Use it only once
    }

    //add the category in the selectedCategories array(stack) when the category button is clicked else remove from the stack. then update the url,activestyle,event listing .
    filters.forEach((btn) => {
      btn.addEventListener("click", () => {
        const category = btn.dataset.category;
        if (!category) return;

        if (category.toLowerCase() === "all") {
          selectedCategories = [];
        } else {
          const index = selectedCategories.indexOf(category);
          if (index > -1) {
            selectedCategories.splice(index, 1);
          } else {
            selectedCategories.push(category);
          }
        }

        updateURL();
        applyActiveStyles();
        loadEventsForEventsPage(selectedCategories);
      });
    });

    function updateURL(): void {
      const url = new URL(window.location.href);
      url.searchParams.delete("category");

      selectedCategories.forEach((cat) =>
        url.searchParams.append("category", cat)
      );

      window.history.pushState({}, "", url.toString());
    }

    function applyActiveStyles(): void {
      filters.forEach((btn) => {
        const category = btn.dataset.category || "";
        const isActive =
          (category.toLowerCase() === "all" &&
            selectedCategories.length === 0) ||
          selectedCategories.includes(category);

        btn.classList.toggle("category-active", isActive);
      });
    }

    applyActiveStyles();
    loadEventsForEventsPage(selectedCategories);
  });
});
