// Import your components (adjust paths as needed)
import { loadNavbar } from "../components/navbar/navbar";
import { loadFooter } from "../components/footer/footer";
import { loadContactModal } from "../components/contact-modal/contactModal";
import { loadLocationModal } from "../components/location-modal/locationModal";

// Define TypeScript interfaces for expected API response types
interface AdminProfile {
name: string;
username: string;
fullName: string;
email: string;
phone: string;
}

interface ActiveAdmin {
name: string;
avatar: string;
}

// Wait for the DOM to load
document.addEventListener("DOMContentLoaded", async () => {
// Load common UI components
try {
loadNavbar();
loadFooter();
loadContactModal();
loadLocationModal();
} catch (error) {
console.error("Component load error:", error);
}

// Get references to DOM elements
const nameEl = document.getElementById("adminName");
const usernameEl = document.getElementById("adminUsername");
const fullNameInput = document.getElementById("adminFullName") as HTMLInputElement;
const emailInput = document.getElementById("adminEmail") as HTMLInputElement;
const phoneInput = document.getElementById("adminPhone") as HTMLInputElement;
const adminList = document.getElementById("adminList");

// Fetch and apply admin profile
try {
const res = await fetch("/api/admin/profile");
if (!res.ok) throw new Error("Profile fetch failed");
const profile: AdminProfile = await res.json();

if (nameEl) nameEl.textContent = profile.name;
if (usernameEl) usernameEl.textContent = profile.username;
if (fullNameInput) fullNameInput.value = profile.fullName;
if (emailInput) emailInput.value = profile.email;
if (phoneInput) phoneInput.value = profile.phone;
} catch (error) {
console.error("Failed to fetch admin profile:", error);
}

// Fetch and render active admins
try {
const res = await fetch("/api/admin/active");
if (!res.ok) throw new Error("Active admins fetch failed");
const activeAdmins: ActiveAdmin[] = await res.json();

if (adminList) {
adminList.innerHTML = ""; // Clear existing list
activeAdmins.forEach((admin) => {
const li = document.createElement("li");
li.className = "flex items-center gap-3";
li.innerHTML = `
<img src="${admin.avatar}" alt="Avatar of ${admin.name}" class="w-10 h-10 rounded-full border" />
<span>${admin.name}</span>
`;
adminList.appendChild(li);
});
}
} catch (error) {
console.error("Failed to fetch active admins:", error);
}
});

