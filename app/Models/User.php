<?php

namespace App\Models;

use Illuminate\Database\Eloquent\Factories\HasFactory;
use Illuminate\Database\Eloquent\SoftDeletes;
use Illuminate\Foundation\Auth\User as Authenticatable;
use Illuminate\Notifications\Notifiable;
use Laravel\Sanctum\HasApiTokens;

class User extends Authenticatable
{
    use HasApiTokens, HasFactory, Notifiable, SoftDeletes;


    protected $fillable = ['name', 'email', 'password', 'role', 'status', 'deleted_at', 'created_at', 'updated_at'];
    protected $hidden = ['password', 'remember_token'];
    protected $dates = ['deleted_at', 'created_at', 'updated_at'];

    protected function casts(): array
    {
        return [
            'email_verified_at' => 'datetime',
            'password' => 'hashed',
        ];
    }

    public function appointmentsAsClient()
    {
        return $this->hasMany(Appointment::class, 'client_id');
    }

    public function appointmentsAsStaff()
    {
        return $this->hasMany(Appointment::class, 'staff_id');
    }

    public function collaborator()
    {
        return $this->hasOne(Collaborator::class);
    }

    protected static function booted()
    {
        static::deleting(function ($user) {
            $user->status = 0;
            $user->save();
        });
    }
}
