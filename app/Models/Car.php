<?php

namespace App\Models;

use Illuminate\Database\Eloquent\Factories\HasFactory;
use Illuminate\Database\Eloquent\Model;
use PhpParser\Builder\Class_;

class Car extends Model
{
    use HasFactory;
    protected $guarded = ['id'];

    public function image()
    {
        return $this->morphOne(Image::class, 'imageable');
    }
    public function reservations()
    {
        return $this->hasMany(Reservation::class);
    }
}

