<!DOCTYPE html>
<html lang="{{ str_replace('_', '-', app()->getLocale()) }}">
<head>
    <meta charset="utf-8">
    <meta name="viewport" content="width=device-width, initial-scale=1">
    <title>Laravel</title>
</head>
<body>
<h1>Reservation Confirmation</h1>
<p>Thank you for making a reservation. Here are the details:</p>

<p>Reservation ID: {{ $reservation->id }}</p>
<p>User: {{ $reservation->user->name }}</p>
<p>Car:{{ $reservation->car->producer }} {{ $reservation->car->model }}</p>
<p>Taken At: {{ $reservation->taken_at }}</p>
<p>Returned At: {{ $reservation->returned_at }}</p>
</body>
</html>
