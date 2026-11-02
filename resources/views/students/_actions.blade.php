@can('view', $student)
    <a href="{{ route('students.show', $student) }}" class="btn btn-sm btn-outline-secondary" title="View"><i class="bi bi-eye"></i></a>
@endcan
@can('update', $student)
    <a href="{{ route('students.edit', $student) }}" class="btn btn-sm btn-outline-primary" title="Edit"><i class="bi bi-pencil"></i></a>
@endcan
@can('delete', $student)
    <form method="POST" action="{{ route('students.destroy', $student) }}" class="d-inline"
          onsubmit="return confirm('Delete {{ $student->full_name }} and all their registrations?')">
        @csrf
        @method('DELETE')
        <button type="submit" class="btn btn-sm btn-outline-danger" title="Delete"><i class="bi bi-trash"></i></button>
    </form>
@endcan
