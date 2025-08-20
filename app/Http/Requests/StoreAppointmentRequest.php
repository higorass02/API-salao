<?php

namespace App\Http\Requests;

use Illuminate\Foundation\Http\FormRequest;
use App\Models\Schedule;
use App\Models\Appointment;

class StoreAppointmentRequest extends FormRequest
{
    public function authorize()
    {
        // Se futuramente quiser checar roles, pode adicionar aqui
        return true;
    }

    public function rules()
    {
        return [
            'client_id' => 'required|exists:users,id',
            'staff_id' => 'required|exists:users,id',
            'service_id' => 'required|exists:services,id',
            'dt_appointment' => 'required|date|after_or_equal:now',
            'notes' => 'nullable|string|max:255',
        ];
    }

    public function withValidator($validator)
    {
        $validator->after(function ($validator) {
            $staff_id = $this->staff_id;
            $dt_appointment = $this->dt_appointment;

            // 1️⃣ Verificar conflito com outro agendamento
            $conflict = Appointment::where('staff_id', $staff_id)
                ->where('dt_appointment', $dt_appointment)
                ->exists();

            if ($conflict) {
                $validator->errors()->add('dt_appointment', 'Colaborador já possui um agendamento nesse horário.');
            }

            // 2️⃣ Verificar se horário está dentro do expediente
            $dayOfWeek = date('w', strtotime($dt_appointment)); // 0=Dom, 1=Seg...
            $schedule = Schedule::where('collaborator_id', $staff_id)
                ->where('day_of_week', $dayOfWeek)
                ->first();

            if (!$schedule) {
                $validator->errors()->add('dt_appointment', 'Colaborador não trabalha nesse dia.');
                return;
            }

            $time = date('H:i:s', strtotime($dt_appointment));
            if ($time < $schedule->start_time || $time > $schedule->end_time) {
                $validator->errors()->add('dt_appointment', 'Horário fora do expediente do colaborador.');
            }
        });
    }
}
