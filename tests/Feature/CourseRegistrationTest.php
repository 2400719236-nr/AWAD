<?php

namespace Tests\Feature;

use App\Models\Course;
use App\Models\CourseRegistration;
use App\Models\Student;
use App\Models\User;
use Database\Seeders\DatabaseSeeder;
use Illuminate\Foundation\Testing\RefreshDatabase;
use Tests\TestCase;

class CourseRegistrationTest extends TestCase
{
    use RefreshDatabase;

    protected function setUp(): void
    {
        parent::setUp();
        $this->seed(DatabaseSeeder::class);
    }

    private function user(string $email): User
    {
        return User::where('email', $email)->firstOrFail();
    }

    private function course(string $code): Course
    {
        return Course::where('code', $code)->firstOrFail();
    }

    public function test_student_can_register_for_a_course(): void
    {
        $aisha = $this->user('aisha.nakato@sms.test');

        $this->actingAs($aisha)
            ->post('/registrations', ['course_id' => $this->course('BUC3134')->id])
            ->assertRedirect('/registrations')
            ->assertSessionHas('success');

        $this->assertTrue($aisha->student->courses()->where('code', 'BUC3134')->exists());
    }

    public function test_duplicate_registration_is_prevented(): void
    {
        $aisha = $this->user('aisha.nakato@sms.test');
        $before = CourseRegistration::count();

        // Aisha is already registered for BUC3131 by the seeder
        $this->actingAs($aisha)
            ->post('/registrations', ['course_id' => $this->course('BUC3131')->id])
            ->assertSessionHasErrors(['course_id' => 'This student is already registered for that course.']);

        $this->assertSame($before, CourseRegistration::count());
    }

    public function test_full_course_cannot_be_registered(): void
    {
        $course = $this->course('BUC3134');
        $course->update(['capacity' => $course->registrations()->count()]);

        $this->actingAs($this->user('aisha.nakato@sms.test'))
            ->post('/registrations', ['course_id' => $course->id])
            ->assertSessionHasErrors('course_id');
    }

    public function test_student_cannot_register_someone_else(): void
    {
        $aisha = $this->user('aisha.nakato@sms.test');
        $frank = Student::where('registration_number', '25/U/BBC/0006')->first();

        $this->actingAs($aisha)->post('/registrations', ['student_id' => $frank->id, 'course_id' => $this->course('BUC3134')->id]);

        $this->assertSame(0, $frank->registrations()->count());
        $this->assertTrue($aisha->student->courses()->where('code', 'BUC3134')->exists());
    }

    public function test_student_sees_and_drops_only_their_own_registrations(): void
    {
        $aisha = $this->user('aisha.nakato@sms.test');
        $brianRegistration = Student::where('email', 'brian.mugisha@sms.test')->first()->registrations()->first();

        $this->actingAs($aisha)->get('/registrations')->assertOk()->assertSee('BUC3133')->assertDontSee('BUC3134');
        $this->actingAs($aisha)->delete("/registrations/{$brianRegistration->id}")->assertForbidden();

        $own = $aisha->student->registrations()->first();
        $this->actingAs($aisha)->delete("/registrations/{$own->id}")->assertSessionHas('success');
        $this->assertModelMissing($own);
    }

    public function test_admin_can_register_and_remove_any_student(): void
    {
        $admin = $this->user('admin@sms.test');
        $frank = Student::where('registration_number', '25/U/BBC/0006')->first();

        $this->actingAs($admin)
            ->post('/registrations', ['student_id' => $frank->id, 'course_id' => $this->course('BUC3235')->id])
            ->assertSessionHas('success');

        $registration = $frank->registrations()->first();
        $this->actingAs($admin)->get('/registrations')->assertSee('Frank Tumusiime');
        $this->actingAs($admin)->delete("/registrations/{$registration->id}")->assertSessionHas('success');
        $this->assertModelMissing($registration);
    }

    public function test_lecturer_cannot_manage_registrations(): void
    {
        $lecturer = $this->user('grace.namutebi@sms.test');

        $this->actingAs($lecturer)->get('/registrations')->assertForbidden();
        $this->actingAs($lecturer)->post('/registrations', ['course_id' => $this->course('BUC3131')->id])->assertForbidden();
    }

    public function test_student_dashboard_lists_available_courses(): void
    {
        $this->actingAs($this->user('brian.mugisha@sms.test'))
            ->get('/dashboard')
            ->assertSee('Available courses')
            ->assertSee('BUC3132');
    }
}
