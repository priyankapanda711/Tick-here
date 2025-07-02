import { initLoader, showLoader, hideLoader } from "../components/loader/loader.js";

const sign_up_button = document.getElementById("sign_up") as HTMLButtonElement;

initLoader();

sign_up_button?.addEventListener("click", async function (e) {
  e.preventDefault();

  const name = (
    document.getElementById("name") as HTMLInputElement
  )?.value.trim();
  const email = (
    document.getElementById("email") as HTMLInputElement
  )?.value.trim();
  const password = (document.getElementById("password") as HTMLInputElement)
    ?.value;

  if (!name || !email || !password) {
    alert("Please fill in all fields.");
    return;
  }

  console.log("request");
  console.log(name);
  console.log(email);
  console.log(password);


  if (!email.includes("@gmail.com")) {
    alert("Not a valid Email");
    return;
  }

  if (password.length < 8) {
    alert("Minimum 8 digit password required");
    return;
  }

  showLoader();
  try {
    const response = await fetch("http://127.0.0.1:8000/api/users", {
      method: "POST",
      headers: {
        "Content-Type": "application/json",
      },
      body: JSON.stringify({ name, email, password }),
    });

    const result = await response.json();

    console.log(result);

    if (!response.ok) {
      alert(`Signup failed: ${result.message || "Unknown error"}`);
    } else {
      alert("Account created successfully!");
      window.location.href = "/Login"
    }
  } catch (error) {
    console.error("Signup error:", error);
    alert("Something went wrong. Please try again later.");
  }
  hideLoader();
});
