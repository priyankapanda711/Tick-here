export function loadNavbar() {
    const navbarContainer = document.getElementById("navbar");
    if (!navbarContainer) {
        console.error("Navbar container not found");
        return;
    }
    fetch("/components/navbar/index.html")
        .then((response) => {
        if (!response.ok) {
            throw new Error("Failed to fetch navbar");
        }
        return response.text();
    })
        .then((html) => {
        if (navbarContainer) {
            navbarContainer.innerHTML = html;
            // User profile dropdown logic
            const userAvatar = document.getElementById("userAvatar");
            const frameContainer = document.getElementById("frameContainer");
            if (userAvatar && frameContainer) {
                let isVisible = false;
                userAvatar.addEventListener("click", (e) => {
                    e.stopPropagation();
                    isVisible = !isVisible;
                    if (isVisible) {
                        frameContainer.style.display = "flex";
                        frameContainer.style.position = "absolute";
                        frameContainer.style.top = "4rem";
                        frameContainer.style.right = "2rem";
                        frameContainer.style.zIndex = "999";
                        frameContainer.style.backgroundColor = "white";
                        frameContainer.style.boxShadow = "0 4px 6px rgba(0,0,0,0.1)";
                    }
                    else {
                        frameContainer.style.display = "none";
                    }
                });
                document.addEventListener("click", (e) => {
                    if (!frameContainer.contains(e.target) &&
                        e.target !== userAvatar) {
                        frameContainer.style.display = "none";
                        isVisible = false;
                    }
                });
            }
            // Navigation + Active indicator
            const navWrapper = document.getElementById("navWrapper");
            const links = navWrapper.querySelectorAll("a");
            const indicator = document.getElementById("activeIndicator");
            links.forEach((link) => {
                link.addEventListener("click", (e) => {
                    e.preventDefault();
                    const targetText = (link.textContent || "").trim().toLowerCase();
                    let path = "";
                    if (targetText.includes("home"))
                        path = "/";
                    else if (targetText.includes("all events"))
                        path = "/events";
                    else if (targetText.includes("my bookings"))
                        path = "/bookings";
                    if (path) {
                        window.location.href = path;
                    }
                });
            });
            // Automatically highlight the current nav item based on location
            const currentPath = window.location.pathname;
            links.forEach((link) => {
                var _a, _b;
                const text = (_a = link.textContent) === null || _a === void 0 ? void 0 : _a.trim().toLowerCase();
                const isActive = (text === "home" && currentPath === "/") ||
                    (text === "all events" && currentPath.includes("/events")) ||
                    (text === "my bookings" && currentPath.includes("/bookings")) ||
                    (text === "contact us" && currentPath.includes("/contact"));
                if (isActive) {
                    const rect = link.getBoundingClientRect();
                    const parentRect = navWrapper.getBoundingClientRect();
                    indicator.style.width = `${rect.width}px`;
                    indicator.style.left = `${rect.left - parentRect.left}px`;
                    (_b = link.parentElement) === null || _b === void 0 ? void 0 : _b.classList.add("active");
                }
            });
        }
    })
        .catch((error) => {
        console.error("Error loading navbar:", error);
    });
}
