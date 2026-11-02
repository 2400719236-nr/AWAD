<?php

namespace Tests\Feature;

use App\Models\Course;
use App\Models\CourseRegistration;
use App\Models\Student;
use App\Models\User;
use Database\Seeders\DatabaseSeeder;
use Illuminate\Foundation\Testing\RefreshDatabase;
use Spatie\Permission\Models\Permission;
use Spatie\Permission\Models\Role;
use Tests\TestCase;

class SeederTest extends TestCase
{
    use RefreshDatabase;

    public function test_seeders_create_sample_data_and_roles(): void
    {
        $this->seed(DatabaseSeeder::class);

        $this->assertGreaterThanOrEqual(5, Student::count());
        $this->assertGreaterThanOrEqual(4, Course::count());
        $this->assertGreaterThan(0, CourseRegistration::count());

        $this->assertTrue(User::where('email', 'admin@sms.test')->first()->hasRole('admin'));
        $this->assertTrue(User::where('email', 'grace.namutebi@sms.test')->first()->hasRole('lecturer'));
        $this->assertTrue(User::where('email', 'aisha.nakato@sms.test')->first()->hasRole('student'));
        $this->assertTrue(User::where('email', 'aisha.nakato@sms.test')->first()->student->exists);
    }

    public function test_running_seeders_twice_does_not_duplicate_records(): void
    {
        $this->seed(DatabaseSeeder::class);
        $counts = [User::count(), Student::count(), Course::count(), CourseRegistration::count(), Role::count(), Permission::count()];

        $this->seed(DatabaseSeeder::class);

        $this->assertSame($counts, [User::count(), Student::count(), Course::count(), CourseRegistration::count(), Role::count(), Permission::count()]);
    }
}
