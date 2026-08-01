@extends('layouts.app')

@section('title', 'Ticket #' . $ticket->id)

@push('styles')
    <link rel="stylesheet" href="{{ asset('assets/css/quill.snow.css') }}">
    <link rel="stylesheet" href="{{ asset('assets/css/dropzone.min.css') }}">
    <style>
        /* ── Avatares de iniciales ── */
        .msg-avatar {
            width: 34px;
            height: 34px;
            border-radius: 50%;
            font-size: .7rem;
            font-weight: 700;
            display: inline-flex;
            align-items: center;
            justify-content: center;
            flex-shrink: 0;
            letter-spacing: .02em;
            user-select: none;
        }

        .msg-avatar.is-staff {
            background: rgba(89, 85, 209, .14);
            color: var(--bs-primary);
        }

        .msg-avatar.is-client {
            background: rgba(100, 116, 139, .12);
            color: #64748b;
        }

        /* ── Burbuja de mensaje ── */
        .msg-bubble {
            border-radius: .5rem;
            border: 1px solid var(--bs-border-color);
            background: var(--bs-body-bg);
        }

        .msg-bubble.is-staff {
            border-color: rgba(89, 85, 209, .25);
            background: rgba(89, 85, 209, .03);
        }

        /* ── Imágenes Quill ── */
        .ticket-desc img, .msg-body img {
            max-width: 100%;
            height: auto;
        }

        /* ── Quill reply ── */
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
            min-height: 130px;
        }

        .quill-focused .ql-toolbar.ql-snow {
            border-color: #86b7fe;
        }

        .quill-focused .ql-container.ql-snow {
            border-color: #86b7fe;
            box-shadow: 0 0 0 .25rem rgba(89, 85, 209, .15);
        }

        .quill-char-count {
            font-size: .72rem;
            color: var(--bs-secondary-color);
            transition: color .2s;
        }

        .quill-char-count.near-limit {
            color: var(--bs-warning);
        }

        .quill-char-count.at-limit {
            color: var(--bs-danger);
        }

        /* ════════════════════════════════
           DROPZONE — zona de arrastre
        ════════════════════════════════ */
        .dropzone {
            border: 2px dashed var(--bs-border-color);
            border-radius: .75rem;
            background: var(--bs-tertiary-bg);
            min-height: auto;
            padding: 1.5rem 1.25rem;
            transition: border-color .25s, background .25s;
            cursor: pointer;
        }

        .dropzone:hover,
        .dropzone.dz-drag-hover {
            border-color: var(--bs-primary);
            background: rgba(89, 85, 209, .04);
        }

        #replyDropzone.dz-started .dz-message {
            display: flex !important;
        }

        .dropzone .dz-message {
            margin: 0;
            display: flex;
            flex-direction: column;
            align-items: center;
            gap: .6rem;
            text-align: center;
        }

        .dz-upload-icon {
            width: 46px;
            height: 46px;
            border-radius: 50%;
            background: rgba(89, 85, 209, .12);
            color: var(--bs-primary);
            display: flex;
            align-items: center;
            justify-content: center;
            font-size: 1.2rem;
            transition: transform .3s ease;
        }

        .dropzone:hover .dz-upload-icon,
        .dropzone.dz-drag-hover .dz-upload-icon {
            transform: translateY(-4px);
        }

        .dz-msg-title {
            font-size: .875rem;
            font-weight: 500;
            color: var(--bs-body-color);
            margin-bottom: .15rem;
        }

        .dz-msg-sub {
            font-size: .76rem;
            color: var(--bs-secondary-color);
            margin-bottom: .25rem;
        }

        .dz-formats {
            display: flex;
            flex-wrap: wrap;
            gap: .3rem;
            justify-content: center;
        }

        .dz-fmt-badge {
            font-size: .66rem;
            padding: .1rem .4rem;
            background: var(--bs-secondary-bg);
            border-radius: 20px;
            color: var(--bs-secondary-color);
        }

        /* ════════════════════════════════
           DROPZONE — tarjetas de archivo
        ════════════════════════════════ */
        #dz-previews-reply {
            display: flex;
            flex-direction: column;
            gap: .625rem;
        }

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

        .dz-file-card:hover {
            border-color: var(--bs-primary-subtle);
        }

        .dz-file-ico {
            width: 40px;
            height: 40px;
            border-radius: .5rem;
            background: rgba(89, 85, 209, .12);
            color: var(--bs-primary);
            display: flex;
            align-items: center;
            justify-content: center;
            font-size: .9rem;
            flex-shrink: 0;
        }

        .dz-file-ico.ico-img {
            background: rgba(13, 110, 253, .1);
            color: #0d6efd;
        }

        .dz-file-ico.ico-pdf {
            background: rgba(220, 53, 69, .1);
            color: #dc3545;
        }

        .dz-file-ico.ico-sheet {
            background: rgba(25, 135, 84, .1);
            color: #198754;
        }

        .dz-file-ico.ico-zip {
            background: rgba(255, 153, 0, .1);
            color: #fd7e14;
        }

        .dz-file-ico.ico-txt {
            background: rgba(108, 117, 125, .1);
            color: #6c757d;
        }

        .dz-file-body {
            flex: 1;
            min-width: 0;
        }

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

        .dz-preview.dz-success .dz-prog-fill {
            background: var(--bs-success);
            width: 100% !important;
        }

        .dz-preview.dz-success .dz-file-card {
            border-color: rgba(25, 135, 84, .3);
        }

        .dz-preview.dz-success .dz-del-btn {
            display: none !important;
        }

        .dz-preview.dz-success .dz-ok-icon {
            display: flex !important;
        }

        .dz-preview.dz-success .dz-err-icon {
            display: none !important;
        }

        .dz-preview.dz-success .dz-pct {
            color: var(--bs-success);
            font-weight: 600;
        }

        .dz-preview.dz-error .dz-prog-fill {
            background: var(--bs-danger);
        }

        .dz-preview.dz-error .dz-file-card {
            border-color: rgba(220, 53, 69, .3);
        }

        .dz-preview.dz-error .dz-del-btn {
            display: none !important;
        }

        .dz-preview.dz-error .dz-ok-icon {
            display: none !important;
        }

        .dz-preview.dz-error .dz-err-icon {
            display: flex !important;
        }

        .dz-preview.dz-error .dz-pct {
            color: var(--bs-danger);
        }

        .dz-action-area {
            display: flex;
            align-items: center;
            flex-shrink: 0;
        }

        .dz-del-btn {
            width: 30px;
            height: 30px;
            border: none;
            background: none;
            padding: 0;
            border-radius: .375rem;
            color: var(--bs-secondary-color);
            display: flex;
            align-items: center;
            justify-content: center;
            cursor: pointer;
            transition: background .2s, color .2s;
        }

        .dz-del-btn:hover {
            background: var(--bs-danger-subtle);
            color: var(--bs-danger);
        }

        .dz-ok-icon {
            width: 28px;
            height: 28px;
            border-radius: 50%;
            background: var(--bs-success-subtle);
            color: var(--bs-success);
            display: none;
            align-items: center;
            justify-content: center;
            font-size: .85rem;
        }

        .dz-err-icon {
            width: 28px;
            height: 28px;
            border-radius: 50%;
            background: var(--bs-danger-subtle);
            color: var(--bs-danger);
            display: none;
            align-items: center;
            justify-content: center;
            font-size: .85rem;
        }

        .dz-err-msg {
            font-size: .72rem;
            color: var(--bs-danger);
            margin-top: .25rem;
            display: none;
        }

        .dz-preview.dz-error .dz-err-msg {
            display: block;
        }

        /* ── Sidebar ── */
        .detail-item {
            padding: .625rem 0;
        }

        .detail-item + .detail-item {
            border-top: 1px solid var(--bs-border-color);
        }

        .detail-item small {
            font-size: .72rem;
            text-transform: uppercase;
            letter-spacing: .04em;
        }
    </style>
@endpush

@section('content')
    <div class="app-page-head d-flex align-items-center justify-content-between flex-wrap gap-3">
        <div>
            <x-breadcrumb :items="[
                ['label' => 'Inicio',  'url' => route('dashboard'),     'icon' => 'fi fi-rr-home'],
                ['label' => 'Tickets', 'url' => route('tickets.index'), 'icon' => 'fi fi-rr-ticket'],
                ['label' => 'Ticket #' . $ticket->id],
            ]"/>
        </div>
        <a href="{{ url()->previous() }}" class="btn btn-outline-light waves-effect">
            <i class="fi fi-rr-arrow-left me-2"></i> Volver
        </a>
    </div>

    <div class="row g-4">

        {{-- ══════════════════════════ COLUMNA PRINCIPAL ══════════════════════════ --}}
        <div class="col-lg-8 d-flex flex-column gap-4">

            {{-- ── Información del ticket ── --}}
            <div class="card mb-0">
                <div class="card-header d-flex align-items-start justify-content-between gap-3 flex-wrap">
                    <div>
                        <span class="text-muted small">#{{ $ticket->id }}</span>
                        <h6 class="mb-0 mt-1 fw-semibold">{{ $ticket->subject }}</h6>
                    </div>
                    <span class="badge fs-xs"
                          style="background:{{ $ticket->status->color ?? '#6c757d' }}1a;
                                 color:{{ $ticket->status->color ?? '#6c757d' }};
                                 border:1px solid {{ $ticket->status->color ?? '#6c757d' }}33;">
                        {{ $ticket->status->name ?? '—' }}
                    </span>
                </div>
                <div class="card-body">
                    <div class="ticket-desc">
                        {!! $ticket->description !!}
                    </div>

                    @if ($ticket->attachments->isNotEmpty())
                        <hr class="my-3">
                        <p class="text-muted small mb-2">
                            <i class="fi fi-rr-paperclip me-1"></i>
                            Archivos adjuntos ({{ $ticket->attachments->count() }})
                        </p>
                        <div class="d-flex flex-wrap gap-2">
                            @foreach ($ticket->attachments as $att)
                                <a href="{{ route('attachments.download', ['type' => 'ticket', 'id' => $att->id]) }}"
                                   target="_blank"
                                   class="btn btn-sm btn-outline-light waves-effect d-inline-flex align-items-center gap-1">
                                    <i class="fi fi-rr-file"></i>
                                    {{ basename($att->file_path) }}
                                    <span
                                        class="badge bg-subtle-secondary text-secondary">{{ strtoupper($att->file_type) }}</span>
                                </a>
                            @endforeach
                        </div>
                    @endif
                </div>
            </div>

            {{-- ── Conversación ── --}}
            <div class="card mb-0">
                <div class="card-header">
                    <h6 class="card-title mb-0">
                        Conversación
                        <span class="badge bg-subtle-primary text-primary ms-1">{{ $ticket->messages->count() }}</span>
                    </h6>
                </div>
                <div class="card-body">
                    @forelse ($ticket->messages as $message)
                        @php
                            $isStaff = in_array($message->user->role ?? '', ['agente', 'admin']);
                            $msgName = $message->user->name ?? '—';
                            $initials = '';
                            foreach (explode(' ', $msgName) as $p) {
                                if ($p !== '') $initials .= strtoupper($p[0]);
                                if (strlen($initials) >= 2) break;
                            }
                        @endphp
                        <div class="d-flex gap-3 {{ !$loop->last ? 'mb-4' : '' }}">
                            <span class="msg-avatar {{ $isStaff ? 'is-staff' : 'is-client' }} mt-1 flex-shrink-0">
                                {{ $initials ?: '?' }}
                            </span>
                            <div class="flex-grow-1 min-w-0">
                                <div class="d-flex align-items-center gap-2 mb-1 flex-wrap">
                                    <span class="fw-semibold">{{ $msgName }}</span>
                                    @if ($isStaff)
                                        <span class="badge bg-subtle-primary text-primary" style="font-size:.68rem;">Soporte</span>
                                    @endif
                                    <small
                                        class="text-muted ms-auto">{{ $message->created_at->format('d/m/Y H:i') }}</small>
                                </div>
                                <div class="msg-bubble {{ $isStaff ? 'is-staff' : '' }} p-3">
                                    <div class="msg-body">
                                        {!! $message->message !!}
                                    </div>
                                    @if ($message->attachments->isNotEmpty())
                                        <hr class="my-2">
                                        <div class="d-flex flex-wrap gap-2">
                                            @foreach ($message->attachments as $att)
                                                <a href="{{ route('attachments.download', ['type' => 'message', 'id' => $att->id]) }}"
                                                   target="_blank"
                                                   class="btn btn-sm btn-outline-light waves-effect d-inline-flex align-items-center gap-1">
                                                    <i class="fi fi-rr-file"></i>
                                                    {{ basename($att->file_path) }}
                                                    <span
                                                        class="badge bg-subtle-secondary text-secondary">{{ strtoupper($att->file_type) }}</span>
                                                </a>
                                            @endforeach
                                        </div>
                                    @endif
                                </div>
                            </div>
                        </div>
                    @empty
                        <div class="text-center py-4 text-muted">
                            <i class="fi fi-rr-comments d-block mb-2" style="font-size:2rem; opacity:.3;"></i>
                            <span style="font-size:.875rem;">Aún no hay respuestas en este ticket.</span>
                        </div>
                    @endforelse
                </div>
            </div>

            {{-- ── Formulario de respuesta ── --}}
            <div class="card mb-0">
                <div class="card-header">
                    <h6 class="card-title mb-0">
                        <i class="fi fi-rr-paper-plane me-2 text-primary"></i> Responder
                    </h6>
                </div>
                <div class="card-body">
                    <form action="{{ route('tickets.responder', $ticket) }}" method="POST"
                          enctype="multipart/form-data" id="replyForm">
                        @csrf

                        <div class="mb-3">
                            <label class="form-label">
                                Mensaje <span class="text-danger">*</span>
                            </label>
                            <div id="quillWrapper">
                                <div id="editor">{!! old('message') !!}</div>
                            </div>
                            <input type="hidden" name="message" id="message" value="{{ old('message') }}">
                            <div class="d-flex justify-content-between mt-1">
                                @error('message')
                                <span class="text-danger small">{{ $message }}</span>
                                @else
                                    <span></span>
                                    @enderror
                                    <span class="quill-char-count" id="charCount">0 / 5 000</span>
                            </div>
                        </div>

                        <div class="mb-4">
                            <label class="form-label">
                                Archivos adjuntos
                                <span class="fw-normal text-muted">(opcional)</span>
                            </label>

                            {{-- Zona de arrastre --}}
                            <div class="dropzone" id="replyDropzone">
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
                            <div id="dz-previews-reply" class="mt-3"></div>
                        </div>

                        <div class="d-flex justify-content-end">
                            <button type="submit" class="btn btn-primary waves-effect waves-light px-4" id="submitBtn">
                                <span class="btn-text">
                                    <i class="fi fi-rr-paper-plane me-2"></i> Enviar respuesta
                                </span>
                                <span class="btn-loader d-none">
                                    <span class="spinner-border spinner-border-sm me-2" role="status"></span>
                                    Enviando...
                                </span>
                            </button>
                        </div>
                    </form>
                </div>
            </div>

        </div>

        {{-- ══════════════════════════ BARRA LATERAL ══════════════════════════ --}}
        <div class="col-lg-4">
            <div class="card sticky-top" style="top:calc(var(--app-header-height, 80px) + 1rem);z-index:8;">
                <div class="card-header">
                    <h6 class="card-title mb-0">
                        <i class="fi fi-rr-info me-2 text-primary"></i> Detalles del ticket
                    </h6>
                </div>
                <div class="card-body px-3 py-2">

                    @php
                        $creatorName = $ticket->creator->name ?? '—';
                        $creatorInitials = '';
                        foreach (explode(' ', $creatorName) as $p) {
                            if ($p !== '') $creatorInitials .= strtoupper($p[0]);
                            if (strlen($creatorInitials) >= 2) break;
                        }
                    @endphp

                    <div class="detail-item">
                        <small class="text-muted d-block mb-1">Solicitante</small>
                        <div class="d-flex align-items-center gap-2">
                            <span class="msg-avatar is-client" style="width:28px;height:28px;font-size:.62rem;">
                                {{ $creatorInitials ?: '?' }}
                            </span>
                            <div>
                                <div class="fw-medium" style="font-size:.875rem;">{{ $creatorName }}</div>
                                <div class="text-muted"
                                     style="font-size:.78rem;">{{ $ticket->creator->email ?? '' }}</div>
                            </div>
                        </div>
                    </div>

                    <div class="detail-item">
                        <small class="text-muted d-block mb-1">Asignado a</small>
                        @if ($ticket->agent)
                            @php
                                $agentName = $ticket->agent->name;
                                $agentInitials = '';
                                foreach (explode(' ', $agentName) as $p) {
                                    if ($p !== '') $agentInitials .= strtoupper($p[0]);
                                    if (strlen($agentInitials) >= 2) break;
                                }
                            @endphp
                            <div class="d-flex align-items-center gap-2">
                                <span class="msg-avatar is-staff" style="width:28px;height:28px;font-size:.62rem;">
                                    {{ $agentInitials }}
                                </span>
                                <span style="font-size:.875rem;">{{ $agentName }}</span>
                            </div>
                        @else
                            <span class="badge bg-warning-subtle text-warning">Sin asignar</span>
                        @endif
                    </div>

                    <div class="detail-item">
                        <small class="text-muted d-block mb-1">Estado</small>
                        <span class="badge"
                              style="background:{{ $ticket->status->color ?? '#6c757d' }}1a;
                                     color:{{ $ticket->status->color ?? '#6c757d' }};
                                     border:1px solid {{ $ticket->status->color ?? '#6c757d' }}33;">
                            {{ $ticket->status->name ?? '—' }}
                        </span>
                    </div>

                    <div class="detail-item">
                        <small class="text-muted d-block mb-1">Prioridad</small>
                        @php
                            $prioClass = match($ticket->priority->name ?? '') {
                                'Alta'  => 'bg-danger-subtle text-danger',
                                'Media' => 'bg-warning-subtle text-warning',
                                default => 'bg-secondary-subtle text-secondary',
                            };
                        @endphp
                        <span class="badge {{ $prioClass }}">{{ $ticket->priority->name ?? '—' }}</span>
                    </div>

                    <div class="detail-item">
                        <small class="text-muted d-block mb-1">Categoría</small>
                        <span style="font-size:.875rem;">{{ $ticket->category->name ?? '—' }}</span>
                    </div>

                    <div class="detail-item">
                        <small class="text-muted d-block mb-1">Creado</small>
                        <span style="font-size:.875rem;">{{ $ticket->created_at->format('d/m/Y H:i') }}</span>
                    </div>

                    @if ($ticket->closed_at)
                        <div class="detail-item">
                            <small class="text-muted d-block mb-1">Cerrado</small>
                            <span style="font-size:.875rem;">{{ $ticket->closed_at->format('d/m/Y H:i') }}</span>
                        </div>
                    @endif

                </div>
            </div>
        </div>

    </div>
@endsection

@push('scripts')
    <script src="{{ asset('assets/js/plugins/quill.js') }}"></script>
    <script src="{{ asset('assets/js/plugins/dropzone.min.js') }}"></script>
    <script src="{{ asset('assets/js/plugins/sweetalert2.js') }}"></script>
    <script>
        // ── Quill ──
        const quill = new Quill('#editor', {
            theme: 'snow',
            placeholder: 'Escribe tu respuesta...',
            modules: {
                toolbar: [
                    ['bold', 'italic', 'underline'],
                    [{list: 'ordered'}, {list: 'bullet'}],
                    ['link', 'blockquote', 'code-block'],
                    ['clean'],
                ],
            },
        });

        const MAX_CHARS = 5000;
        const charCountEl = document.getElementById('charCount');
        const quillWrapper = document.getElementById('quillWrapper');

        quill.on('text-change', function () {
            const len = Math.max(0, quill.getLength() - 1);
            charCountEl.textContent = `${len.toLocaleString('es-PE')} / ${MAX_CHARS.toLocaleString('es-PE')}`;
            charCountEl.className = 'quill-char-count'
                + (len >= MAX_CHARS * 0.9 ? ' near-limit' : '')
                + (len >= MAX_CHARS ? ' at-limit' : '');
        });

        quill.on('selection-change', function (range) {
            quillWrapper.classList.toggle('quill-focused', range !== null);
        });

        // ── Dropzone ──
        Dropzone.autoDiscover = false;

        const EXT_MAP = {
            jpg: ['ico-img', 'fi-rr-picture'],
            jpeg: ['ico-img', 'fi-rr-picture'],
            png: ['ico-img', 'fi-rr-picture'],
            gif: ['ico-img', 'fi-rr-picture'],
            pdf: ['ico-pdf', 'fi-rr-document'],
            xls: ['ico-sheet', 'fi-rr-document'],
            xlsx: ['ico-sheet', 'fi-rr-document'],
            txt: ['ico-txt', 'fi-rr-document'],
            zip: ['ico-zip', 'fi-rr-folder-download'],
            rar: ['ico-zip', 'fi-rr-folder-download'],
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
                    <div class="dz-ok-icon"><i class="fi fi-rr-check"></i></div>
                    <div class="dz-err-icon"><i class="fi fi-rr-cross-small"></i></div>
                </div>
            </div>
        </div>`;

        const dz = new Dropzone('#replyDropzone', {
            url: "{{ route('tickets.responder', $ticket) }}",
            paramName: 'attachments',
            autoProcessQueue: false,
            uploadMultiple: true,
            parallelUploads: 5,
            maxFiles: 5,
            maxFilesize: 5,
            acceptedFiles: '.jpg,.jpeg,.png,.pdf,.doc,.docx,.xls,.xlsx,.txt,.zip',
            addRemoveLinks: false,
            previewsContainer: '#dz-previews-reply',
            previewTemplate: previewTemplate,
            dictFileTooBig: 'Archivo muy grande (@{{filesize}} MB). Máx: @{{maxFilesize}} MB.',
            dictInvalidFileType: 'Tipo de archivo no permitido.',
            dictMaxFilesExceeded: 'Solo puedes adjuntar hasta 5 archivos.',
            headers: {'X-CSRF-TOKEN': document.querySelector('meta[name="csrf-token"]').content},
        });

        dz.on('addedfile', function (file) {
            const ext = file.name.split('.').pop().toLowerCase();
            const [colorClass, iconClass] = EXT_MAP[ext] || ['', 'fi-rr-file-upload'];
            const ico = file.previewElement.querySelector('#dz-ico');
            const icoI = file.previewElement.querySelector('#dz-ico-i');
            if (ico) {
                ico.id = '';
                if (colorClass) ico.classList.add(colorClass);
            }
            if (icoI) {
                icoI.id = '';
                icoI.className = `fi ${iconClass}`;
            }
        });

        dz.on('uploadprogress', function (file, progress) {
            const pct = file.previewElement.querySelector('.dz-pct');
            if (pct) pct.textContent = Math.round(progress) + '%';
        });

        dz.on('success', function (file) {
            const pct = file.previewElement.querySelector('.dz-pct');
            if (pct) pct.textContent = '100%';
        });

        dz.on('error', function (file) {
            const pct = file.previewElement.querySelector('.dz-pct');
            if (pct) pct.textContent = 'Error';
        });

        // ── Submit ──
        const form = document.getElementById('replyForm');
        const btn = document.getElementById('submitBtn');
        const btnText = btn.querySelector('.btn-text');
        const btnLoad = btn.querySelector('.btn-loader');

        function setLoading(on) {
            btn.disabled = on;
            btnText.classList.toggle('d-none', on);
            btnLoad.classList.toggle('d-none', !on);
        }

        form.addEventListener('submit', function (e) {
            const texto = quill.getText().trim();
            document.getElementById('message').value = texto.length ? quill.root.innerHTML : '';

            if (dz.getQueuedFiles().length > 0) {
                e.preventDefault();
                setLoading(true);
                dz.processQueue();
            } else {
                setLoading(true);
            }
        });

        dz.on('sending', function (file, xhr, formData) {
            formData.append('message', document.getElementById('message').value);
        });

        dz.on('successmultiple', function () {
            window.location.href = "{{ route('tickets.show', $ticket) }}";
        });

        dz.on('errormultiple', function (files, response) {
            setLoading(false);
            let msg = 'Ocurrió un error al enviar la respuesta.';
            if (typeof response === 'object' && response.errors) {
                msg = Object.values(response.errors).flat().join('<br>');
            }
            Swal.fire({icon: 'error', title: 'No se pudo enviar', html: msg, confirmButtonColor: '#5955D1'});
        });
    </script>
@endpush
