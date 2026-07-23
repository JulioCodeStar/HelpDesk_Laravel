@php
    // Iniciales del solicitante
    $initials = '';
    foreach (explode(' ', $row->creator_name ?? '') as $part) {
        if ($part !== '') $initials .= strtoupper($part[0]);
        if (strlen($initials) >= 2) break;
    }

    // Badge de prioridad
    $prioClass = match ($row->priority_name) {
        'Alta'  => 'bg-danger-subtle text-danger',
        'Media' => 'bg-warning-subtle text-warning',
        default => 'bg-secondary-subtle text-secondary',
    };

    // Color del estado
    $statusColor = $row->status_color ?? '#6c757d';
@endphp

<tr>
    <td class="text-muted fw-medium">#{{ $row->id }}</td>

    <td class="fw-medium">{{ \Illuminate\Support\Str::limit($row->subject, 60) }}</td>

    <td>
        <div class="d-flex align-items-center gap-2">
            <span class="lw-avatar">{{ $initials ?: '?' }}</span>
            <span>{{ $row->creator_name ?? '—' }}</span>
        </div>
    </td>

    <td>
        @if ($row->agent_name)
            {{ $row->agent_name }}
        @else
            <span class="badge bg-warning-subtle text-warning">Sin asignar</span>
        @endif
    </td>

    <td>{{ $row->category_name ?? '—' }}</td>

    <td>
        <span class="badge {{ $prioClass }}">
            {{ $row->priority_name ?? '—' }}
        </span>
    </td>

    <td>
        <span class="badge" style="background:{{ $statusColor }}1a; color:{{ $statusColor }};">
            {{ $row->status_name ?? '—' }}
        </span>
    </td>

    <td class="text-muted" style="font-size:.825rem;">
        {{ \Carbon\Carbon::parse($row->created_at)->format('d/m/Y') }}
    </td>

    <td>
        <div class="d-flex justify-content-end gap-2">
            <a href="#" class="btn btn-sm btn-subtle-primary waves-effect">
                <i class="fi fi-rr-eye"></i>
            </a>
        </div>
    </td>
</tr>
