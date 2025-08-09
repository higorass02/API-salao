<?php

namespace App\Models;

use Illuminate\Database\Eloquent\Model;

class Schedule extends Model
{
    protected $fillable = ['day_of_week', 'start_time', 'end_time', 'collaborator_id'];

    public function collaborator()
    {
        return $this->belongsTo(Collaborator::class);
    }
}
