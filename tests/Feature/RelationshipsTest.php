<?php

namespace Tests\Feature;

use App\Models\Course;
use App\Models\CourseRegistration;
use App\Models\Lecturer;
use App\Models\Student;
use App\Models\User;
use Illuminate\Database\QueryException;
use Illuminate\Foundation\Testing\RefreshDatabase;
use Tests\TestCase;

class RelationshipsTest extends TestCase
{
    use RefreshDatabase;

    private function makeStudent(string $regNo): Student
    {
        return Student::create([
            'registration_number' => $regNo,
            'first_name' => 'Test',
            'last_name' => $regNo,
            'email' => strtolower($regNo).'@students.test',
            'gender' => 'female',
            'programme' => 'Bachelor of Business Computing',
            'year_of_study' => 3,
        ]);
    }

    private function makeCourse(string $code, ?Lecturer $lecturer = null): Course
    {
        return Course::create([
            'code' => $code,
            'title' => "Course $code",
            'credit_units' => 4,
            'semester' => 1,
            'lecturer_id' => $lecturer?->id,
        ]);
    }

    public function test_lecturer_has_many_courses_and_course_belongs_to_lecturer(): void
    {
        $user = User::factory()->create();
        $lecturer = Lecturer::create(['user_id' => $user->id, 'staff_number' => 'STF001', 'department' => 'Computing']);
        $course = $this->makeCourse('BUC3131', $lecturer);

        $this->assertTrue($lecturer->courses->contains($course));
        $this->assertTrue($course->lecturer->is($lecturer));
        $this->assertTrue($user->lecturer->is($lecturer));
    }

    public function test_student_courses_and_course_students_are_retrieved_through_registrations(): void
    {
        $student = $this->makeStudent('S001');
        $other = $this->makeStudent('S002');
        $awad = $this->makeCourse('BUC3131');
        $db = $this->makeCourse('BUC3132');

        $student->courses()->attach([$awad->id, $db->id], ['academic_year' => '2026/2027']);
        $other->courses()->attach($awad->id, ['academic_year' => '2026/2027']);

        // A student's registered courses
        $this->assertEqualsCanonicalizing(['BUC3131', 'BUC3132'], $student->courses()->pluck('code')->all());
        // The students registered for a course
        $this->assertEqualsCanonicalizing(['S001', 'S002'], $awad->students()->pluck('registration_number')->all());
        $this->assertSame(2, $student->registrations()->count());
        $this->assertTrue(CourseRegistration::first()->student->is($student));
    }

    public function test_duplicate_registration_is_rejected_by_the_database(): void
    {
        $student = $this->makeStudent('S001');
        $course = $this->makeCourse('BUC3131');

        CourseRegistration::create(['student_id' => $student->id, 'course_id' => $course->id, 'academic_year' => '2026/2027']);

        $this->expectException(QueryException::class);
        CourseRegistration::create(['student_id' => $student->id, 'course_id' => $course->id, 'academic_year' => '2026/2027']);
    }
}
