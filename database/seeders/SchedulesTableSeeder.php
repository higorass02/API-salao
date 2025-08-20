<?php

namespace Database\Seeders;

use Illuminate\Database\Seeder;
use App\Models\Schedule;

class SchedulesTableSeeder extends Seeder
{
    public function run()
    {
        Schedule::create(['collaborator_id' => 1, 'day_of_week' => 1, 'start_time' => '09:00:00', 'end_time' => '17:00:00']);
        Schedule::create(['collaborator_id' => 2, 'day_of_week' => 2, 'start_time' => '10:00:00', 'end_time' => '18:00:00']);
        Schedule::create(['collaborator_id' => 1, 'day_of_week' => 3, 'start_time' => '09:00:00', 'end_time' => '15:00:00']);
    }
}
