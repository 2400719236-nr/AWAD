<?php

namespace Database\Seeders;

use App\Models\Course;
use App\Models\CourseRegistration;
use App\Models\Student;
use Illuminate\Database\Seeder;

class CourseRegistrationSeeder extends Seeder
{
    public function run(): void
    {
        // registration number => course codes
        $registrations = [
            '24/U/BBC/0001' => ['BUC3131', 'BUC3132', 'BUC3133'],
            '24/U/BBC/0002' => ['BUC3131', 'BUC3134'],
            '24/U/BBC/0003' => ['BUC3131', 'BUC3132'],
            '24/U/BBC/0004' => ['BUC3133', 'BUC3134'],
            '25/U/BBC/0005' => ['BUC3131'],
        ];

        foreach ($registrations as $regNo => $codes) {
            $studentId = Student::where('registration_number', $regNo)->value('id');

            foreach ($codes as $code) {
                // firstOrCreate never inserts the same student/course pair twice
                CourseRegistration::firstOrCreate(
                    ['student_id' => $studentId, 'course_id' => Course::where('code', $code)->value('id')],
                    ['academic_year' => '2026/2027']
                );
            }
        }
    }
}
