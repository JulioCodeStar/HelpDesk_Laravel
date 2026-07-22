{{-- Parcial compartido por create.blade.php y edit.blade.php --}}
@php $department = $department ?? null; @endphp


{{-- Nombre --}}
<div class="col-12">
    <label for="name" class="form-label">
        Nombre <span class="text-danger">*</span>
    </label>
    <input type="text"
           class="form-control @error('name') is-invalid @enderror"
           id="name"
           name="name"
           value="{{ old('name', $department->name ?? '') }}"
           placeholder="Ej. Soporte Técnico"
           maxlength="255"
           autofocus>
    @error('name')
    <div class="invalid-feedback">{{ $message }}</div>
    @enderror
</div>


{{-- Botones --}}
<div class="col-12 d-flex justify-content-end gap-2 pt-2">
    <a href="{{ route('departments.index') }}" class="btn btn-outline-light waves-effect">
        Cancelar
    </a>
    <button type="submit" class="btn btn-primary waves-effect waves-light" id="submitBtn">
            <span class="btn-text">
                <i class="fi fi-rr-disk me-1"></i>
                {{ $department ? 'Actualizar Departamento' : 'Guardar Departamento' }}
            </span>
        <span class="btn-loader d-none">
                <span class="spinner-border spinner-border-sm me-1" role="status" aria-hidden="true"></span>
                Guardando...
            </span>
    </button>
</div>

@push('scripts')
    <script>
        const form = document.getElementById('departmentForm');
        const btn  = document.getElementById('submitBtn');

        // Loader en el botón al enviar
        form.addEventListener('submit', function () {
            btn.disabled = true;
            btn.querySelector('.btn-text').classList.add('d-none');
            btn.querySelector('.btn-loader').classList.remove('d-none');
        });
    </script>
@endpush

