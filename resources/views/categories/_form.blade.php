{{-- Parcial compartido por create.blade.php y edit.blade.php --}}
@php $category = $category ?? null; @endphp


{{-- Nombre --}}
<div class="col-12">
    <label for="name" class="form-label">
        Nombre <span class="text-danger">*</span>
    </label>
    <input type="text"
           class="form-control @error('name') is-invalid @enderror"
           id="name"
           name="name"
           value="{{ old('name', $category->name ?? '') }}"
           placeholder="Ej. Hardware"
           maxlength="255"
           autofocus>
    @error('name')
    <div class="invalid-feedback">{{ $message }}</div>
    @enderror
</div>

{{-- Descripción --}}
<div class="col-12">
    <label for="description" class="form-label">Descripción</label>
    <textarea class="form-control @error('description') is-invalid @enderror"
              id="description"
              name="description"
              rows="4"
              maxlength="1000"
              placeholder="Describe brevemente esta categoría...">{{ old('description', $category->description ?? '') }}</textarea>
    @error('description')
    <div class="invalid-feedback">{{ $message }}</div>
    @enderror
    <div class="form-text text-end">
        <span id="descCount">0</span>/1000
    </div>
</div>

{{-- Botones --}}
<div class="col-12 d-flex justify-content-end gap-2 pt-2">
    <a href="{{ route('categories.index') }}" class="btn btn-outline-light waves-effect">
        Cancelar
    </a>
    <button type="submit" class="btn btn-primary waves-effect waves-light" id="submitBtn">
            <span class="btn-text">
                <i class="fi fi-rr-disk me-1"></i>
                {{ $category ? 'Actualizar categoría' : 'Guardar categoría' }}
            </span>
        <span class="btn-loader d-none">
                <span class="spinner-border spinner-border-sm me-1" role="status" aria-hidden="true"></span>
                Guardando...
            </span>
    </button>
</div>

@push('scripts')
    <script>
        const form = document.getElementById('categoryForm');
        const btn  = document.getElementById('submitBtn');
        const desc = document.getElementById('description');
        const cnt  = document.getElementById('descCount');

        // Contador de caracteres
        const upd = () => cnt.textContent = desc.value.length;
        desc.addEventListener('input', upd);
        upd();

        // Loader en el botón al enviar
        form.addEventListener('submit', function () {
            btn.disabled = true;
            btn.querySelector('.btn-text').classList.add('d-none');
            btn.querySelector('.btn-loader').classList.remove('d-none');
        });
    </script>
@endpush
