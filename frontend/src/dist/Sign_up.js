"use strict";
const sign_up_button = document.getElementById('sign_up');
const loader = document.getElementById('loader');
sign_up_button === null || sign_up_button === void 0 ? void 0 : sign_up_button.addEventListener('click', async function (e) {
    var _a, _b, _c;
    e.preventDefault();
    const name = (_a = document.getElementById('name')) === null || _a === void 0 ? void 0 : _a.value.trim();
    const email = (_b = document.getElementById('email')) === null || _b === void 0 ? void 0 : _b.value.trim();
    const password = (_c = document.getElementById('password')) === null || _c === void 0 ? void 0 : _c.value;
    if (!name || !email || !password) {
        alert('Please fill in all fields.');
        return;
    }
    loader.classList.remove('hidden');
    try {
        const response = await fetch('http://127.0.0.1:8000/api/users', {
            method: 'POST',
            headers: {
                'Content-Type': 'application/json',
            },
            body: JSON.stringify({ name, email, password }),
        });
        const result = await response.json();
        if (!response.ok) {
            alert(`Signup failed: ${result.message || 'Unknown error'}`);
        }
        else {
            alert('Account created successfully!');
        }
    }
    catch (error) {
        console.error('Signup error:', error);
        alert('Something went wrong. Please try again later.');
    }
    finally {
        loader.classList.add('hidden');
    }
});
