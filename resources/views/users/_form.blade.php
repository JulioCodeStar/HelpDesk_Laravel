{{-- Parcial compartido por create.blade.php y edit.blade.php --}}
@php $user = $user ?? null; @endphp

@push('styles')
    <link href="{{ asset('assets/css/select2.min.css') }}" rel="stylesheet"/>
@endpush

{{-- Nombre --}}
<div class="col-md-6">
    <label for="name" class="form-label">
        Nombre <span class="text-danger">*</span>
    </label>
    <input type="text"
           class="form-control @error('name') is-invalid @enderror"
           id="name"
           name="name"
           value="{{ old('name', $user->name ?? '') }}"
           placeholder="Ej. Juan Pérez"
           maxlength="255"
           autofocus>
    @error('name')
    <div class="invalid-feedback">{{ $message }}</div>
    @enderror
</div>

{{-- Correo --}}
<div class="col-md-6">
    <label for="email" class="form-label">
        Correo electrónico <span class="text-danger">*</span>
    </label>
    <input type="email"
           class="form-control @error('email') is-invalid @enderror"
           id="email"
           name="email"
           value="{{ old('email', $user->email ?? '') }}"
           placeholder="Ej. juan.perez@empresa.com"
           maxlength="255">
    @error('email')
    <div class="invalid-feedback">{{ $message }}</div>
    @enderror
</div>

{{-- Departamento --}}
<div class="col-md-6">
    <label for="department_id" class="form-label">Departamento</label>
    <select class="form-select select-department @error('department_id') is-invalid @enderror"
            id="department_id"
            name="department_id">
        <option value="">— Sin departamento —</option>
        @foreach ($departments as $department)
            <option value="{{ $department->id }}"
                {{ old('department_id', $user->department_id ?? '') == $department->id ? 'selected' : '' }}>
                {{ $department->name }}
            </option>
        @endforeach
    </select>
    @error('department_id')
    <div class="invalid-feedback">{{ $message }}</div>
    @enderror
</div>

{{-- Rol --}}
<div class="col-md-6">
    <label for="role" class="form-label">
        Rol <span class="text-danger">*</span>
    </label>
    <select class="form-select selec-role @error('role') is-invalid @enderror"
            id="role"
            name="role">
        <option value="">— Selecciona un rol —</option>
        @foreach (['cliente' => 'Cliente', 'agente' => 'Agente', 'admin' => 'Administrador'] as $value => $label)
            <option value="{{ $value }}"
                {{ old('role', $user->role ?? 'cliente') == $value ? 'selected' : '' }}>
                {{ $label }}
            </option>
        @endforeach
    </select>
    @error('role')
    <div class="invalid-feedback">{{ $message }}</div>
    @enderror
</div>

{{-- Separador de credenciales --}}
<div class="col-12">
    <hr class="my-2">
    <h6 class="mb-0">
        {{ $user ? 'Cambiar contraseña' : 'Credenciales de acceso' }}
    </h6>
    @if ($user)
        <small class="text-muted">Déjalo en blanco si no deseas cambiar la contraseña.</small>
    @endif
</div>

{{-- Contraseña --}}
<div class="col-md-6">
    <label for="password" class="form-label">
        Contraseña
        @unless ($user)
            <span class="text-danger">*</span>
        @endunless
    </label>
    <div class="position-relative">
        <input type="password"
               class="form-control @error('password') is-invalid @enderror"
               id="password"
               name="password"
               placeholder="Mínimo 8 caracteres"
               minlength="8">
        @error('password')
        <div class="invalid-feedback">{{ $message }}</div>
        @enderror
    </div>
</div>

{{-- Confirmar contraseña --}}
<div class="col-md-6">
    <label for="password_confirmation" class="form-label">
        Confirmar contraseña
        @unless ($user)
            <span class="text-danger">*</span>
        @endunless
    </label>
    <input type="password"
           class="form-control"
           id="password_confirmation"
           name="password_confirmation"
           placeholder="Repite la contraseña"
           minlength="8">
</div>

{{-- Botones --}}
<div class="col-12 d-flex justify-content-end gap-2 pt-2">
    <a href="{{ route('users.index') }}" class="btn btn-outline-light waves-effect">
        Cancelar
    </a>
    <button type="submit" class="btn btn-primary waves-effect waves-light" id="submitBtn">
            <span class="btn-text">
                <i class="fi fi-rr-disk me-1"></i>
                {{ $user ? 'Actualizar usuario' : 'Guardar usuario' }}
            </span>
        <span class="btn-loader d-none">
                <span class="spinner-border spinner-border-sm me-1" role="status" aria-hidden="true"></span>
                Guardando...
            </span>
    </button>
</div>


@push('scripts')
    <script src="{{ asset('assets/js/plugins/select2.min.js') }}"></script>
    <script>
        $(document).ready(function() {
            $('.select-department').select2({
                placeholder: 'Seleccionar Departamento',
                width: '100%'
            });
        });

        const form = document.getElementById('userForm');
        const btn  = document.getElementById('submitBtn');

        // Loader en el botón al enviar
        form.addEventListener('submit', function () {
            btn.disabled = true;
            btn.querySelector('.btn-text').classList.add('d-none');
            btn.querySelector('.btn-loader').classList.remove('d-none');
        });
    </script>
@endpush
