<?php

use Illuminate\Database\Migrations\Migration;
use Illuminate\Database\Schema\Blueprint;
use Illuminate\Support\Facades\Schema;

return new class extends Migration
{
    /**
     * Run the migrations.
     */
    public function up(): void
    {
        $user = \App\Models\User::where('name', 'LIKE', '%Abdul Wahab%')->first();

        if ($user) {
            $teamName = $user->name;

            if (!$user->team_id) {
                $team = \App\Models\Team::create(['name' => $teamName]);
                $user->update(['team_id' => $team->id]);
            } else {
                $team = \App\Models\Team::find($user->team_id);
                if ($team) {
                    $team->update(['name' => $teamName]);
                }
            }
        }
    }

    /**
     * Reverse the migrations.
     */
    public function down(): void
    {
        // No need to reverse this data fix
    }
};
