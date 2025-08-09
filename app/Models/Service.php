<?php

namespace App\Models;

use Illuminate\Database\Eloquent\Model;

class Service extends Model
{
    protected $fillable = ['name', 'description', 'category', 'duration', 'price', 'status'];

    public function appointments()
    {
        return $this->hasMany(Appointment::class);
    }
}
