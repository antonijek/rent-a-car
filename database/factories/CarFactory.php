<?php

namespace Database\Factories;

use App\Models\Car;
use Illuminate\Database\Eloquent\Factories\Factory;

class CarFactory extends Factory
{
    protected $model = Car::class;

    private $carBrands = [
        'Toyota',
        'Honda',
        'Ford',
        'Chevrolet',
        'Nissan',
        'Volkswagen',
        'BMW',
        'Mercedes-Benz',
        'Audi',
        'Hyundai',
        'Kia',
        'Subaru',
        'Mazda',
        'Volvo',
        'Lexus',
    ];

    private $carModels = [
        'Camry',
        'Civic',
        'Mustang',
        'Corvette',
        'Altima',
        'Passat',
        '3 Series',
        'E-Class',
        'A4',
        'Elantra',
        'Sorento',
        'Outback',
        'CX-5',
        'S60',
        'RX',
    ];

    public function definition()
    {
        $producer = $this->faker->randomElement($this->carBrands);
        $model = $this->faker->randomElement($this->carModels);

        return [
            'producer' => $producer,
            'model' => $model,
            'year' => $this->faker->numberBetween(2010, 2023),
            'available' => 1,
            'price' => $this->faker->randomFloat(2, 20, 200),

        ];
    }
}
