<?php

namespace Database\Seeders;

#use App\Models\User;
use App\Models\Staff;
use App\Models\Shift;
#use Illuminate\Database\Console\Seeds\WithoutModelEvents;
use Illuminate\Database\Seeder;

class DatabaseSeeder extends Seeder
{
    /**
     * Seed the application's database.
     */
    public function run(): void
    {
        #create sample staff for testing
        $steven = Staff::create([
            'name' => 'Steven Sample',
            'role' => 'manager',
            'phone_number' => '555-0101-1234',
        ]);

        $kevin = Staff::create([
            'name' => 'Kevin Sample',
            'role' => 'server',
            'phone_number' => '555-010-1234',
        ]);

        $michael = Staff::create([
            'name' => 'Michael Sample',
            'role' => 'cook',
            'phone_number' => '555-010-1234',
        ]);

          # Create sample shifts
          Shift::create([
            'staff_id' => $steven->id,
            'day' => '2026-03-10',
            'start_time' => '09:00',
            'end_time' => '17:00',
            'role' => 'manager',
        ]);

        Shift::create([
            'staff_id' => $kevin->id,
            'day' => '2026-03-10',
            'start_time' => '11:00',
            'end_time' => '19:00',
            'role' => 'server',
        ]);

        # Unassigned shift (open for assignment)
        Shift::create([
            'staff_id' => null,
            'day' => '2026-03-11',
            'start_time' => '10:00',
            'end_time' => '18:00',
            'role' => 'cook',
        ]);    
    
    }
}
