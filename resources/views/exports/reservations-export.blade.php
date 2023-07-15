<!DOCTYPE html>
<html lang="{{ str_replace('_', '-', app()->getLocale()) }}">
<head>
    <meta charset="utf-8">
    <meta name="viewport" content="width=device-width, initial-scale=1">
    <title>Laravel</title>
</head>
<body>
<table>
    <thead>
    <tr>
        <th>Reservation ID</th>
        <th>User</th>
        <th>Car Name</th>
        <th>Car Year</th>
        <th>Taken At</th>
        <th>Returned At</th>
    </tr>
    </thead>
    <tbody>
    @foreach ($reservations as $reservation)
        <tr>
            <td>{{ $reservation->id }}</td>
            <td>{{ $reservation->user->name }}</td>
            <td>{{ $reservation->car->producer }}{{ $reservation->car->model }}</td>
            <td>{{ $reservation->car->year }}</td>
            <td>{{ $reservation->taken_at }}</td>
            <td>{{ $reservation->returned_at }}</td>
        </tr>
    @endforeach
    </tbody>
</table>
</body>
</html>
