<?php

namespace App\Http\Controllers;

use App\Http\Requests\StudentRequest;
use App\Models\Student;
use App\Models\User;
use Illuminate\Http\RedirectResponse;
use Illuminate\Http\Request;
use Illuminate\Support\Facades\DB;
use Illuminate\Support\Facades\Gate;
use Illuminate\View\View;

class StudentController extends Controller
{
    public function index(Request $request): View
    {
        Gate::authorize('viewAny', Student::class);

        $search = $request->string('search')->trim()->toString();

        $students = Student::withCount('courses')
            ->when($search, fn ($query) => $query->where(function ($q) use ($search) {
                $q->where('first_name', 'like', "%{$search}%")
                    ->orWhere('last_name', 'like', "%{$search}%")
                    ->orWhere('registration_number', 'like', "%{$search}%")
                    ->orWhere('email', 'like', "%{$search}%");
            }))
            ->orderBy('registration_number')
            ->paginate(10)
            ->withQueryString();

        return view('students.index', compact('students', 'search'));
    }

    public function create(): View
    {
        Gate::authorize('create', Student::class);

        return view('students.create', ['student' => new Student]);
    }

    public function store(StudentRequest $request): RedirectResponse
    {
        $student = DB::transaction(function () use ($request) {
            $student = Student::create($request->safe()->except(['password', 'password_confirmation']));
            $this->syncLoginAccount($student, $request->input('password'));

            return $student;
        });

        return redirect()->route('students.show', $student)
            ->with('success', "Student {$student->full_name} was created successfully.");
    }

    public function show(Student $student): View
    {
        Gate::authorize('view', $student);

        $student->load(['courses.lecturer.user', 'user']);

        return view('students.show', compact('student'));
    }

    public function edit(Student $student): View
    {
        Gate::authorize('update', $student);

        return view('students.edit', compact('student'));
    }

    public function update(StudentRequest $request, Student $student): RedirectResponse
    {
        DB::transaction(function () use ($request, $student) {
            $student->update($request->safe()->except(['password', 'password_confirmation']));
            $this->syncLoginAccount($student, $request->input('password'));
        });

        return redirect()->route('students.show', $student)
            ->with('success', "Student {$student->full_name} was updated successfully.");
    }

    public function destroy(Student $student): RedirectResponse
    {
        Gate::authorize('delete', $student);

        DB::transaction(function () use ($student) {
            // Registrations are removed by the cascading foreign key
            $student->user?->delete();
            $student->delete();
        });

        return redirect()->route('students.index')
            ->with('success', "Student {$student->full_name} was deleted.");
    }

    /**
     * Keep the student's login account in step with the student record.
     * A new password creates the account if it does not exist yet.
     */
    private function syncLoginAccount(Student $student, ?string $password): void
    {
        $user = $student->user;

        if (! $user && ! $password) {
            return;
        }

        $user ??= new User;
        $user->name = $student->full_name;
        $user->email = $student->email;
        if ($password) {
            $user->password = $password;
        }
        $user->save();
        $user->syncRoles('student');

        $student->user()->associate($user)->save();
    }
}
