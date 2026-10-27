<?php

namespace Tests\Feature;

use App\Models\Course;
use App\Models\CourseRegistration;
use App\Models\Student;
use App\Models\User;
use Database\Seeders\DatabaseSeeder;
use Illuminate\Foundation\Testing\RefreshDatabase;
use Tests\TestCase;

class AuthenticationTest extends TestCase
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

    public function test_guests_are_redirected_to_login(): void
    {
        $this->get('/dashboard')->assertRedirect('/login');
    }

    public function test_users_can_log_in_and_out(): void
    {
        $this->post('/login', ['email' => 'admin@sms.test', 'password' => 'password'])
            ->assertRedirect('/dashboard');
        $this->assertAuthenticated();

        $this->post('/logout')->assertRedirect('/login');
        $this->assertGuest();
    }

    public function test_wrong_password_is_rejected(): void
    {
        $this->post('/login', ['email' => 'admin@sms.test', 'password' => 'wrong'])
            ->assertSessionHasErrors('email');
        $this->assertGuest();
    }

    public function test_each_role_sees_its_own_dashboard(): void
    {
        $this->actingAs($this->user('admin@sms.test'))->get('/dashboard')->assertSee('Administrator Dashboard');
        $this->actingAs($this->user('grace.namutebi@sms.test'))->get('/dashboard')->assertSee('BUC3131')->assertDontSee('BUC3133');
        $this->actingAs($this->user('brian.mugisha@sms.test'))->get('/dashboard')->assertSee('My registered courses (2)')->assertSee('BUC3134');
    }

    public function test_students_cannot_access_other_students_records(): void
    {
        $aisha = $this->user('aisha.nakato@sms.test');
        $brian = Student::where('email', 'brian.mugisha@sms.test')->first();

        $this->assertTrue($aisha->can('view', $aisha->student));
        $this->assertFalse($aisha->can('view', $brian));
        $this->assertFalse($aisha->can('delete', CourseRegistration::where('student_id', $brian->id)->first()));
        $this->assertTrue($aisha->can('delete', $aisha->student->registrations()->first()));
    }

    public function test_lecturers_only_see_students_of_their_own_courses(): void
    {
        $grace = $this->user('grace.namutebi@sms.test');

        $this->assertTrue($grace->can('viewStudents', Course::where('code', 'BUC3131')->first()));
        $this->assertFalse($grace->can('viewStudents', Course::where('code', 'BUC3133')->first()));
        $this->assertFalse($grace->can('create', Student::class));
    }
}
