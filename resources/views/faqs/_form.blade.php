{{-- Parcial compartido por create.blade.php y edit.blade.php --}}
@php $faq = $faq ?? null; @endphp


{{-- Pregunta --}}
<div class="col-12">
    <label for="title" class="form-label">
        Pregunta <span class="text-danger">*</span>
    </label>
    <input type="text"
           class="form-control @error('title') is-invalid @enderror"
           id="title"
           name="title"
           value="{{ old('title', $faq->title ?? '') }}"
           placeholder="Ej. ¿Cómo restablezco mi contraseña?"
           maxlength="255"
           autofocus>
    @error('title')
    <div class="invalid-feedback">{{ $message }}</div>
    @enderror
</div>

{{-- Respuesta --}}
<div class="col-12">
    <label for="description" class="form-label">
        Respuesta <span class="text-danger">*</span>
    </label>
    <textarea class="form-control @error('description') is-invalid @enderror"
              id="description"
              name="description"
              rows="6"
              maxlength="5000"
              placeholder="Escribe la respuesta a esta pregunta..."
    >{{ old('description', $faq->description ?? '') }}</textarea>
    @error('description')
    <div class="invalid-feedback">{{ $message }}</div>
    @enderror
    <div class="form-text text-end">
        <span id="descCount">0</span>/5000
    </div>
</div>

{{-- Botones --}}
<div class="col-12 d-flex justify-content-end gap-2 pt-2">
    <a href="{{ route('faqs.index') }}" class="btn btn-outline-light waves-effect">
        Cancelar
    </a>
    <button type="submit" class="btn btn-primary waves-effect waves-light" id="submitBtn">
            <span class="btn-text">
                <i class="fi fi-rr-disk me-1"></i>
                {{ $faq ? 'Actualizar pregunta' : 'Guardar pregunta' }}
            </span>
        <span class="btn-loader d-none">
                <span class="spinner-border spinner-border-sm me-1" role="status" aria-hidden="true"></span>
                Guardando...
            </span>
    </button>
</div>

@push('scripts')
    <script>
        const form = document.getElementById('faqForm');
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

