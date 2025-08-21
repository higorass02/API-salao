<?php

namespace App\Models;

use Illuminate\Database\Eloquent\Model;

class Service extends Model
{
    protected $fillable = ['name', 'description', 'category', 'duration', 'price', 'status'];
    protected $dates = ['created_at', 'updated_at'];

    public function appointments()
    {
        return $this->hasMany(Appointment::class);
    }
}
