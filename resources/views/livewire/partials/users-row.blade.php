@php
    $initials = '';
    foreach (explode(' ', $row->name ?? '') as $part) {
        if ($part !== '') $initials .= strtoupper($part[0]);
        if (strlen($initials) >= 2) break;
    }

    $roleClass = match($row->role) {
        'admin'  => 'bg-danger-subtle text-danger',
        'agente' => 'bg-primary-subtle text-primary',
        default  => 'bg-secondary-subtle text-secondary',
    };
@endphp

<tr>
    <td>
        <div class="d-flex align-items-center gap-2">
            <span class="lw-avatar">{{ $initials ?: '?' }}</span>
            <span class="fw-medium">{{ $row->name }}</span>
        </div>
    </td>

    <td class="text-muted" style="font-size:.875rem;">{{ $row->email }}</td>

    <td>{{ $row->department_name ?? '—' }}</td>

    <td>
        <span class="badge {{ $roleClass }}">{{ ucfirst($row->role) }}</span>
    </td>

    <td class="text-muted" style="font-size:.825rem;">
        {{ \Carbon\Carbon::parse($row->created_at)->format('d/m/Y') }}
    </td>

    <td>
        <div class="d-flex justify-content-end gap-2">
            <a href="{{ route('users.edit', $row->id) }}"
               class="btn btn-sm btn-subtle-primary waves-effect">
                <i class="fi fi-rr-edit"></i>
            </a>
            <button type="button"
                    class="btn btn-sm btn-subtle-danger waves-effect btn-delete"
                    data-id="{{ $row->id }}"
                    data-name="{{ $row->name }}">
                <i class="fi fi-rr-trash"></i>
            </button>
        </div>
    </td>
</tr>
