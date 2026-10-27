@can('view', $course)
    <a href="{{ route('courses.show', $course) }}" class="btn btn-sm btn-outline-secondary" title="View"><i class="bi bi-eye"></i></a>
@endcan
@can('update', $course)
    <a href="{{ route('courses.edit', $course) }}" class="btn btn-sm btn-outline-primary" title="Edit"><i class="bi bi-pencil"></i></a>
@endcan
@can('delete', $course)
    <form method="POST" action="{{ route('courses.destroy', $course) }}" class="d-inline"
          onsubmit="return confirm('Delete course {{ $course->code }}?')">
        @csrf
        @method('DELETE')
        <button type="submit" class="btn btn-sm btn-outline-danger" title="Delete"><i class="bi bi-trash"></i></button>
    </form>
@endcan
