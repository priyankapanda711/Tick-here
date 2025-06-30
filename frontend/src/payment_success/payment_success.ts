const booking_details_string = localStorage.getItem("ticket_data");

if (booking_details_string) {
    const booking_details = JSON.parse(booking_details_string);
    console.log(booking_details);
    console.log(booking_details.seats);

    try {
        fetch("http://127.0.0.1:8000/api/bookseat", {
            method: "POST",
            headers: {
                "content-type": "application/json"
            },
            body: JSON.stringify(booking_details)
        }).then((res) => res.json())
            .then((data) => {
                console.log(data);
                if (data.success) {
                    const userdetails = localStorage.getItem("User_details");
                    const ticket_data = localStorage.getItem("ticket_data");

                    const userObj = userdetails ? JSON.parse(userdetails) : {};
                    const ticketObj = ticket_data ? JSON.parse(ticket_data) : {};

                    ticketObj.seats = JSON.parse(ticketObj.seats); // ✅ convert string to array
                    ticketObj.eventID = parseInt(ticketObj.eventID); // ✅ ensure integer
                    ticketObj.venue_id = parseInt(ticketObj.venue_id); // ✅ ensure integer
                    ticketObj.price = parseFloat(ticketObj.price); // ✅ ensure number

                    const user_ticket_details = {
                        ...userObj,
                        ...ticketObj
                    };
                    console.log(user_ticket_details);




                    fetch('http://127.0.0.1:8000/api/create-ticket', {
                        method: "POST",
                        headers: {
                            "content-type": "application/json",
                            "Authorization": `Bearer ${localStorage.getItem("auth-token")}`,
                        },
                        body: JSON.stringify(user_ticket_details)
                    }).then((res) => res.json())
                        .then((data) => {
                            console.log(data);
                            localStorage.removeItem("ticket_data")
                        })
                }
            })
    } catch (error) {
        console.log(error);
        alert(`Internal Server Error ${error}`);
    }
}
