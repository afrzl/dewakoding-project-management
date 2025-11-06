<?php

namespace Database\Seeders;

use App\Models\Team;
use Illuminate\Database\Seeder;
use Illuminate\Support\Str;

class TeamInviteCodeSeeder extends Seeder
{
    /**
     * Run the database seeds.
     */
    public function run(): void
    {
        $teams = Team::whereNull('invite_code')->get();

        foreach ($teams as $team) {
            $team->update([
                'invite_code' => strtoupper(Str::random(8))
            ]);
            
            $this->command->info("Generated invite code {$team->invite_code} for {$team->name}");
        }

        $this->command->info('All teams now have invite codes!');
    }
}
