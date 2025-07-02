<!DOCTYPE html>
<html>

<head>
    <meta charset="UTF-8">
    <title>Ticket Confirmation</title>
</head>

<body style="font-family: Arial, sans-serif; background-color: #f9f9f9; margin: 0; padding: 0;">
    <div style="max-width: 600px; margin: 20px auto; background-color: #ffffff; padding: 30px; border-radius: 10px; box-shadow: 0 4px 8px rgba(0,0,0,0.1); text-align: center;">

        <h2 style="color: #333;">Hello {{ $ticket['name'] }},</h2>
        <p style="font-size: 16px;">Your ticket has been booked successfully!</p>

        <img src="{{ $ticket['thumbnail'] }}" alt="Event" style="width: 100%; max-width: 500px; border-radius: 8px; margin: 20px 0;">

        <div style="font-size: 16px; color: #444;">
            <p><strong>Event:</strong> {{ $ticket['event'] }}</p>
            <p><strong>Venue:</strong> {{ $ticket['venue'] }}</p>
            <p><strong>Date:</strong> {{ $ticket['date'] }}</p>
            <p><strong>Seats:</strong> {{ implode(', ', $ticket['seats']) }}</p>
            <p><strong>Price:</strong> ₹{{ $ticket['price'] }}</p>
        </div>

        <p style="margin-top: 30px; font-size: 15px; color: #555;">Thank you for booking with <strong>Tick Here</strong>!</p>
    </div>
</body>

</html>