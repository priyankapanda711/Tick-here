"use strict";
var __awaiter = (this && this.__awaiter) || function (thisArg, _arguments, P, generator) {
    function adopt(value) { return value instanceof P ? value : new P(function (resolve) { resolve(value); }); }
    return new (P || (P = Promise))(function (resolve, reject) {
        function fulfilled(value) { try { step(generator.next(value)); } catch (e) { reject(e); } }
        function rejected(value) { try { step(generator["throw"](value)); } catch (e) { reject(e); } }
        function step(result) { result.done ? resolve(result.value) : adopt(result.value).then(fulfilled, rejected); }
        step((generator = generator.apply(thisArg, _arguments || [])).next());
    });
};
const sign_up_button = document.getElementById("sign_up");
const loader = document.getElementById("loader");
sign_up_button === null || sign_up_button === void 0 ? void 0 : sign_up_button.addEventListener("click", function (e) {
    return __awaiter(this, void 0, void 0, function* () {
        var _a, _b, _c;
        e.preventDefault();
        const name = (_a = document.getElementById("name")) === null || _a === void 0 ? void 0 : _a.value.trim();
        const email = (_b = document.getElementById("email")) === null || _b === void 0 ? void 0 : _b.value.trim();
        const password = (_c = document.getElementById("password")) === null || _c === void 0 ? void 0 : _c.value;
        if (!name || !email || !password) {
            alert("Please fill in all fields.");
            return;
        }
        loader.classList.remove("hidden");
        try {
            const response = yield fetch("http://127.0.0.1:8000/api/users", {
                method: "POST",
                headers: {
                    "Content-Type": "application/json",
                },
                body: JSON.stringify({ name, email, password }),
            });
            const result = yield response.json();
            if (!response.ok) {
                alert(`Signup failed: ${result.message || "Unknown error"}`);
            }
            else {
                alert("Account created successfully!");
            }
        }
        catch (error) {
            console.error("Signup error:", error);
            alert("Something went wrong. Please try again later.");
        }
        finally {
            loader.classList.add("hidden");
        }
    });
});
