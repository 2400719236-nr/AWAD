@csrf

<div class="row g-3">
    <div class="col-md-3">
        <label for="code" class="form-label">Course code</label>
        <input type="text" id="code" name="code" value="{{ old('code', $course->code) }}"
               class="form-control text-uppercase @error('code') is-invalid @enderror" placeholder="BUC3131" required>
        @error('code') <div class="invalid-feedback">{{ $message }}</div> @enderror
    </div>
    <div class="col-md-9">
        <label for="title" class="form-label">Title</label>
        <input type="text" id="title" name="title" value="{{ old('title', $course->title) }}"
               class="form-control @error('title') is-invalid @enderror" required>
        @error('title') <div class="invalid-feedback">{{ $message }}</div> @enderror
    </div>

    <div class="col-12">
        <label for="description" class="form-label">Description</label>
        <textarea id="description" name="description" rows="3"
                  class="form-control @error('description') is-invalid @enderror">{{ old('description', $course->description) }}</textarea>
        @error('description') <div class="invalid-feedback">{{ $message }}</div> @enderror
    </div>

    <div class="col-md-3">
        <label for="credit_units" class="form-label">Credit units</label>
        <input type="number" id="credit_units" name="credit_units" min="1" max="6"
               value="{{ old('credit_units', $course->credit_units) }}"
               class="form-control @error('credit_units') is-invalid @enderror" required>
        @error('credit_units') <div class="invalid-feedback">{{ $message }}</div> @enderror
    </div>
    <div class="col-md-3">
        <label for="semester" class="form-label">Semester</label>
        <select id="semester" name="semester" class="form-select @error('semester') is-invalid @enderror" required>
            @foreach ([1, 2] as $semester)
                <option value="{{ $semester }}" @selected((int) old('semester', $course->semester ?? 1) === $semester)>Semester {{ $semester }}</option>
            @endforeach
        </select>
        @error('semester') <div class="invalid-feedback">{{ $message }}</div> @enderror
    </div>
    <div class="col-md-3">
        <label for="capacity" class="form-label">Capacity</label>
        <input type="number" id="capacity" name="capacity" min="1" max="500"
               value="{{ old('capacity', $course->capacity ?? 50) }}"
               class="form-control @error('capacity') is-invalid @enderror" required>
        @error('capacity') <div class="invalid-feedback">{{ $message }}</div> @enderror
    </div>
    <div class="col-md-3">
        <label for="lecturer_id" class="form-label">Lecturer</label>
        <select id="lecturer_id" name="lecturer_id" class="form-select @error('lecturer_id') is-invalid @enderror">
            <option value="">Not assigned</option>
            @foreach ($lecturers as $lecturer)
                <option value="{{ $lecturer->id }}" @selected((int) old('lecturer_id', $course->lecturer_id) === $lecturer->id)>
                    {{ $lecturer->name }}
                </option>
            @endforeach
        </select>
        @error('lecturer_id') <div class="invalid-feedback">{{ $message }}</div> @enderror
    </div>
</div>

<div class="mt-4 d-flex gap-2">
    <button type="submit" class="btn btn-primary"><i class="bi bi-save"></i> Save</button>
    <a href="{{ route('courses.index') }}" class="btn btn-outline-secondary">Cancel</a>
</div>
