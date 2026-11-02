<?php

namespace Database\Seeders;

use App\Models\Student;
use App\Models\User;
use Illuminate\Database\Seeder;

class StudentSeeder extends Seeder
{
    public function run(): void
    {
        $students = [
            ['registration_number' => '24/U/BBC/0001', 'first_name' => 'Aisha', 'last_name' => 'Nakato', 'email' => 'aisha.nakato@sms.test', 'gender' => 'female', 'date_of_birth' => '2004-03-14', 'phone' => '0701000001', 'year_of_study' => 3],
            ['registration_number' => '24/U/BBC/0002', 'first_name' => 'Brian', 'last_name' => 'Mugisha', 'email' => 'brian.mugisha@sms.test', 'gender' => 'male', 'date_of_birth' => '2003-07-22', 'phone' => '0701000002', 'year_of_study' => 3],
            ['registration_number' => '24/U/BBC/0003', 'first_name' => 'Christine', 'last_name' => 'Achieng', 'email' => 'christine.achieng@sms.test', 'gender' => 'female', 'date_of_birth' => '2004-11-02', 'phone' => '0701000003', 'year_of_study' => 3],
            ['registration_number' => '24/U/BBC/0004', 'first_name' => 'David', 'last_name' => 'Ssempala', 'email' => 'david.ssempala@sms.test', 'gender' => 'male', 'date_of_birth' => '2003-01-30', 'phone' => '0701000004', 'year_of_study' => 3],
            ['registration_number' => '25/U/BBC/0005', 'first_name' => 'Esther', 'last_name' => 'Nabirye', 'email' => 'esther.nabirye@sms.test', 'gender' => 'female', 'date_of_birth' => '2005-05-18', 'phone' => '0701000005', 'year_of_study' => 2],
            ['registration_number' => '25/U/BBC/0006', 'first_name' => 'Frank', 'last_name' => 'Tumusiime', 'email' => 'frank.tumusiime@sms.test', 'gender' => 'male', 'date_of_birth' => '2005-09-09', 'phone' => '0701000006', 'year_of_study' => 2],
        ];

        foreach ($students as $data) {
            // Link the record to a login account when one exists with the same email
            $data['user_id'] = User::where('email', $data['email'])->value('id');
            $data['programme'] = 'Bachelor of Business Computing';

            Student::updateOrCreate(
                ['registration_number' => $data['registration_number']],
                $data
            );
        }
    }
}
