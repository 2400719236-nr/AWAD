<?php

namespace Database\Seeders;

use Illuminate\Database\Seeder;
use Spatie\Permission\Models\Permission;
use Spatie\Permission\Models\Role;
use Spatie\Permission\PermissionRegistrar;

class RolesAndPermissionsSeeder extends Seeder
{
    /**
     * Permissions grouped by area of the system.
     */
    public const PERMISSIONS = [
        // Students
        'view students',
        'create students',
        'edit students',
        'delete students',
        // Courses
        'view courses',
        'create courses',
        'edit courses',
        'delete courses',
        'view assigned courses',
        // Registrations
        'view all registrations',
        'view own registrations',
        'register for courses',
        'manage registrations',
        // Users
        'manage users',
    ];

    public function run(): void
    {
        // Clear cached roles and permissions before changing them
        app()[PermissionRegistrar::class]->forgetCachedPermissions();

        // firstOrCreate keeps the seeder safe to run more than once
        foreach (self::PERMISSIONS as $permission) {
            Permission::firstOrCreate(['name' => $permission, 'guard_name' => 'web']);
        }

        $admin = Role::firstOrCreate(['name' => 'admin', 'guard_name' => 'web']);
        $lecturer = Role::firstOrCreate(['name' => 'lecturer', 'guard_name' => 'web']);
        $student = Role::firstOrCreate(['name' => 'student', 'guard_name' => 'web']);

        // Administrator: manages students, courses, registrations and user access
        $admin->syncPermissions(Permission::all());

        // Lecturer: views assigned courses and their registered students
        $lecturer->syncPermissions([
            'view courses',
            'view assigned courses',
        ]);

        // Student: views available courses and their own registrations
        $student->syncPermissions([
            'view courses',
            'view own registrations',
            'register for courses',
        ]);
    }
}
