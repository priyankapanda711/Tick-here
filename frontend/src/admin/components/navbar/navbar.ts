export function loadNavbar(): void {
  const navbarContainer = document.getElementById("navbar");
 
  if (!navbarContainer) {
    console.error("Navbar container not found");
    return;
  }
 
  // Correct relative path for fetch
  fetch("/admin/components/navbar/index.html")
    .then((response) => {
      if (!response.ok) {
        throw new Error("Failed to fetch navbar");
      }
      return response.text();
    })
    .then((html) => {
      navbarContainer.innerHTML = html;
 
      // Profile dropdown logic
      const userAvatar = document.getElementById("userAvatar");
      const frameContainer = document.getElementById("frameContainer");
 
      if (userAvatar && frameContainer) {
        let isVisible = false;
 
        userAvatar.addEventListener("click", (e) => {
          e.stopPropagation();
          isVisible = !isVisible;
          frameContainer.style.display = isVisible ? "flex" : "none";
          if (isVisible) {
            frameContainer.style.position = "absolute";
            frameContainer.style.top = "4rem";
            frameContainer.style.right = "2rem";
            frameContainer.style.zIndex = "999";
            frameContainer.style.backgroundColor = "white";
            frameContainer.style.boxShadow = "0 4px 6px rgba(0,0,0,0.1)";
          }
        });
 
        document.addEventListener("click", (e) => {
          if (
            !frameContainer.contains(e.target as Node) &&
            e.target !== userAvatar
          ) {
            frameContainer.style.display = "none";
            isVisible = false;
          }
        });
      }
 
      // Nav logic for admin tabs (Dashboard, Venue, Location)
      const navTabs = document.querySelectorAll(".nav-tab");
 
      navTabs.forEach((tab) => {
        tab.addEventListener("click", () => {
          // Remove active style from all tabs
          navTabs.forEach((t) => {
            t.classList.remove(
              "bg-gradient-to-r",
              "from-[#46006e]",
              "to-[#0a0417]",
              "text-white"
            );
            t.classList.add("border", "border-[#191970]", "text-black");
          });
 
          // Add active style to clicked tab
          tab.classList.remove("border", "border-[#191970]", "text-black");
          tab.classList.add(
            "bg-gradient-to-r",
            "from-[#46006e]",
            "to-[#0a0417]",
            "text-white"
          );
 
          // Navigate based on text
          const text = (tab.textContent || "").trim().toLowerCase();
 
          if (text === "dashboard") window.location.href = "/admin/dashboard";
          else if (text === "venue") window.location.href = "/admin/venue";
          else if (text === "location") window.location.href = "/admin/location";
        });
      });
 
      // Optional: highlight current tab based on URL
      const currentPath = window.location.pathname;
      navTabs.forEach((tab) => {
        const text = tab.textContent?.trim().toLowerCase();
        const match =
          (text === "dashboard" && currentPath.includes("/dashboard")) ||
          (text === "venue" && currentPath.includes("/venue")) ||
          (text === "location" && currentPath.includes("/location"));
 
        if (match) {
          tab.classList.remove("border", "border-[#191970]", "text-black");
          tab.classList.add(
            "bg-gradient-to-r",
            "from-[#46006e]",
            "to-[#0a0417]",
            "text-white"
          );
        }
      });
    })
    .catch((error) => {
      console.error("Error loading navbar:", error);
    });
}
 
 
 