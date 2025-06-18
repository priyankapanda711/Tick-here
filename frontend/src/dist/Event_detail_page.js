"use strict";
const urlParameter = new URLSearchParams(window.location.search);
const event_Id = urlParameter.get("event");
const payment_button = document.getElementById('payment-button');
class ListNode {
    constructor(value) {
        this.next = null;
        this.value = value;
    }
}
class LinkedList {
    constructor() {
        this.head = null;
        this._size = 0;
    }
    get size() {
        return this._size;
    }
    add(value) {
        const node = new ListNode(value);
        if (!this.head) {
            this.head = node;
        }
        else {
            let current = this.head;
            while (current.next)
                current = current.next;
            current.next = node;
        }
        this._size++;
    }
    remove(value) {
        if (!this.head)
            return;
        if (this.head.value === value) {
            this.head = this.head.next;
            this._size--;
            return;
        }
        let current = this.head;
        while (current.next && current.next.value !== value) {
            current = current.next;
        }
        if (current.next) {
            current.next = current.next.next;
            this._size--;
        }
    }
    toArray() {
        const result = [];
        let current = this.head;
        while (current) {
            result.push(current.value);
            current = current.next;
        }
        return result;
    }
}
const selectedSeats = new LinkedList();
const stripe = Stripe('pk_test_51RbHaeHBa8sjr9k4JHp8VJ5uUgLSbkZsMlMvmjQnInpNhFV77x5cTJJVNInFQqJAHEBrCScxUVURFfKn0OHlN0mx008GD1wiuq');
payment_button === null || payment_button === void 0 ? void 0 : payment_button.addEventListener('click', async function () {
    if (selectedSeats.size === 0) {
        alert("No Seats Selected ..... Select The Seats First!!");
        return;
    }
    const selected_items = document.querySelectorAll(".selected");
    const formdata = new FormData();
    formdata.append('place', selected_items[0].innerHTML);
    if (selected_items[1].innerHTML === "Today") {
        const today = new Date();
        const formattedDate = today.toISOString().split('T')[0];
        formdata.append('date', formattedDate);
    }
    else if (selected_items[1].innerHTML === "Tomorrow") {
        const today = new Date();
        const tomorrow = new Date(today);
        tomorrow.setDate(today.getDate() + 1);
        const yyyy = tomorrow.getFullYear();
        const mm = String(tomorrow.getMonth() + 1).padStart(2, '0');
        const dd = String(tomorrow.getDate()).padStart(2, '0');
        const formattedTomorrow = `${yyyy}-${mm}-${dd}`;
        formdata.append('date', formattedTomorrow);
    }
    else {
        formdata.append('date', selected_items[1].innerHTML);
    }
    formdata.append('time', selected_items[2].innerHTML);
    formdata.append('seats', JSON.stringify(selectedSeats.toArray()));
    formdata.forEach((value, key) => {
        console.log(`${key}: ${value}`);
    });
    const final_price = document.getElementById("final_price");
    const new_price = final_price === null || final_price === void 0 ? void 0 : final_price.innerHTML.substring(3);
    //stripe payment integration
    const response = await fetch("http://127.0.0.1:8000/api/create-checkout-session", {
        method: "POST",
        headers: { "Content-Type": "application/json" },
        body: JSON.stringify({
            amount: new_price,
            quantity: 1
        })
    });
    const session = await response.json();
    const result = await stripe.redirectToCheckout({ sessionId: session.id });
    if (result.error) {
        alert(result.error.message);
    }
});
if (event_Id) {
    const apiUrl = `http://127.0.0.1:8000/api/events/${event_Id}`;
    fetch(apiUrl)
        .then((res) => {
        if (!res.ok) {
            alert("Failed to fetch event details");
            window.location.href = "http://127.0.0.1:8080/";
        }
        return res.json();
    })
        .then((data) => {
        var _a, _b, _c;
        if (!data.success)
            throw new Error("Event fetch unsuccessful");
        const event = data.payload;
        console.log(event);
        document.getElementById("event-title").textContent = event.title;
        document.getElementById("event-duration").textContent = formatDuration(event.duration);
        document.getElementById("event-description").textContent = event.description;
        document.getElementById("event_category").textContent = event.event_category.name;
        document.getElementById("event-price").textContent = "1299";
        let num_of_venues = event.event_venue.length;
        const venue_div = document.getElementById("Venues_div");
        const show_time_div = document.getElementById("show_time_div");
        const date_div = document.getElementById("date_div");
        for (let index = 0; index < num_of_venues; index++) {
            // Adding venues here
            const venue = document.createElement("p");
            venue.className = "bg-[#EAE2FF] rounded-[10px] px-[34px] py-[17px] w-[377px] text-center border-[1px] cursor-pointer";
            venue.addEventListener('click', function () {
                document.querySelectorAll("#Venues_div .selected").forEach((el) => {
                    el.classList.remove("selected");
                });
                venue.classList.add("selected");
                const selectedVenueId = event.event_venue[index].venue.id;
                fetchAndRenderSeats(selectedVenueId);
            });
            venue.textContent = event.event_venue[index].venue.venue_name;
            venue_div === null || venue_div === void 0 ? void 0 : venue_div.appendChild(venue);
            //respective dates here
            const date = document.createElement("p");
            date.className = "bg-[#EAE2FF] rounded-[10px] px-[34px] py-[17px] w-[200px] text-center border-[1px] cursor-pointer";
            date.addEventListener('click', function () {
                document.querySelectorAll("#date_div .selected").forEach((el) => {
                    el.classList.remove("selected");
                });
                date.classList.add("selected");
            });
            date.textContent = extractDateLabel(event.event_venue[index].start_datetime);
            date_div === null || date_div === void 0 ? void 0 : date_div.appendChild(date);
            //respective times here
            const time = document.createElement("p");
            time.className = "bg-[#EAE2FF] rounded-[10px] px-[34px] py-[17px] w-[320px] text-center border-[1px] cursor-pointer";
            time.addEventListener('click', function () {
                document.querySelectorAll("#show_time_div .selected").forEach((el) => {
                    el.classList.remove("selected");
                });
                time.classList.add("selected");
            });
            time.textContent = extractTime(event.event_venue[index].start_datetime);
            show_time_div === null || show_time_div === void 0 ? void 0 : show_time_div.appendChild(time);
        }
        (_a = venue_div === null || venue_div === void 0 ? void 0 : venue_div.firstElementChild) === null || _a === void 0 ? void 0 : _a.classList.add("selected");
        (_b = date_div === null || date_div === void 0 ? void 0 : date_div.firstElementChild) === null || _b === void 0 ? void 0 : _b.classList.add("selected");
        (_c = show_time_div === null || show_time_div === void 0 ? void 0 : show_time_div.firstElementChild) === null || _c === void 0 ? void 0 : _c.classList.add("selected");
        if (event.event_venue.length > 0) {
            const selectedVenueId = event.event_venue[0].venue.id;
            fetchAndRenderSeats(selectedVenueId);
        }
        const imagePath = `http://127.0.0.1:8000/storage/${event.thumbnail}`;
        const thumbnail = document.getElementById("event-thumbnail");
        thumbnail.src = imagePath;
        thumbnail.alt = event.title;
    })
        .catch((err) => {
        console.error("Error loading event:", err);
    });
}
else {
    console.warn("No event ID found in URL.");
}
function formatDuration(durationStr) {
    const hours = parseInt(durationStr.split(":")[0], 10);
    return `${hours}hr`;
}
function extractTime(datetimeStr) {
    const date = new Date(datetimeStr.replace(" ", "T")); // Ensure it's in ISO format
    const hours = date.getHours();
    const minutes = date.getMinutes();
    const ampm = hours >= 12 ? "PM" : "AM";
    const hour12 = hours % 12 || 12;
    const paddedMinutes = minutes.toString().padStart(2, "0");
    return `${hour12}:${paddedMinutes} ${ampm}`;
}
function extractDateLabel(datetimeStr) {
    const inputDate = new Date(datetimeStr.replace(" ", "T"));
    const today = new Date();
    const tomorrow = new Date();
    tomorrow.setDate(today.getDate() + 1);
    // Reset time for accurate date comparison
    today.setHours(0, 0, 0, 0);
    tomorrow.setHours(0, 0, 0, 0);
    inputDate.setHours(0, 0, 0, 0);
    if (inputDate.getTime() === today.getTime()) {
        return "Today";
    }
    else if (inputDate.getTime() === tomorrow.getTime()) {
        return "Tomorrow";
    }
    else {
        const options = {
            weekday: "short",
            day: "2-digit",
            month: "short",
        };
        return inputDate.toLocaleDateString("en-US", options); // e.g. "Wed, 18 Jun"
    }
}
function fetchAndRenderSeats(venueId) {
    fetch(`http://127.0.0.1:8000/api/venues/${venueId}/seats`)
        .then((res) => {
        if (!res.ok)
            throw new Error("Failed to load seats");
        return res.json();
    })
        .then((data) => {
        if (!data.success)
            throw new Error("Seat fetch unsuccessful");
        const seatContainer = document.querySelector(".seat-grid");
        seatContainer.innerHTML = "";
        const priceElement = document.getElementById("event-price");
        const selectedSeatsDisplay = document.getElementById("selected_seats");
        const finalPriceDisplay = document.getElementById("final_price");
        // Set the price from API
        if (priceElement && data.seats.length > 0) {
            priceElement.textContent = data.seats[0].price;
        }
        data.seats.forEach((seat) => {
            const seatEl = document.createElement("p");
            seatEl.textContent = seat.seat_no;
            if (seat.is_booked) {
                seatEl.className =
                    "p-[10px] rounded-[6px] text-center bg-[#CBBAEA] text-white cursor-not-allowed";
            }
            else {
                seatEl.className =
                    "p-[10px] rounded-[6px] text-center border-1 hover:bg-purple-100 cursor-pointer";
                seatEl.addEventListener("click", function () {
                    const seatNo = seatEl.textContent;
                    seatEl.classList.toggle("selected");
                    if (seatEl.classList.contains("selected")) {
                        selectedSeats.add(seatNo);
                    }
                    else {
                        selectedSeats.remove(seatNo);
                    }
                    const selectedArray = selectedSeats.toArray();
                    selectedSeatsDisplay.textContent = selectedArray.join(", ");
                    const unitPrice = parseFloat(data.seats[0].price);
                    const total = selectedArray.length * unitPrice;
                    if (finalPriceDisplay) {
                        finalPriceDisplay.textContent = `Rs.${total.toFixed(2)}`;
                    }
                });
            }
            seatContainer === null || seatContainer === void 0 ? void 0 : seatContainer.appendChild(seatEl);
        });
    })
        .catch((err) => {
        console.error("Error loading seats:", err);
    });
}
