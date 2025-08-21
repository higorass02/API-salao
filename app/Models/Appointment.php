<?php

namespace App\Models;

use Illuminate\Database\Eloquent\Model;
use Illuminate\Database\Eloquent\SoftDeletes;

class Appointment extends Model
{
    use SoftDeletes;
    protected $fillable = [
        'client_id',
        'staff_id',
        'service_id',
        'dt_appointment',
        'status',
        'notes',
        'deleted_at',
        'created_at',
        'updated_at'
    ];
    protected $dates = ['deleted_at', 'created_at', 'updated_at'];

    public function client()
    {
        return $this->belongsTo(User::class, 'client_id');
    }

    public function staff()
    {
        return $this->belongsTo(User::class, 'staff_id');
    }

    public function service()
    {
        return $this->belongsTo(Service::class);
    }

    protected static function booted()
    {
        static::deleting(function ($appointment) {
            $appointment->status = 0;
            $appointment->save();
        });
    }
}
