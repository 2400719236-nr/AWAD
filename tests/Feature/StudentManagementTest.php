<?php

namespace Tests\Feature;

use App\Models\Student;
use App\Models\User;
use Database\Seeders\DatabaseSeeder;
use Illuminate\Foundation\Testing\RefreshDatabase;
use Tests\TestCase;

class StudentManagementTest extends TestCase
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
            'registration_number' => '26/U/BBC/0100',
            'first_name' => 'Grace',
            'last_name' => 'Atim',
            'email' => 'grace.atim@sms.test',
            'gender' => 'female',
            'date_of_birth' => '2005-02-10',
            'phone' => '0772000100',
            'programme' => 'Bachelor of Business Computing',
            'year_of_study' => 1,
        ], $overrides);
    }

    public function test_admin_can_list_and_search_students(): void
    {
        $this->actingAs($this->admin)->get('/students')->assertOk()->assertSee('24/U/BBC/0001');
        $this->actingAs($this->admin)->get('/students?search=Mugisha')->assertSee('Brian')->assertDontSee('Christine');
    }

    public function test_admin_can_create_a_student_with_a_login_account(): void
    {
        $this->actingAs($this->admin)
            ->post('/students', $this->validData(['password' => 'secret123', 'password_confirmation' => 'secret123']))
            ->assertRedirect()
            ->assertSessionHas('success');

        $student = Student::where('email', 'grace.atim@sms.test')->first();
        $this->assertNotNull($student);
        $this->assertTrue($student->user->hasRole('student'));
    }

    public function test_invalid_student_data_is_rejected(): void
    {
        $this->actingAs($this->admin)
            ->post('/students', $this->validData(['email' => 'not-an-email', 'registration_number' => '24/U/BBC/0001', 'year_of_study' => 9]))
            ->assertSessionHasErrors(['email', 'registration_number', 'year_of_study']);
    }

    public function test_admin_can_update_and_delete_a_student(): void
    {
        $student = Student::where('registration_number', '24/U/BBC/0003')->first();

        $this->actingAs($this->admin)
            ->put("/students/{$student->id}", $this->validData(['registration_number' => $student->registration_number, 'email' => $student->email, 'first_name' => 'Christina']))
            ->assertSessionHas('success');
        $this->assertSame('Christina', $student->fresh()->first_name);

        $this->actingAs($this->admin)->delete("/students/{$student->id}")->assertRedirect('/students');
        $this->assertModelMissing($student);
        $this->assertDatabaseMissing('course_registrations', ['student_id' => $student->id]);
    }

    public function test_students_and_lecturers_cannot_manage_students(): void
    {
        $studentUser = User::where('email', 'aisha.nakato@sms.test')->first();
        $other = Student::where('registration_number', '24/U/BBC/0002')->first();

        $this->actingAs($studentUser)->get('/students')->assertForbidden();
        $this->actingAs($studentUser)->get('/students/create')->assertForbidden();
        $this->actingAs($studentUser)->get("/students/{$other->id}")->assertForbidden();
        $this->actingAs($studentUser)->get("/students/{$studentUser->student->id}")->assertOk();
        $this->actingAs($studentUser)->delete("/students/{$other->id}")->assertForbidden();

        $lecturer = User::where('email', 'grace.namutebi@sms.test')->first();
        $this->actingAs($lecturer)->post('/students', $this->validData())->assertForbidden();
    }
}
