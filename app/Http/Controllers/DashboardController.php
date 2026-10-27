<?php

namespace App\Http\Controllers;

use App\Models\Course;
use App\Models\CourseRegistration;
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
                'registrationCount' => CourseRegistration::count(),
                'latestRegistrations' => CourseRegistration::with(['student', 'course'])->latest()->take(5)->get(),
            ]);
        }

        if ($user->hasRole('lecturer')) {
            return view('dashboard.lecturer', [
                'courses' => $user->lecturer?->courses()->withCount('students')->orderBy('code')->get() ?? collect(),
            ]);
        }

        $student = $user->student;

        return view('dashboard.student', [
            'student' => $student,
            'courses' => $student?->courses()->orderBy('code')->get() ?? collect(),
            // Courses this student has not registered for yet
            'available' => Course::with('lecturer.user')
                ->withCount('students')
                ->whereDoesntHave('students', fn ($query) => $query->whereKey($student?->id))
                ->orderBy('code')
                ->get(),
        ]);
    }
}
