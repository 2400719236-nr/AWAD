@csrf

<div class="row g-3">
    <div class="col-md-4">
        <label for="registration_number" class="form-label">Registration number</label>
        <input type="text" id="registration_number" name="registration_number"
               value="{{ old('registration_number', $student->registration_number) }}"
               class="form-control @error('registration_number') is-invalid @enderror" placeholder="24/U/BBC/0001" required>
        @error('registration_number') <div class="invalid-feedback">{{ $message }}</div> @enderror
    </div>
    <div class="col-md-4">
        <label for="first_name" class="form-label">First name</label>
        <input type="text" id="first_name" name="first_name" value="{{ old('first_name', $student->first_name) }}"
               class="form-control @error('first_name') is-invalid @enderror" required>
        @error('first_name') <div class="invalid-feedback">{{ $message }}</div> @enderror
    </div>
    <div class="col-md-4">
        <label for="last_name" class="form-label">Last name</label>
        <input type="text" id="last_name" name="last_name" value="{{ old('last_name', $student->last_name) }}"
               class="form-control @error('last_name') is-invalid @enderror" required>
        @error('last_name') <div class="invalid-feedback">{{ $message }}</div> @enderror
    </div>

    <div class="col-md-6">
        <label for="email" class="form-label">Email</label>
        <input type="email" id="email" name="email" value="{{ old('email', $student->email) }}"
               class="form-control @error('email') is-invalid @enderror" required>
        @error('email') <div class="invalid-feedback">{{ $message }}</div> @enderror
    </div>
    <div class="col-md-3">
        <label for="phone" class="form-label">Phone</label>
        <input type="text" id="phone" name="phone" value="{{ old('phone', $student->phone) }}"
               class="form-control @error('phone') is-invalid @enderror">
        @error('phone') <div class="invalid-feedback">{{ $message }}</div> @enderror
    </div>
    <div class="col-md-3">
        <label for="gender" class="form-label">Gender</label>
        <select id="gender" name="gender" class="form-select @error('gender') is-invalid @enderror" required>
            <option value="">Choose...</option>
            @foreach (['male' => 'Male', 'female' => 'Female'] as $value => $label)
                <option value="{{ $value }}" @selected(old('gender', $student->gender) === $value)>{{ $label }}</option>
            @endforeach
        </select>
        @error('gender') <div class="invalid-feedback">{{ $message }}</div> @enderror
    </div>

    <div class="col-md-3">
        <label for="date_of_birth" class="form-label">Date of birth</label>
        <input type="date" id="date_of_birth" name="date_of_birth"
               value="{{ old('date_of_birth', $student->date_of_birth?->format('Y-m-d')) }}"
               class="form-control @error('date_of_birth') is-invalid @enderror">
        @error('date_of_birth') <div class="invalid-feedback">{{ $message }}</div> @enderror
    </div>
    <div class="col-md-6">
        <label for="programme" class="form-label">Programme</label>
        <input type="text" id="programme" name="programme"
               value="{{ old('programme', $student->programme ?? 'Bachelor of Business Computing') }}"
               class="form-control @error('programme') is-invalid @enderror" required>
        @error('programme') <div class="invalid-feedback">{{ $message }}</div> @enderror
    </div>
    <div class="col-md-3">
        <label for="year_of_study" class="form-label">Year of study</label>
        <select id="year_of_study" name="year_of_study" class="form-select @error('year_of_study') is-invalid @enderror" required>
            @foreach (range(1, 5) as $year)
                <option value="{{ $year }}" @selected((int) old('year_of_study', $student->year_of_study ?? 1) === $year)>Year {{ $year }}</option>
            @endforeach
        </select>
        @error('year_of_study') <div class="invalid-feedback">{{ $message }}</div> @enderror
    </div>
</div>

<hr class="my-4">

<h2 class="h6">Login account</h2>
<p class="text-muted small">
    @if ($student->user_id)
        This student can log in with their email. Enter a new password only to reset it.
    @else
        Optional: set a password to give this student a login account with the student role.
    @endif
</p>
<div class="row g-3">
    <div class="col-md-6">
        <label for="password" class="form-label">Password</label>
        <input type="password" id="password" name="password" autocomplete="new-password"
               class="form-control @error('password') is-invalid @enderror">
        @error('password') <div class="invalid-feedback">{{ $message }}</div> @enderror
    </div>
    <div class="col-md-6">
        <label for="password_confirmation" class="form-label">Confirm password</label>
        <input type="password" id="password_confirmation" name="password_confirmation" autocomplete="new-password" class="form-control">
    </div>
</div>

<div class="mt-4 d-flex gap-2">
    <button type="submit" class="btn btn-primary"><i class="bi bi-save"></i> Save</button>
    <a href="{{ route('students.index') }}" class="btn btn-outline-secondary">Cancel</a>
</div>
