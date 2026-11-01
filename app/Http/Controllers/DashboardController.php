<?php

namespace App\Http\Controllers;

use App\Models\Course;
use App\Models\CourseRegistration;
use App\Models\Lecturer;
use App\Models\Student;
use Illuminate\Http\Request;
use Illuminate\View\View;

class DashboardController extends Controller
{
    /**
     * Show a dashboard that matches the logged-in user's role.
     */
    public function __invoke(Request $request): View
    {
        $user = $request->user();

        if ($user->hasRole('admin')) {
            return view('dashboard.admin', [
                'studentCount' => Student::count(),
                'courseCount' => Course::count(),
                'lecturerCount' => Lecturer::count(),
                'registrationCount' => CourseRegistration::count(),
                'latestRegistrations' => CourseRegistration::with(['student', 'course'])->latest()->take(5)->get(),
                'courses' => Course::withCount('students')->orderByDesc('students_count')->take(5)->get(),
            ]);
        }

        if ($user->hasRole('lecturer')) {
            $courses = $user->lecturer?->courses()->withCount('students')->orderBy('code')->get() ?? collect();

            return view('dashboard.lecturer', [
                'lecturer' => $user->lecturer,
                'courses' => $courses,
                'totalStudents' => $courses->sum('students_count'),
            ]);
        }

        $student = $user->student;
        $courses = $student?->courses()->with('lecturer.user')->orderBy('code')->get() ?? collect();

        return view('dashboard.student', [
            'student' => $student,
            'courses' => $courses,
            'totalCredits' => $courses->sum('credit_units'),
            // Courses this student has not registered for yet
            'available' => Course::with('lecturer.user')
                ->withCount('students')
                ->whereDoesntHave('students', fn ($query) => $query->whereKey($student?->id))
                ->orderBy('code')
                ->get(),
        ]);
    }
}
