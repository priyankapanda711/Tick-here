export async function initLoader(): Promise<void> {
    const exists = document.getElementById("loader");
    if (exists) return;

    const response = await fetch("/components/loader/index.html");
    const html = await response.text();
    document.body.insertAdjacentHTML("beforeend", html);
}

export function showLoader(): void {
    document.getElementById("loader")?.classList.remove("hidden");
}

export function hideLoader(): void {
    document.getElementById("loader")?.classList.add("hidden");
}
