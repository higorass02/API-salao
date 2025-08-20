<?php

use Illuminate\Database\Migrations\Migration;
use Illuminate\Database\Schema\Blueprint;
use Illuminate\Support\Facades\Schema;
use App\Models\Collaborator;
use App\Models\Schedule;

return new class extends Migration
{
    public function up(): void
    {
        $collaborators = Collaborator::all();

        foreach ($collaborators as $collaborator) {
            // Quarta (3) a Sábado (6)
            for ($day = 3; $day <= 6; $day++) {
                Schedule::updateOrCreate(
                    [
                        'collaborator_id' => $collaborator->id,
                        'day_of_week' => $day,
                    ],
                    [
                        'start_time' => '09:00:00',
                        'end_time' => '17:00:00',
                    ]
                );
            }
        }
    }

    public function down(): void
    {
        // Remove os horários criados pela migration
        $collaborators = Collaborator::all();

        foreach ($collaborators as $collaborator) {
            Schedule::where('collaborator_id', $collaborator->id)
                ->whereBetween('day_of_week', [3, 6])
                ->delete();
        }
    }
};
