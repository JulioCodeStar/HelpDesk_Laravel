<tr>
    <td class="text-muted fw-medium">#{{ $row->id }}</td>

    <td class="fw-medium">{{ $row->name }}</td>

    <td class="text-muted" style="font-size:.875rem;">
        {{ $row->description ? \Illuminate\Support\Str::limit($row->description, 80) : '—' }}
    </td>

    <td class="text-muted" style="font-size:.825rem;">
        {{ \Carbon\Carbon::parse($row->created_at)->format('d/m/Y') }}
    </td>

    <td>
        <div class="d-flex justify-content-end gap-2">
            <a href="{{ route('categories.edit', $row->id) }}"
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
