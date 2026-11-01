{{-- Usage: @include('partials.stat-card', ['label' => ..., 'value' => ..., 'icon' => ..., 'color' => ..., 'url' => optional]) --}}
<div class="card stat-card h-100">
    <div class="card-body d-flex align-items-center gap-3">
        <span class="stat-icon bg-soft-{{ $color }}"><i class="bi bi-{{ $icon }}"></i></span>
        <div class="flex-grow-1">
            <div class="text-muted small">{{ $label }}</div>
            <div class="stat-value">{{ $value }}</div>
        </div>
        @isset($url)
            <a href="{{ $url }}" class="stretched-link text-muted"><i class="bi bi-arrow-right"></i></a>
        @endisset
    </div>
</div>
