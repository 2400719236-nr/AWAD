<?php

namespace Tests\Feature;

use App\Models\Course;
use App\Models\Lecturer;
use App\Models\User;
use Database\Seeders\DatabaseSeeder;
use Illuminate\Foundation\Testing\RefreshDatabase;
use Tests\TestCase;

class CourseManagementTest extends TestCase
{
    use RefreshDatabase;

    private User $admin;

    protected function setUp(): void
    {
        parent::setUp();
        $this->seed(DatabaseSeeder::class);
        $this->admin = User::where('email', 'admin@sms.test')->first();
    }

    private function validData(array $overrides = []): array
    {
        return array_merge([
            'code' => 'buc3240',
            'title' => 'Cloud Computing',
            'description' => 'Cloud service models and deployment.',
            'credit_units' => 3,
            'semester' => 2,
            'capacity' => 40,
            'lecturer_id' => Lecturer::first()->id,
        ], $overrides);
    }

    public function test_admin_can_create_update_and_delete_a_course(): void
    {
        $this->actingAs($this->admin)->post('/courses', $this->validData())->assertSessionHas('success');
        $course = Course::where('code', 'BUC3240')->firstOrFail();

        $this->actingAs($this->admin)
            ->put("/courses/{$course->id}", $this->validData(['code' => 'BUC3240', 'title' => 'Cloud Computing II']))
            ->assertSessionHas('success');
        $this->assertSame('Cloud Computing II', $course->fresh()->title);

        $this->actingAs($this->admin)->delete("/courses/{$course->id}")->assertRedirect('/courses');
        $this->assertModelMissing($course);
    }

    public function test_invalid_course_data_is_rejected(): void
    {
        $this->actingAs($this->admin)
            ->post('/courses', $this->validData(['code' => 'BUC3131', 'credit_units' => 0, 'semester' => 3, 'lecturer_id' => 999]))
            ->assertSessionHasErrors(['code', 'credit_units', 'semester', 'lecturer_id']);
    }

    public function test_course_with_registrations_cannot_be_deleted(): void
    {
        $course = Course::where('code', 'BUC3131')->first();

        $this->actingAs($this->admin)->delete("/courses/{$course->id}")->assertSessionHas('error');
        $this->assertModelExists($course);
    }

    public function test_lecturer_only_sees_assigned_courses_and_their_students(): void
    {
        $lecturer = User::where('email', 'grace.namutebi@sms.test')->first();

        $this->actingAs($lecturer)->get('/courses')->assertSee('BUC3131')->assertDontSee('BUC3133');

        $own = Course::where('code', 'BUC3131')->first();
        $this->actingAs($lecturer)->get("/courses/{$own->id}")->assertSee('Registered students')->assertSee('Aisha');

        $other = Course::where('code', 'BUC3133')->first();
        $this->actingAs($lecturer)->get("/courses/{$other->id}")->assertDontSee('Registered students');

        $this->actingAs($lecturer)->get('/courses/create')->assertForbidden();
    }

    public function test_student_can_browse_courses_but_not_manage_them(): void
    {
        $student = User::where('email', 'aisha.nakato@sms.test')->first();
        $course = Course::where('code', 'BUC3131')->first();

        $this->actingAs($student)->get('/courses')->assertOk()->assertSee('BUC3133')->assertDontSee('Add course');
        $this->actingAs($student)->get("/courses/{$course->id}")->assertOk()->assertDontSee('Registered students');
        $this->actingAs($student)->get("/courses/{$course->id}/edit")->assertForbidden();
        $this->actingAs($student)->delete("/courses/{$course->id}")->assertForbidden();
    }
}
