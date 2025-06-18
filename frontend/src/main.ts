import { loadNavbar } from "./components/navbar/navbar.js";
import { loadFooter } from "./components/footer/footer.js";
import { loadContactModal } from "./components/contact-modal/contactModal.js";

declare const Swiper: any;

document.addEventListener("DOMContentLoaded", () => {
  loadNavbar();
  loadFooter();
  loadContactModal();

  // Get the "All Events" button by clicking on "see More >""
  const allEvents = document.getElementById("all-events");

  if (allEvents) {
    allEvents.addEventListener("click", () => {
      const currentPath = window.location.pathname;

      if (currentPath.includes("/events")) {
        // Already inside events path, do nothing or maybe refresh
        window.location.href = "/events";
      } else {
        // Go to events from root or relative path
        window.location.href = currentPath.endsWith("/")
          ? currentPath + "events"
          : currentPath + "/events";
      }
    });
  }

  //carousel logic
  const swiper: any = new Swiper(".swiper", {
    loop: true,
    pagination: {
      el: ".swiper-pagination",
    },
    navigation: {
      nextEl: ".swiper-button-next",
      prevEl: ".swiper-button-prev",
    },
    autoplay: {
      delay: 4000,
    },
  });

  //contact modal
  const contactButtons = document.querySelectorAll(".contact-button");
  const modal = document.getElementById("contact-modal") as HTMLDivElement;
  const closeModal = document.getElementById(
    "close-modal"
  ) as HTMLButtonElement;

  contactButtons.forEach((button) => {
    button.addEventListener("click", () => {
      modal.classList.remove("hidden");
      modal.classList.add("flex");
    });
  });

  closeModal.addEventListener("click", () => {
    modal.classList.remove("flex");
    modal.classList.add("hidden");
  });

  // close when clicking outside the modal content
  modal.addEventListener("click", (e: MouseEvent) => {
    if (e.target === modal) {
      modal.classList.remove("flex");
      modal.classList.add("hidden");
    }
  });
});
