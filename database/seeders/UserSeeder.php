<?php

namespace Database\Seeders;

use App\Models\Lecturer;
use App\Models\User;
use Illuminate\Database\Seeder;
use Illuminate\Support\Facades\Hash;

class UserSeeder extends Seeder
{
    /**
     * Sample login accounts. Every account uses the password "password".
     */
    public function run(): void
    {
        $admin = $this->account('System Administrator', 'admin@sms.test');
        $admin->syncRoles('admin');

        $lecturers = [
            ['name' => 'Dr. Grace Namutebi', 'email' => 'grace.namutebi@sms.test', 'staff_number' => 'STF-001', 'department' => 'Computing'],
            ['name' => 'Mr. Joseph Okello', 'email' => 'joseph.okello@sms.test', 'staff_number' => 'STF-002', 'department' => 'Business Information Systems'],
        ];

        foreach ($lecturers as $data) {
            $user = $this->account($data['name'], $data['email']);
            $user->syncRoles('lecturer');

            Lecturer::updateOrCreate(
                ['staff_number' => $data['staff_number']],
                ['user_id' => $user->id, 'department' => $data['department']]
            );
        }

        // Student accounts are linked to student records in StudentSeeder
        $this->account('Aisha Nakato', 'aisha.nakato@sms.test')->syncRoles('student');
        $this->account('Brian Mugisha', 'brian.mugisha@sms.test')->syncRoles('student');
    }

    /**
     * Create the account once; later runs update it instead of duplicating it.
     */
    private function account(string $name, string $email): User
    {
        return User::updateOrCreate(
            ['email' => $email],
            ['name' => $name, 'password' => Hash::make('password'), 'email_verified_at' => now()]
        );
    }
}
