<?php

namespace App\Http\Controllers;

use App\Http\Requests\CourseRequest;
use App\Models\Course;
use App\Models\Lecturer;
use Illuminate\Http\RedirectResponse;
use Illuminate\Http\Request;
use Illuminate\Support\Facades\Gate;
use Illuminate\View\View;

class CourseController extends Controller
{
    /**
     * Admins and students see every course; lecturers see only the courses assigned to them.
     */
    public function index(Request $request): View
    {
        Gate::authorize('viewAny', Course::class);

        $user = $request->user();
        $search = $request->string('search')->trim()->toString();

        $courses = Course::with('lecturer.user')
            ->withCount('students')
            ->when($user->hasRole('lecturer'), fn ($query) => $query->where('lecturer_id', $user->lecturer?->id))
            ->when($search, fn ($query) => $query->where(function ($q) use ($search) {
                $q->where('code', 'like', "%{$search}%")->orWhere('title', 'like', "%{$search}%");
            }))
            ->orderBy('code')
            ->paginate(10)
            ->withQueryString();

        return view('courses.index', compact('courses', 'search'));
    }

    public function create(): View
    {
        Gate::authorize('create', Course::class);

        return view('courses.create', ['course' => new Course, 'lecturers' => $this->lecturers()]);
    }

    public function store(CourseRequest $request): RedirectResponse
    {
        $course = Course::create($request->validated());

        return redirect()->route('courses.show', $course)
            ->with('success', "Course {$course->code} was created successfully.");
    }

    public function show(Request $request, Course $course): View
    {
        Gate::authorize('view', $course);

        $course->load('lecturer.user');

        // The class list is only loaded for users allowed to see it
        $students = $request->user()->can('viewStudents', $course)
            ? $course->students()->orderBy('last_name')->get()
            : null;

        return view('courses.show', compact('course', 'students'));
    }

    public function edit(Course $course): View
    {
        Gate::authorize('update', $course);

        return view('courses.edit', ['course' => $course, 'lecturers' => $this->lecturers()]);
    }

    public function update(CourseRequest $request, Course $course): RedirectResponse
    {
        $course->update($request->validated());

        return redirect()->route('courses.show', $course)
            ->with('success', "Course {$course->code} was updated successfully.");
    }

    public function destroy(Course $course): RedirectResponse
    {
        Gate::authorize('delete', $course);

        if ($course->registrations()->exists()) {
            return back()->with('error', "Course {$course->code} has registered students and cannot be deleted. Remove the registrations first.");
        }

        $course->delete();

        return redirect()->route('courses.index')
            ->with('success', "Course {$course->code} was deleted.");
    }

    private function lecturers()
    {
        return Lecturer::with('user')->get()->sortBy('name');
    }
}
