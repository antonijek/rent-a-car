<?php

namespace App\Http\Controllers\api;

use App\Http\Controllers\Controller;
use App\Http\Requests\ReservationRequest;
use App\Http\Requests\UpdateReservationRequest;
use App\Mail\ConfirmationOfReservation;
use App\Models\Car;
use App\Models\Reservation;
use App\Models\User;
use Illuminate\Http\Request;
use Illuminate\Support\Facades\Auth;
use Illuminate\Support\Facades\Mail;
use Maatwebsite\Excel\Facades\Excel;
use App\Exports\Reservations;


class ReservationController extends Controller
{
    public function index(Request $request)
    {
        $startDate = $request->input('start_date');
        $endDate = $request->input('end_date');

        if ($startDate && $endDate) {
            $reservations = Reservation::whereBetween('taken_at', [$startDate, $endDate])->get();
        } else {
            $reservations = Reservation::all();
        }

        $data = [];

        foreach ($reservations as $reservation) {
            $user = User::find($reservation->user_id);
            $car = Car::find($reservation->car_id);

            $data[] = [
                'reservation_id' => $reservation->id,
                'user' => $user->name,
                'car_name' => "$car->producer - $car->model",
                'car_year' => "$car->year",
                'taken_at' => $reservation->taken_at,
                'returned_at' => $reservation->returned_at
            ];
        }

        return response()->json($data);
    }


    public function export(Request $request)
    {
        $brand = $request->input('producer');
        $model = $request->input('model');
        $startDate = $request->input('start_date');
        $endDate = $request->input('end_date');
        $minPrice = $request->input('min_price');
        $maxPrice = $request->input('max_price');

        $query = Reservation::query();

        if ($brand) {
            $query->whereHas('car', function ($carQuery) use ($brand) {
                $carQuery->where('brand', $brand);
            });
        }

        if ($model) {
            $query->whereHas('car', function ($carQuery) use ($model) {
                $carQuery->where('model', $model);
            });
        }

        if ($startDate && $endDate) {
            $query->whereBetween('taken_at', [$startDate, $endDate]);
        }

        if ($minPrice) {
            $query->whereHas('car', function ($carQuery) use ($minPrice) {
                $carQuery->where('price', '>=', $minPrice);
            });
        }

        if ($maxPrice) {
            $query->whereHas('car', function ($carQuery) use ($maxPrice) {
                $carQuery->where('price', '<=', $maxPrice);
            });
        }

        $reservations = $query->get();

        $export = new Reservations($reservations);

        return Excel::download($export, 'reservations.xlsx');
    }






















    public function create(ReservationRequest $request)
    {
        $validatedData = $request->validated();
        $user = $request->user();
        $car = Car::find($validatedData['car_id']);

        if (!$car) {
            return response()->json('Car not found', 404);
        }

        if (!$car->available) {
            return response()->json('The car is not available', 400);
        }

        $reservation = new Reservation();
        $reservation->user_id = $user->id;
        $reservation->car_id = $car->id;
        $reservation->taken_at = $validatedData['taken_at'];
        $reservation->returned_at = $validatedData['returned_at'];
        $reservation->save();

        $car->update(['available' => 0]);
        Mail::to($user->email)->queue(new ConfirmationOfReservation($reservation));
        return response()->json('Reservation created');
    }

    public function showMyReservations(Request $request)
    {
        $user = $request->user();

        $startDate = $request->input('start_date');
        $endDate = $request->input('end_date');

        if ($startDate && $endDate) {
            $reservations = $user->reservations()->whereBetween('taken_at', [$startDate, $endDate])->get();
        } else {
            $reservations = $user->reservations;
        }

        $data = [];

        foreach ($reservations as $reservation) {
            $car = $reservation->car()->first();

            $data[] = [
                'reservation_id' => $reservation->id,
                'user' => $user->name,
                'car_name' => "{$car->producer} - {$car->model}",
                'car_year' => $car->year,
                'taken_at' => $reservation->taken_at,
                'returned_at' => $reservation->returned_at
            ];
        }

        return response()->json($data);
    }

    public function update(UpdateReservationRequest $request, string $id)
    {
        $user = $request->user();
        $reservation = Reservation::findOrFail($id);

        // Check if the user is either an admin or the user who made the reservation
        if (!$user->isAdmin() && $reservation->user_id !== $user->id) {
            return response()->json('You are not authorized to update this reservation', 403);
        }

        $validatedData = $request->validated();
        $newCar = Car::findOrFail($validatedData['car_id']);

        if (!$newCar->available) {
            return response()->json('The new car is not available', 400);
        }

        $reservation->car_id = $newCar->id;
        $reservation->taken_at = $validatedData['taken_at'];
        $reservation->returned_at = $validatedData['returned_at'];
        $reservation->save();

        $previousCar = $reservation->car;
        $previousCar->update(['available' => 1]);
        $newCar->update(['available' => 0]);

        return response()->json('Reservation updated!');
    }


    public function destroy(string $id)
    {
        $reservation = Reservation::find($id);

        if (!$reservation) {
            return response()->json('Reservation not found', 404);
        }

        $user = Auth::user();

        if ($reservation->user_id !== $user->id &&!$user->isAdmin) {
            return response()->json('You are not authorized to cancel this reservation', 403);
        }

        $car = $reservation->car;

        $reservation->delete();

        // Update the car availability
        $car->update(['available' => 1]);

        return response()->json('Reservation canceled successfully');
    }
}
