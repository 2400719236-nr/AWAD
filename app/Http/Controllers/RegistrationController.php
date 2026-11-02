<?php

namespace App\Http\Controllers;

use App\Http\Requests\RegistrationRequest;
use App\Models\Course;
use App\Models\CourseRegistration;
use App\Models\Student;
use Illuminate\Database\UniqueConstraintViolationException;
use Illuminate\Http\RedirectResponse;
use Illuminate\Http\Request;
use Illuminate\Support\Facades\Gate;
use Illuminate\View\View;

class RegistrationController extends Controller
{
    /**
     * Admins see every registration; students only see their own.
     */
    public function index(Request $request): View
    {
        Gate::authorize('viewAny', CourseRegistration::class);

        $user = $request->user();
        $seesAll = $user->can('view all registrations');

        $registrations = CourseRegistration::with(['student', 'course.lecturer.user'])
            ->when(! $seesAll, fn ($query) => $query->where('student_id', $user->student?->id))
            ->when($seesAll && $request->filled('course_id'), fn ($query) => $query->where('course_id', $request->integer('course_id')))
            ->latest()
            ->paginate(15)
            ->withQueryString();

        return view('registrations.index', [
            'registrations' => $registrations,
            'seesAll' => $seesAll,
            'courses' => $seesAll ? Course::orderBy('code')->get() : collect(),
        ]);
    }

    public function create(Request $request): View
    {
        Gate::authorize('create', CourseRegistration::class);

        $user = $request->user();
        $isAdmin = $user->can('manage registrations');

        // Students only get offered courses they have not registered for yet
        $courses = Course::withCount('students')
            ->when(! $isAdmin, fn ($query) => $query->whereDoesntHave('students', fn ($q) => $q->whereKey($user->student?->id)))
            ->orderBy('code')
            ->get();

        return view('registrations.create', [
            'courses' => $courses,
            'students' => $isAdmin ? Student::orderBy('registration_number')->get() : collect(),
            'isAdmin' => $isAdmin,
            'selectedCourse' => $request->integer('course_id') ?: null,
        ]);
    }

    public function store(RegistrationRequest $request): RedirectResponse
    {
        try {
            $registration = CourseRegistration::create([
                ...$request->validated(),
                'academic_year' => CourseRegistration::CURRENT_ACADEMIC_YEAR,
            ]);
        } catch (UniqueConstraintViolationException) {
            // Last line of defence if two identical requests arrive at the same time
            return back()->with('error', 'This student is already registered for that course.');
        }

        $registration->load(['student', 'course']);
        $message = $request->user()->can('manage registrations')
          ? "Success! {$registration->student->full_name} has been successfully registered for {$registration->course->code}."
            : "Great job! You are now successfully enrolled in {$registration->course->code}: {$registration->course->title}.";
        return redirect()->route('registrations.index')->with('success', $message);

       
    }

    public function destroy(CourseRegistration $registration): RedirectResponse
    {
        Gate::authorize('delete', $registration);

        $registration->load(['student', 'course']);
        $registration->delete();

        return back()->with('success', "Registration of {$registration->student->full_name} for {$registration->course->code} was removed.");
    }
}
