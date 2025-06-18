"use strict";
const button = document.getElementById('sign_in');
button === null || button === void 0 ? void 0 : button.addEventListener('click', async function (e) {
    var _a, _b;
    e.preventDefault();
    const email = (_a = document.getElementById('email')) === null || _a === void 0 ? void 0 : _a.value.trim();
    const password = (_b = document.getElementById('password')) === null || _b === void 0 ? void 0 : _b.value;
    if (!email || !password) {
        alert('Please fill in both email and password.');
        return;
    }
    try {
        const response = await fetch('http://127.0.0.1:8000/api/auth/user/login', {
            method: 'POST',
            headers: {
                'Content-Type': 'application/json',
            },
            body: JSON.stringify({ email, password }),
        });
        if (!response.ok) {
            const error = await response.json();
            alert(`Login failed: ${error.message || 'Unknown error'}`);
            return;
        }
        const result = await response.json();
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
