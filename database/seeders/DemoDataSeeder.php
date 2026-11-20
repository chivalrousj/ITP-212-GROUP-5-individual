<?php

namespace Database\Seeders;

use App\Models\Institute;
use App\Models\Program;
use App\Models\Role;
use App\Models\Student;
use App\Models\User;
use Illuminate\Database\Seeder;
use Illuminate\Support\Facades\Hash;

class DemoDataSeeder extends Seeder
{
    public function run(): void
    {
        // The role names come from RoleSeeder, so run that first
        $officerRole = Role::where('name', 'Officer')->first();
        $adviserRole = Role::where('name', 'Adviser')->first();
        $studentRole = Role::where('name', 'Student')->first();

        if (!$officerRole || !$adviserRole || !$studentRole) {
            $this->command->error('Roles are missing. Run: php artisan db:seed --class=RoleSeeder');
            return;
        }

        // Every sample account uses this password (local lab only)
        $password = 'Student@12345';

        // 1. Sample institutes and programs (replace with your campus data if needed)
        $institutes = [
            'ICS' => 'Institute of Computer Studies',
            'ITE' => 'Institute of Teacher Education',
            'IBM' => 'Institute of Business and Management',
        ];

        $programs = [
            'BSIT'  => ['ICS', 'Bachelor of Science in Information Technology'],
            'BSCS'  => ['ICS', 'Bachelor of Science in Computer Science'],
            'BSED'  => ['ITE', 'Bachelor of Secondary Education'],
            'BEED'  => ['ITE', 'Bachelor of Elementary Education'],
            'BSBA'  => ['IBM', 'Bachelor of Science in Business Administration'],
            'BSHM'  => ['IBM', 'Bachelor of Science in Hospitality Management'],
        ];

        $instituteIds = [];
        foreach ($institutes as $code => $name) {
            // updateOrCreate: running the seeder again does not make duplicates
            $institute = Institute::updateOrCreate(['code' => $code], ['name' => $name]);
            $instituteIds[$code] = $institute->id;
        }

        $programIds = [];
        foreach ($programs as $code => [$instituteCode, $name]) {
            $program = Program::updateOrCreate(
                ['code' => $code],
                ['institute_id' => $instituteIds[$instituteCode], 'name' => $name]
            );
            $programIds[] = $program->id;
        }

        // 2. One Officer and one Adviser
        User::updateOrCreate(
            ['email' => 'officer@spamast.edu.ph'],
            [
                'first_name' => 'Olivia',
                'last_name' => 'Officer',
                'password' => Hash::make($password),
                'role_id' => $officerRole->id,
                'is_active' => true,
                'is_admin' => false,
            ]
        );

        User::updateOrCreate(
            ['email' => 'adviser@spamast.edu.ph'],
            [
                'first_name' => 'Adrian',
                'last_name' => 'Adviser',
                'password' => Hash::make($password),
                'role_id' => $adviserRole->id,
                'is_active' => true,
                'is_admin' => false,
            ]
        );

        // 3. Twenty students, both Male and Female, spread across the programs
        $students = [
            ['Juan', 'Dela Cruz', 'Male'],
            ['Maria', 'Santos', 'Female'],
            ['Jose', 'Reyes', 'Male'],
            ['Ana', 'Garcia', 'Female'],
            ['Carlos', 'Mendoza', 'Male'],
            ['Liza', 'Gonzales', 'Female'],
            ['Mark', 'Torres', 'Male'],
            ['Grace', 'Ramos', 'Female'],
            ['Paolo', 'Flores', 'Male'],
            ['Jenny', 'Castillo', 'Female'],
            ['Miguel', 'Aquino', 'Male'],
            ['Rhea', 'Villanueva', 'Female'],
            ['Kevin', 'Bautista', 'Male'],
            ['Angel', 'Navarro', 'Female'],
            ['Ryan', 'Domingo', 'Male'],
            ['Cherry', 'Salazar', 'Female'],
            ['Jerome', 'Pascual', 'Male'],
            ['Nica', 'Fernandez', 'Female'],
            ['Daniel', 'Cruz', 'Male'],
            ['Bea', 'Lopez', 'Female'],
        ];

        foreach ($students as $i => [$first, $last, $sex]) {
            $number = $i + 1;

            $user = User::updateOrCreate(
                ['email' => sprintf('student%02d@spamast.edu.ph', $number)],
                [
                    'first_name' => $first,
                    'last_name' => $last,
                    'password' => Hash::make($password),
                    'role_id' => $studentRole->id,
                    'is_active' => true,
                    'is_admin' => false,
                ]
            );

            // students row linked to the user (one row for each user)
            Student::updateOrCreate(
                ['user_id' => $user->id],
                [
                    'program_id' => $programIds[$i % count($programIds)],
                    'student_no' => sprintf('2026-%04d', $number),
                    'sex' => $sex,
                    'year_level' => ($i % 4) + 1,
                ]
            );
        }

        $this->command->info('Demo data is ready. Sample password: ' . $password);
    }
}
