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
const button = document.getElementById('sign_in');
button === null || button === void 0 ? void 0 : button.addEventListener('click', function (e) {
    return __awaiter(this, void 0, void 0, function* () {
        var _a, _b;
        e.preventDefault();
        const email = (_a = document.getElementById('email')) === null || _a === void 0 ? void 0 : _a.value.trim();
        const password = (_b = document.getElementById('password')) === null || _b === void 0 ? void 0 : _b.value;
        if (!email || !password) {
            alert('Please fill in both email and password.');
            return;
        }
        try {
            const response = yield fetch('http://127.0.0.1:8000/api/auth/user/login', {
                method: 'POST',
                headers: {
                    'Content-Type': 'application/json',
                },
                body: JSON.stringify({ email, password }),
            });
            if (!response.ok) {
                const error = yield response.json();
                alert(`Login failed: ${error.message || 'Unknown error'}`);
                return;
            }
            const result = yield response.json();
            console.log('Login success:', result);
            // Example: redirect or store token
            // localStorage.setItem('token', result.token);
            // window.location.href = '/dashboard.html';
        }
        catch (error) {
            console.error('Network or server error:', error);
            alert('Something went wrong. Please try again.');
        }
    });
});
