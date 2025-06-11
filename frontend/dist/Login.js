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
let button = document.getElementById('sign_in');
function getCsrfToken() {
    return __awaiter(this, void 0, void 0, function* () {
        const res = yield fetch('http://127.0.0.1:8000/csrf-token');
        const data = yield res.json();
        return data.token;
    });
}
button === null || button === void 0 ? void 0 : button.addEventListener('click', function (e) {
    return __awaiter(this, void 0, void 0, function* () {
        e.preventDefault();
        const form = document.getElementById('form');
        console.log(form);
        const formData = new FormData(form);
        const token = yield getCsrfToken();
        const password = formData.get('password');
        const email = formData.get('email');
        console.log(token);
        console.log(password);
        console.log(email);
        // const res = await fetch('http://localhost:8000/login', {
        //   method: 'POST',
        //   headers: {
        //     'Content-Type': 'application/json',
        //     'X-CSRF-TOKEN': token,
        //   },
        //   credentials: 'include',
        //   body: JSON.stringify({
        //     email,
        //     password,
        //   }),
        // });
    });
});
