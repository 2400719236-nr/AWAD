<?php

namespace Database\Seeders;

use App\Models\Course;
use App\Models\Lecturer;
use Illuminate\Database\Seeder;

class CourseSeeder extends Seeder
{
    public function run(): void
    {
        $grace = Lecturer::where('staff_number', 'STF-001')->value('id');
        $joseph = Lecturer::where('staff_number', 'STF-002')->value('id');

        $courses = [
            ['code' => 'BUC3131', 'title' => 'Advanced Web Application Development', 'credit_units' => 4, 'semester' => 1, 'capacity' => 60, 'lecturer_id' => $grace,
                'description' => 'Building secure, data-driven web applications with Laravel.'],
            ['code' => 'BUC3132', 'title' => 'Database Administration', 'credit_units' => 4, 'semester' => 1, 'capacity' => 50, 'lecturer_id' => $grace,
                'description' => 'Designing, tuning and securing relational databases.'],
            ['code' => 'BUC3133', 'title' => 'Mobile Application Development', 'credit_units' => 3, 'semester' => 1, 'capacity' => 40, 'lecturer_id' => $joseph,
                'description' => 'Developing cross-platform mobile applications.'],
            ['code' => 'BUC3134', 'title' => 'E-Business Systems', 'credit_units' => 3, 'semester' => 1, 'capacity' => 70, 'lecturer_id' => $joseph,
                'description' => 'Information systems that support online business.'],
            ['code' => 'BUC3235', 'title' => 'Information Systems Security', 'credit_units' => 3, 'semester' => 2, 'capacity' => 45, 'lecturer_id' => null,
                'description' => 'Threats, controls and security management for information systems.'],
        ];

        foreach ($courses as $data) {
            Course::updateOrCreate(['code' => $data['code']], $data);
        }
    }
}
