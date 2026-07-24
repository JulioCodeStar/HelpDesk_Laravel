@extends('layouts.app')

@section('title', 'Nuevo ticket')

@push('styles')
    <link rel="stylesheet" href="{{ asset('assets/css/quill.snow.css') }}">
    <link rel="stylesheet" href="{{ asset('assets/css/dropzone.min.css') }}">
    <link rel="stylesheet" href="{{ asset('assets/css/select2.min.css') }}">
    <style>
        /* ── Secciones del formulario ── */
        .ticket-section {
            padding: 1.5rem 1.75rem;
            border-bottom: 1px solid var(--bs-border-color);
        }
        .ticket-section:last-child { border-bottom: none; }

        .section-header {
            display: flex;
            align-items: center;
            gap: .6rem;
            margin-bottom: 1.25rem;
        }
        .section-icon {
            width: 30px; height: 30px;
            border-radius: .45rem;
            background: rgba(89, 85, 209, .12);
            color: var(--bs-primary);
            display: flex; align-items: center; justify-content: center;
            font-size: .8rem;
            flex-shrink: 0;
        }
        .section-label {
            font-size: .8125rem;
            font-weight: 600;
            color: var(--bs-heading-color);
            margin: 0;
        }

        /* ── Barra de acciones ── */
        .ticket-actions {
            display: flex;
            align-items: center;
            justify-content: space-between;
            padding: 1rem 1.75rem;
            background: var(--bs-tertiary-bg);
            border-top: 1px solid var(--bs-border-color);
        }

        /* ── Asunto: contador ── */
        .subject-wrapper { position: relative; }
        .subject-wrapper .form-control { padding-right: 3.5rem; }
        .subject-counter {
            position: absolute;
            right: .75rem; top: 50%;
            transform: translateY(-50%);
            font-size: .7rem;
            color: var(--bs-secondary-color);
            pointer-events: none;
            white-space: nowrap;
            transition: color .2s;
        }

        /* ── Editor Quill ── */
        .ql-toolbar.ql-snow {
            border-color: var(--bs-border-color);
            border-radius: .5rem .5rem 0 0;
            background: var(--bs-tertiary-bg);
            padding: .5rem .75rem;
            transition: border-color .2s;
        }
        .ql-container.ql-snow {
            border-color: var(--bs-border-color);
            border-radius: 0 0 .5rem .5rem;
            font-family: inherit;
            font-size: .9rem;
            transition: border-color .2s, box-shadow .2s;
        }
        #editor { min-height: 210px; }

        .quill-focused .ql-toolbar.ql-snow  { border-color: #86b7fe; }
        .quill-focused .ql-container.ql-snow {
            border-color: #86b7fe;
            box-shadow: 0 0 0 .25rem rgba(89, 85, 209, .15);
        }
        .quill-footer { display: flex; justify-content: flex-end; margin-top: .375rem; }
        .quill-char-count { font-size: .72rem; color: var(--bs-secondary-color); transition: color .2s; }
        .quill-char-count.near-limit { color: var(--bs-warning); }
        .quill-char-count.at-limit   { color: var(--bs-danger);  }

        /* ════════════════════════════════
           DROPZONE — zona de arrastre
        ════════════════════════════════ */
        .dropzone {
            border: 2px dashed var(--bs-border-color);
            border-radius: .75rem;
            background: var(--bs-tertiary-bg);
            min-height: auto;
            padding: 1.75rem 1.5rem;
            transition: border-color .25s, background .25s;
            cursor: pointer;
        }
        .dropzone:hover,
        .dropzone.dz-drag-hover {
            border-color: var(--bs-primary);
            background: rgba(89, 85, 209, .04);
        }
        /* Mantener el mensaje siempre visible aunque ya haya archivos */
        #attachmentsDropzone.dz-started .dz-message { display: flex !important; }

        .dropzone .dz-message {
            margin: 0;
            display: flex;
            flex-direction: column;
            align-items: center;
            gap: .75rem;
            text-align: center;
        }
        .dz-upload-icon {
            width: 52px; height: 52px;
            border-radius: 50%;
            background: rgba(89, 85, 209, .12);
            color: var(--bs-primary);
            display: flex; align-items: center; justify-content: center;
            font-size: 1.35rem;
            transition: transform .3s ease;
        }
        .dropzone:hover .dz-upload-icon,
        .dropzone.dz-drag-hover .dz-upload-icon { transform: translateY(-5px); }
        .dz-msg-title  { font-size: .9rem; font-weight: 500; color: var(--bs-body-color); margin-bottom: .2rem; }
        .dz-msg-sub    { font-size: .78rem; color: var(--bs-secondary-color); margin-bottom: .375rem; }
        .dz-formats    { display: flex; flex-wrap: wrap; gap: .3rem; justify-content: center; }
        .dz-fmt-badge  {
            font-size: .67rem; padding: .1rem .45rem;
            background: var(--bs-secondary-bg);
            border-radius: 20px;
            color: var(--bs-secondary-color);
        }

        /* ════════════════════════════════
           DROPZONE — tarjetas de archivo
        ════════════════════════════════ */
        #dz-previews { display: flex; flex-direction: column; gap: .625rem; }

        .dz-file-card {
            display: flex;
            align-items: center;
            gap: .875rem;
            padding: .875rem 1rem;
            border: 1px solid var(--bs-border-color);
            border-radius: .625rem;
            background: var(--bs-body-bg);
            transition: border-color .2s;
        }
        .dz-file-card:hover { border-color: var(--bs-primary-subtle); }

        /* Icono de tipo de archivo */
        .dz-file-ico {
            width: 40px; height: 40px;
            border-radius: .5rem;
            background: rgba(89, 85, 209, .12);
            color: var(--bs-primary);
            display: flex; align-items: center; justify-content: center;
            font-size: .9rem;
            flex-shrink: 0;
        }
        /* colores según tipo */
        .dz-file-ico.ico-img   { background: rgba(13, 110, 253, .1); color: #0d6efd; }
        .dz-file-ico.ico-pdf   { background: rgba(220, 53, 69, .1);  color: #dc3545; }
        .dz-file-ico.ico-sheet { background: rgba(25, 135, 84, .1);  color: #198754; }
        .dz-file-ico.ico-zip   { background: rgba(255, 153, 0, .1);  color: #fd7e14; }
        .dz-file-ico.ico-txt   { background: rgba(108, 117, 125, .1); color: #6c757d; }

        /* Cuerpo de la tarjeta */
        .dz-file-body { flex: 1; min-width: 0; }
        .dz-file-name {
            font-size: .8375rem;
            font-weight: 600;
            color: var(--bs-body-color);
            white-space: nowrap;
            overflow: hidden;
            text-overflow: ellipsis;
            margin-bottom: .35rem;
        }
        .dz-prog-track {
            height: 5px;
            border-radius: 99px;
            background: var(--bs-secondary-bg);
            overflow: hidden;
            margin-bottom: .3rem;
        }
        .dz-prog-fill {
            height: 100%;
            width: 0%;
            border-radius: 99px;
            background: var(--bs-primary);
            transition: width .3s ease, background .3s;
        }
        .dz-file-meta {
            display: flex;
            justify-content: space-between;
            font-size: .72rem;
            color: var(--bs-secondary-color);
        }

        /* Estado uploading */
        .dz-preview.dz-processing .dz-prog-fill { background: var(--bs-primary); }

        /* Estado success */
        .dz-preview.dz-success .dz-prog-fill    { background: var(--bs-success); width: 100% !important; }
        .dz-preview.dz-success .dz-file-card    { border-color: rgba(25,135,84,.3); }
        .dz-preview.dz-success .dz-del-btn      { display: none !important; }
        .dz-preview.dz-success .dz-ok-icon      { display: flex !important; }
        .dz-preview.dz-success .dz-err-icon     { display: none !important; }
        .dz-preview.dz-success .dz-pct          { color: var(--bs-success); font-weight: 600; }

        /* Estado error */
        .dz-preview.dz-error .dz-prog-fill      { background: var(--bs-danger); }
        .dz-preview.dz-error .dz-file-card      { border-color: rgba(220,53,69,.3); }
        .dz-preview.dz-error .dz-del-btn        { display: none !important; }
        .dz-preview.dz-error .dz-ok-icon        { display: none !important; }
        .dz-preview.dz-error .dz-err-icon       { display: flex !important; }
        .dz-preview.dz-error .dz-pct            { color: var(--bs-danger); }

        /* Iconos de acción */
        .dz-action-area { display: flex; align-items: center; flex-shrink: 0; }

        .dz-del-btn {
            width: 30px; height: 30px;
            border: none; background: none; padding: 0;
            border-radius: .375rem;
            color: var(--bs-secondary-color);
            display: flex; align-items: center; justify-content: center;
            cursor: pointer;
            transition: background .2s, color .2s;
        }
        .dz-del-btn:hover { background: var(--bs-danger-subtle); color: var(--bs-danger); }

        .dz-ok-icon {
            width: 28px; height: 28px;
            border-radius: 50%;
            background: var(--bs-success-subtle);
            color: var(--bs-success);
            display: none;
            align-items: center; justify-content: center;
            font-size: .85rem;
        }
        .dz-err-icon {
            width: 28px; height: 28px;
            border-radius: 50%;
            background: var(--bs-danger-subtle);
            color: var(--bs-danger);
            display: none;
            align-items: center; justify-content: center;
            font-size: .85rem;
        }

        /* Mensaje de error */
        .dz-err-msg {
            font-size: .72rem;
            color: var(--bs-danger);
            margin-top: .25rem;
            display: none;
        }
        .dz-preview.dz-error .dz-err-msg { display: block; }

        /* ── Panel lateral ── */
        .tips-card { overflow: hidden; }
        .tips-header {
            padding: 1rem 1.25rem;
            background: linear-gradient(135deg, rgba(89,85,209,.1), rgba(89,85,209,.03));
            border-bottom: 1px solid var(--bs-border-color);
            display: flex; align-items: center; gap: .5rem;
        }
        .tips-header-label { font-size: .8125rem; font-weight: 600; margin: 0; }

        .tip-row {
            display: flex; gap: .75rem; align-items: flex-start;
            padding: .875rem 1.25rem;
            border-bottom: 1px solid var(--bs-border-color);
        }
        .tip-row:last-child { border-bottom: none; }
        .tip-ico   { color: var(--bs-primary); font-size: .85rem; flex-shrink: 0; margin-top: .1rem; }
        .tip-body  { font-size: .79rem; color: var(--bs-secondary-color); line-height: 1.5; }

        .status-row { display: flex; align-items: center; gap: .5rem; padding: .3rem 0; }
        .status-lbl { font-size: .78rem; color: var(--bs-secondary-color); }

        /* ── Select2: integración con el tema ── */
        .select2-container--default .select2-selection--single {
            height: calc(1.5em + .75rem + 2px);
            border-color: var(--bs-border-color);
            border-radius: .375rem;
            background-color: var(--bs-body-bg);
            display: flex;
            align-items: center;
        }
        .select2-container--default .select2-selection--single .select2-selection__rendered {
            color: var(--bs-body-color);
            line-height: 1.5;
            padding-left: .75rem;
        }
        .select2-container--default .select2-selection--single .select2-selection__placeholder {
            color: var(--bs-secondary-color);
        }
        .select2-container--default .select2-selection--single .select2-selection__arrow {
            height: 100%;
            right: .5rem;
        }
        .select2-container--default.select2-container--focus .select2-selection--single,
        .select2-container--default.select2-container--open  .select2-selection--single {
            border-color: #86b7fe;
            box-shadow: 0 0 0 .25rem rgba(89, 85, 209, .15);
            outline: none;
        }
        .select2-dropdown {
            border-color: var(--bs-border-color);
            border-radius: .375rem;
            background-color: var(--bs-body-bg);
            box-shadow: 0 .5rem 1rem rgba(0,0,0,.1);
        }
        .select2-container--default .select2-search--dropdown .select2-search__field {
            border-color: var(--bs-border-color);
            border-radius: .25rem;
            background-color: var(--bs-tertiary-bg);
            color: var(--bs-body-color);
            padding: .375rem .625rem;
        }
        .select2-container--default .select2-results__option {
            color: var(--bs-body-color);
            padding: .45rem .75rem;
            font-size: .875rem;
        }
        .select2-container--default .select2-results__option--highlighted[aria-selected] {
            background-color: var(--bs-primary);
            color: #fff;
        }
        .select2-container--default .select2-results__option[aria-selected=true] {
            background-color: rgba(89, 85, 209, .08);
            color: var(--bs-primary);
        }
        /* estado inválido */
        .is-invalid + .select2-container--default .select2-selection--single,
        .select2-container--default.select2-container--invalid .select2-selection--single {
            border-color: var(--bs-danger);
        }
    </style>
@endpush

@section('content')

    <div class="app-page-head d-flex align-items-center justify-content-between flex-wrap gap-3 mb-4">
        <div>
            <x-breadcrumb :items="[
                ['label' => 'Inicio',        'url' => route('dashboard'), 'icon' => 'fi fi-rr-home'],
                ['label' => 'Tickets',       'url' => route('tickets.index'),  'icon' => 'fi fi-rr-ticket'],
                ['label' => 'Nuevo ticket'],
            ]"/>
            <p class="text-muted small mb-0 mt-1">
                Completa el formulario y nuestro equipo te atenderá a la brevedad.
            </p>
        </div>
    </div>

    <form action="{{ route('tickets.store') }}" method="POST"
          enctype="multipart/form-data" id="ticketForm" novalidate>
        @csrf

        <div class="row g-4 align-items-start">

            {{-- ════════════════════════════
                 COLUMNA PRINCIPAL (col-lg-8)
            ════════════════════════════ --}}
            <div class="col-lg-8">
                <div class="card p-0 overflow-hidden">

                    {{-- Sección 1: Información básica --}}
                    <div class="ticket-section">
                        <div class="section-header">
                            <div class="section-icon"><i class="fi fi-rr-info"></i></div>
                            <p class="section-label">Información básica</p>
                        </div>
                        <div class="row g-3">
                            <div class="col-md-5">
                                <label for="category_id" class="form-label">
                                    Categoría <span class="text-danger">*</span>
                                </label>
                                <select class="@error('category_id') is-invalid @enderror"
                                        id="category_id" name="category_id" required>
                                    <option value="">— Selecciona —</option>
                                    @foreach ($categories as $category)
                                        <option value="{{ $category->id }}"
                                            {{ old('category_id') == $category->id ? 'selected' : '' }}>
                                            {{ $category->name }}
                                        </option>
                                    @endforeach
                                </select>
                                @error('category_id')
                                    <div class="invalid-feedback">{{ $message }}</div>
                                @enderror
                            </div>

                            <div class="col-md-7">
                                <label for="subject" class="form-label">
                                    Asunto <span class="text-danger">*</span>
                                </label>
                                <div class="subject-wrapper">
                                    <input type="text"
                                           class="form-control @error('subject') is-invalid @enderror"
                                           id="subject" name="subject"
                                           value="{{ old('subject') }}"
                                           placeholder="Resume tu solicitud en una línea"
                                           maxlength="255" required>
                                    <span class="subject-counter" id="subjectCounter">0/255</span>
                                </div>
                                @error('subject')
                                    <div class="invalid-feedback">{{ $message }}</div>
                                @enderror
                            </div>
                        </div>
                    </div>

                    {{-- Sección 2: Descripción --}}
                    <div class="ticket-section">
                        <div class="section-header">
                            <div class="section-icon"><i class="fi fi-rr-document"></i></div>
                            <p class="section-label">
                                Descripción del problema <span class="text-danger">*</span>
                            </p>
                        </div>
                        <div id="quillWrapper">
                            <div id="editor">{!! old('description') !!}</div>
                            <input type="hidden" name="description" id="description"
                                   value="{{ old('description') }}">
                        </div>
                        <div class="quill-footer">
                            <span class="quill-char-count" id="quillCharCount">0 / 5 000 caracteres</span>
                        </div>
                        @error('description')
                            <div class="text-danger small mt-1">{{ $message }}</div>
                        @enderror
                    </div>

                    {{-- Sección 3: Adjuntos --}}
                    <div class="ticket-section">
                        <div class="section-header">
                            <div class="section-icon"><i class="fi fi-rr-clip"></i></div>
                            <p class="section-label">
                                Archivos adjuntos
                                <span class="fw-normal text-muted">(opcional)</span>
                            </p>
                        </div>

                        {{-- Zona de arrastre --}}
                        <div class="dropzone" id="attachmentsDropzone">
                            <div class="dz-message needsclick">
                                <div class="dz-upload-icon">
                                    <i class="fi fi-rr-cloud-upload"></i>
                                </div>
                                <div>
                                    <p class="dz-msg-title">
                                        <span class="text-primary fw-semibold">Haz clic aquí</span>
                                        para subir o arrastra y suelta
                                    </p>
                                    <p class="dz-msg-sub">Máximo 5 archivos · 5 MB cada uno</p>
                                    <div class="dz-formats">
                                        @foreach(['JPG','PNG','PDF','DOC','DOCX','XLS','XLSX','TXT','ZIP'] as $fmt)
                                            <span class="dz-fmt-badge">{{ $fmt }}</span>
                                        @endforeach
                                    </div>
                                </div>
                            </div>
                        </div>

                        {{-- Tarjetas de archivos añadidos --}}
                        <div id="dz-previews" class="mt-3"></div>

                        @error('attachments')
                            <div class="text-danger small mt-2">{{ $message }}</div>
                        @enderror
                        @error('attachments.*')
                            <div class="text-danger small mt-2">{{ $message }}</div>
                        @enderror
                    </div>

                    {{-- Acciones --}}
                    <div class="ticket-actions">
                        <a href="{{ route('tickets.index') }}"
                           class="btn btn-outline-light waves-effect">
                            <i class="fi fi-rr-arrow-left me-1"></i> Cancelar
                        </a>
                        <button type="submit"
                                class="btn btn-primary waves-effect waves-light px-4"
                                id="submitBtn">
                            <span class="btn-text">
                                <i class="fi fi-rr-paper-plane me-1"></i> Enviar ticket
                            </span>
                            <span class="btn-loader d-none">
                                <span class="spinner-border spinner-border-sm me-1" role="status"></span>
                                Enviando…
                            </span>
                        </button>
                    </div>

                </div>
            </div>

            {{-- ════════════════════════════
                 PANEL LATERAL (col-lg-4)
            ════════════════════════════ --}}
            <div class="col-lg-4 d-flex flex-column gap-3">

                <div class="card tips-card p-0">
                    <div class="tips-header">
                        <i class="fi fi-rr-bulb text-primary fs-6"></i>
                        <p class="tips-header-label">Consejos para un buen ticket</p>
                    </div>
                    <div class="tip-row">
                        <i class="fi fi-rr-checkbox tip-ico"></i>
                        <span class="tip-body">Elige la <strong>categoría</strong> que mejor describe
                            tu solicitud para que el equipo correcto lo atienda de inmediato.</span>
                    </div>
                    <div class="tip-row">
                        <i class="fi fi-rr-checkbox tip-ico"></i>
                        <span class="tip-body">El <strong>asunto</strong> debe ser una frase corta y
                            directa, como el título de un correo importante.</span>
                    </div>
                    <div class="tip-row">
                        <i class="fi fi-rr-checkbox tip-ico"></i>
                        <span class="tip-body">En la <strong>descripción</strong> incluye los pasos para
                            reproducir el problema y cualquier mensaje de error que veas.</span>
                    </div>
                    <div class="tip-row">
                        <i class="fi fi-rr-checkbox tip-ico"></i>
                        <span class="tip-body">Adjunta <strong>capturas de pantalla</strong> o logs:
                            un ejemplo vale más que mil palabras.</span>
                    </div>
                </div>

                <div class="card">
                    <div class="card-body" style="padding: 1rem 1.25rem;">
                        <p class="text-uppercase fw-semibold text-muted mb-2"
                           style="font-size:.68rem; letter-spacing:.06em;">
                            Valores al crear el ticket
                        </p>
                        <div class="status-row">
                            <span class="badge bg-info-subtle text-info">Abierto</span>
                            <span class="status-lbl">Estado inicial</span>
                        </div>
                        <div class="status-row">
                            <span class="badge bg-warning-subtle text-warning">Baja</span>
                            <span class="status-lbl">Prioridad por defecto</span>
                        </div>
                        <div class="status-row">
                            <span class="badge bg-secondary-subtle text-secondary">Sin asignar</span>
                            <span class="status-lbl">Un agente lo tomará pronto</span>
                        </div>
                    </div>
                </div>

            </div>

        </div>
    </form>

@endsection

@push('scripts')
    <script src="{{ asset('assets/js/plugins/select2.min.js') }}"></script>
    <script src="{{ asset('assets/js/plugins/sweetalert2.js') }}"></script>
    <script src="{{ asset('assets/js/plugins/quill.js') }}"></script>
    <script src="{{ asset('assets/js/plugins/dropzone.min.js') }}"></script>
    <script>
        /* ── Select2: categoría ── */
        $('#category_id').select2({
            placeholder: '— Selecciona —',
            allowClear: true,
            width: '100%',
            language: {
                noResults: function () { return 'Sin resultados'; },
                searching:  function () { return 'Buscando…';     },
            },
        });

        /* ── Editor Quill ── */
        const quill = new Quill('#editor', {
            theme: 'snow',
            placeholder: 'Describe con detalle tu solicitud: pasos para reproducir, mensajes de error, versiones…',
            modules: {
                toolbar: [
                    [{ header: [2, 3, false] }],
                    ['bold', 'italic', 'underline', 'strike'],
                    [{ list: 'ordered' }, { list: 'bullet' }],
                    ['link', 'blockquote', 'code-block'],
                    ['clean'],
                ],
            },
        });

        const MAX_CHARS    = 5000;
        const charCountEl  = document.getElementById('quillCharCount');
        const quillWrapper = document.getElementById('quillWrapper');

        quill.on('text-change', function () {
            const len = Math.max(0, quill.getLength() - 1);
            charCountEl.textContent = `${len.toLocaleString('es-PE')} / ${MAX_CHARS.toLocaleString('es-PE')} caracteres`;
            charCountEl.className = 'quill-char-count'
                + (len >= MAX_CHARS * 0.9 ? ' near-limit' : '')
                + (len >= MAX_CHARS       ? ' at-limit'   : '');
        });
        quill.on('selection-change', function (range) {
            quillWrapper.classList.toggle('quill-focused', range !== null);
        });

        /* ── Contador de asunto ── */
        const subjectInput   = document.getElementById('subject');
        const subjectCounter = document.getElementById('subjectCounter');
        function updateSubjectCounter() {
            const len = subjectInput.value.length;
            subjectCounter.textContent = `${len}/255`;
            subjectCounter.style.color = len > 230
                ? 'var(--bs-danger)'
                : len > 200 ? 'var(--bs-warning)' : 'var(--bs-secondary-color)';
        }
        subjectInput.addEventListener('input', updateSubjectCounter);
        updateSubjectCounter();

        /* ── Dropzone ── */
        Dropzone.autoDiscover = false;

        // Mapa de extensión → clase de color e icono
        const EXT_MAP = {
            jpg:  ['ico-img',   'fi-rr-picture'],
            jpeg: ['ico-img',   'fi-rr-picture'],
            png:  ['ico-img',   'fi-rr-picture'],
            gif:  ['ico-img',   'fi-rr-picture'],
            pdf:  ['ico-pdf',   'fi-rr-document'],
            xls:  ['ico-sheet', 'fi-rr-document'],
            xlsx: ['ico-sheet', 'fi-rr-document'],
            txt:  ['ico-txt',   'fi-rr-document'],
            zip:  ['ico-zip',   'fi-rr-folder-download'],
            rar:  ['ico-zip',   'fi-rr-folder-download'],
        };

        const previewTemplate = `
            <div class="dz-preview dz-file-preview">
                <div class="dz-file-card">
                    <div class="dz-file-ico" id="dz-ico">
                        <i class="fi fi-rr-file-upload" id="dz-ico-i"></i>
                    </div>
                    <div class="dz-file-body">
                        <div class="dz-file-name" data-dz-name></div>
                        <div class="dz-prog-track">
                            <div class="dz-prog-fill" data-dz-uploadprogress></div>
                        </div>
                        <div class="dz-file-meta">
                            <span data-dz-size></span>
                            <span class="dz-pct">En cola</span>
                        </div>
                        <div class="dz-err-msg" data-dz-errormessage></div>
                    </div>
                    <div class="dz-action-area">
                        <button type="button" class="dz-del-btn" data-dz-remove title="Quitar archivo">
                            <i class="fi fi-rr-trash"></i>
                        </button>
                        <div class="dz-ok-icon">
                            <i class="fi fi-rr-check"></i>
                        </div>
                        <div class="dz-err-icon">
                            <i class="fi fi-rr-cross-small"></i>
                        </div>
                    </div>
                </div>
            </div>`;

        const dz = new Dropzone('#attachmentsDropzone', {
            url: "{{ route('tickets.store') }}",
            paramName: 'attachments',
            autoProcessQueue: false,
            uploadMultiple: true,
            parallelUploads: 5,
            maxFiles: 5,
            maxFilesize: 5,
            acceptedFiles: '.jpg,.jpeg,.png,.pdf,.doc,.docx,.xls,.xlsx,.txt,.zip',
            addRemoveLinks: false,          // usamos nuestro botón custom
            previewsContainer: '#dz-previews',
            previewTemplate: previewTemplate,
            dictFileTooBig: 'Archivo muy grande (@{{filesize}} MB). Máximo: @{{maxFilesize}} MB.',
            dictInvalidFileType: 'Tipo de archivo no permitido.',
            dictMaxFilesExceeded: 'Solo puedes adjuntar hasta 5 archivos.',
            headers: {
                'X-CSRF-TOKEN': document.querySelector('meta[name="csrf-token"]').content,
            },
        });

        /* Icono y color según extensión */
        dz.on('addedfile', function (file) {
            const ext = file.name.split('.').pop().toLowerCase();
            const [colorClass, iconClass] = EXT_MAP[ext] || ['', 'fi-rr-file-upload'];
            const ico  = file.previewElement.querySelector('#dz-ico');
            const icoI = file.previewElement.querySelector('#dz-ico-i');
            if (ico)  { ico.id = ''; if (colorClass) ico.classList.add(colorClass); }
            if (icoI) { icoI.id = ''; icoI.className = `fi ${iconClass}`; }
        });

        /* Progreso en tiempo real (durante el upload al enviar) */
        dz.on('uploadprogress', function (file, progress) {
            const pct = file.previewElement.querySelector('.dz-pct');
            if (pct) pct.textContent = Math.round(progress) + '%';
        });

        dz.on('success', function (file) {
            const pct = file.previewElement.querySelector('.dz-pct');
            if (pct) pct.textContent = '100%';
        });

        dz.on('error', function (file, message) {
            const pct = file.previewElement.querySelector('.dz-pct');
            if (pct) pct.textContent = 'Error';
        });

        /* ── Envío del formulario ── */
        const form = document.getElementById('ticketForm');
        const btn  = document.getElementById('submitBtn');

        function setLoading(state) {
            btn.disabled = state;
            btn.querySelector('.btn-text').classList.toggle('d-none', state);
            btn.querySelector('.btn-loader').classList.toggle('d-none', !state);
        }

        form.addEventListener('submit', function (e) {
            const html  = quill.root.innerHTML;
            const texto = quill.getText().trim();
            document.getElementById('description').value = texto.length ? html : '';

            if (dz.getQueuedFiles().length > 0) {
                e.preventDefault();
                setLoading(true);
                dz.processQueue();
            } else {
                setLoading(true);
            }
        });

        dz.on('sending', function (file, xhr, formData) {
            formData.append('category_id', document.getElementById('category_id').value);
            formData.append('subject',     document.getElementById('subject').value);
            formData.append('description', document.getElementById('description').value);
        });

        dz.on('successmultiple', function () {
            window.location.href = "{{ route('tickets.index') }}";
        });

        dz.on('errormultiple', function (files, response) {
            setLoading(false);
            let mensaje = 'Ocurrió un error al enviar el ticket.';
            if (typeof response === 'object' && response.errors) {
                mensaje = Object.values(response.errors).flat().join('<br>');
            }
            Swal.fire({
                icon: 'error',
                title: 'No se pudo enviar',
                html: mensaje,
                confirmButtonColor: '#5955D1',
            });
        });
    </script>
@endpush
