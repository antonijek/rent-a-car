<?php

namespace App\Http\Controllers\api;

use App\Http\Controllers\Controller;
use App\Http\Requests\CarRequest;
use App\Models\Car;
use App\Models\Image;
use Illuminate\Http\Request;
use Illuminate\Support\Facades\Storage;

class CarController extends Controller
{

    public function index(Request $request)
    {
        $query = Car::query();

        if ($request->has('model')) {
            $query->where('model', 'like', '%' . $request->input('model') . '%');
        }

        if ($request->has('producer')) {
            $query->where('producer', 'like', '%' . $request->input('producer') . '%');
        }

        if ($request->has('min_price') && $request->has('max_price')) {
            $minPrice = $request->input('min_price');
            $maxPrice = $request->input('max_price');
            $query->whereBetween('price', [$minPrice, $maxPrice]);
        }

        if ($request->has('year')) {
            $query->where('year', $request->input('year'));
        }

        $cars = $query->get();

        return $cars;
    }

    public function store(CarRequest $request)
    {
        $data = $request->validated();
        $data['available'] = 1;

        $file = $request->file('image');
        if ($file) {
            $name = $file->getClientOriginalName();
            $path = $file->store('images', 'public');

            $image = new Image([
                'name' => $name,
                'path' => $path
            ]);

            $car = Car::create($data);
            $car->image()->save($image);
        } else {
            $car = Car::create($data);
        }

        $car['image_path'] = $car->image ? asset('storage/' . $car->image->path) : null;
        unset($car['image']);

        return response()->json([
            'message' => 'Car added successfully',
            'car' => $car
        ]);
    }

    public function update(CarRequest $request, $id)
    {
        $data = $request->validated();
        $car = Car::findOrFail($id);
        $file = $request->file('image');
        if ($file) {
            $name = $file->getClientOriginalName();
            $path = $file->store('public/images');

            if ($car->image) {
                Storage::delete('public/' . $car->image->path);
            }

            if ($car->image) {
                $car->image->update([
                    'name' => $name,
                    'path' => $path
                ]);
            } else {
                $image = new Image([
                    'name' => $name,
                    'path' => $path
                ]);
                $car->image()->save($image);
            }
        }

        $car->update($data);
        $car->refresh();
        $car['image_path'] = $car->image ? asset('storage/' . $car->image->path) : null;
        unset($car['image']);

        return response()->json([
            'message' => 'Car updated successfully',
            'car' => $car
        ]);
    }

    public function destroy($id)
    {
        $car = Car::findOrFail($id);

        if ($car->image) {
            Storage::delete('public/' . $car->image->path);
            $car->image()->delete();
        }

        $car->delete();

        return response()->json(['message' => 'Car deleted successfully']);
    }

    public function search(Request $request)
    {
        $query = Car::query();

        if ($request->has('model')) {
            $query->where('model', 'like', '%' . $request->input('model') . '%');
        }

        if ($request->has('producer')) {
            $query->where('producer', 'like', '%' . $request->input('producer') . '%');
        }

        if ($request->has('min_price') && $request->has('max_price')) {
            $minPrice = $request->input('min_price');
            $maxPrice = $request->input('max_price');
            $query->whereBetween('price', [$minPrice, $maxPrice]);
        }

        if ($request->has('year')) {
            $query->where('year', $request->input('year'));
        }

        $cars = $query->get();

        return $cars;
    }
}
