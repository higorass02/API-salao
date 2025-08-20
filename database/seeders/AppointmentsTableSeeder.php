<?php

namespace Database\Seeders;

use Illuminate\Database\Seeder;
use App\Models\Appointment;

class AppointmentsTableSeeder extends Seeder
{
    public function run()
    {
        Appointment::create(['client_id' => 3, 'staff_id' => 1, 'service_id' => 1, 'dt_appointment' => now()->addDay(), 'status' => 1, 'notes' => 'Agendamento teste']);
        Appointment::create(['client_id' => 3, 'staff_id' => 2, 'service_id' => 2, 'dt_appointment' => now()->addDays(2), 'status' => 1, 'notes' => 'Agendamento teste 2']);
        Appointment::create(['client_id' => 3, 'staff_id' => 1, 'service_id' => 3, 'dt_appointment' => now()->addDays(3), 'status' => 1, 'notes' => 'Agendamento teste 3']);
    }
}
