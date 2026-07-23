<tr>
    <td class="text-muted fw-medium">#{{ $row->id }}</td>

    <td class="fw-medium">{{ \Illuminate\Support\Str::limit($row->title, 70) }}</td>

    <td class="text-muted" style="font-size:.875rem;">
        {{ \Illuminate\Support\Str::limit(strip_tags($row->description), 80) }}
    </td>

    <td class="text-muted" style="font-size:.825rem;">
        {{ \Carbon\Carbon::parse($row->created_at)->format('d/m/Y') }}
    </td>

    <td>
        <div class="d-flex justify-content-end gap-2">
            <a href="{{ route('faqs.edit', $row->id) }}"
               class="btn btn-sm btn-subtle-primary waves-effect">
                <i class="fi fi-rr-edit"></i>
            </a>
            <button type="button"
                    class="btn btn-sm btn-subtle-danger waves-effect btn-delete"
                    data-id="{{ $row->id }}"
                    data-name="{{ \Illuminate\Support\Str::limit($row->title, 50) }}">
                <i class="fi fi-rr-trash"></i>
            </button>
        </div>
    </td>
</tr>
