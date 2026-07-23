<?php

namespace App\Http\Controllers;

use App\Models\Ticket;
use App\Models\Category;
use App\Models\AttachmentTicket;
use Illuminate\Http\JsonResponse;
use Illuminate\Http\Request;
use Illuminate\Support\Facades\DB;
use Illuminate\Support\Facades\Log;
use Illuminate\Support\Str;

class TicketController extends Controller
{

    public function index()
    {
        return view('tickets.index');
    }

    /**
     * Endpoint para DataTables server-side.
     * Devuelve solo la página solicitada (LIMIT/OFFSET en SQL)
     * con búsqueda y ordenamiento en la base de datos.
     */
    public function datatables(Request $request): JsonResponse
    {
        $draw   = (int) $request->input('draw', 1);
        $start  = max(0, (int) $request->input('start', 0));
        $length = min(100, max(1, (int) $request->input('length', 25)));
        $search = trim((string) $request->input('search.value', ''));

        // Columnas mapeadas por índice DataTables → columna SQL ordenable
        $sortMap = [
            0 => 'tickets.id',
            1 => 'tickets.subject',
            7 => 'tickets.created_at',
        ];
        $orderIdx = (int) $request->input('order.0.column', 7);
        $orderDir = $request->input('order.0.dir', 'desc') === 'asc' ? 'asc' : 'desc';
        $sortCol  = $sortMap[$orderIdx] ?? 'tickets.created_at';

        // Query base con JOINs (evita N+1 y subqueries)
        $base = Ticket::query()
            ->select([
                'tickets.id',
                'tickets.subject',
                'tickets.created_at',
                'u.name  as creator_name',
                'a.name  as agent_name',
                'c.name  as category_name',
                's.name  as status_name',
                's.color as status_color',
                'p.name  as priority_name',
            ])
            ->leftJoin('users as u', 'tickets.user_id',     '=', 'u.id')
            ->leftJoin('users as a', 'tickets.assigned_to', '=', 'a.id')
            ->leftJoin('categories as c', 'tickets.category_id', '=', 'c.id')
            ->leftJoin('statuses as s',   'tickets.status_id',   '=', 's.id')
            ->leftJoin('priorities as p', 'tickets.priority_id', '=', 'p.id');

        if (auth()->user()->role === 'cliente') {
            $base->where('tickets.user_id', auth()->id());
        }

        $total = (clone $base)->count();

        if ($search !== '') {
            $base->where(function ($q) use ($search) {
                $q->where('tickets.id',      'like', "%{$search}%")
                  ->orWhere('tickets.subject', 'like', "%{$search}%")
                  ->orWhere('u.name',          'like', "%{$search}%")
                  ->orWhere('c.name',          'like', "%{$search}%")
                  ->orWhere('s.name',          'like', "%{$search}%")
                  ->orWhere('p.name',          'like', "%{$search}%");
            });
        }

        $filtered = (clone $base)->count();

        $rows = $base->orderBy($sortCol, $orderDir)
                     ->skip($start)
                     ->take($length)
                     ->get();

        $data = $rows->map(function ($row) {
            // Iniciales CSS en lugar de avatar externo
            $creatorName = $row->creator_name ?? '—';
            $initials = '';
            foreach (explode(' ', $creatorName) as $part) {
                if ($part !== '') $initials .= strtoupper($part[0]);
                if (strlen($initials) >= 2) break;
            }

            // Badge de prioridad
            $prioClass = match ($row->priority_name) {
                'Alta'  => 'bg-danger-subtle text-danger',
                'Media' => 'bg-warning-subtle text-warning',
                default => 'bg-secondary-subtle text-secondary',
            };

            // Badge de estado con color dinámico (#rrggbb + 1a = rgba 10%)
            $statusColor = $row->status_color ?? '#6c757d';

            return [
                'id'         => '#' . $row->id,
                'subject'    => e(Str::limit($row->subject, 60)),
                'creator'    => '<div class="d-flex align-items-center gap-2">'
                               . '<span class="dt-avatar">' . e($initials) . '</span>'
                               . '<span>' . e($creatorName) . '</span>'
                               . '</div>',
                'assigned'   => $row->agent_name
                               ? e($row->agent_name)
                               : '<span class="badge bg-warning-subtle text-warning">Sin asignar</span>',
                'category'   => e($row->category_name ?? '—'),
                'priority'   => '<span class="badge ' . $prioClass . '">' . e($row->priority_name ?? '—') . '</span>',
                'status'     => '<span class="badge" style="background:' . e($statusColor) . '1a;color:' . e($statusColor) . '">'
                               . e($row->status_name ?? '—') . '</span>',
                'created_at' => $row->created_at?->format('d/m/Y') ?? '—',
                'actions'    => '<div class="d-flex justify-content-end gap-2">'
                               . '<a href="#" class="btn btn-sm btn-subtle-primary waves-effect"><i class="fi fi-rr-eye"></i></a>'
                               . '</div>',
            ];
        });

        return response()->json([
            'draw'            => $draw,
            'recordsTotal'    => $total,
            'recordsFiltered' => $filtered,
            'data'            => $data,
        ]);
    }

    /**
     * Muestra el formulario para registrar un ticket.
     * Solo se envían las categorías: estado, prioridad y agente asignado
     * no se eligen aquí (se definen por defecto o los asigna un agente después).
     */
    public function create()
    {
        try {
            $categories = Category::orderBy('name')->get();
            return view('tickets.create', compact('categories'));
        } catch (\Exception $e) {
            Log::error('Error en TicketController@create: ' . $e->getMessage());
            return redirect()->route('tickets.index')->with('error', 'Error al cargar el formulario.');
        }
    }

    /**
     * Registra el ticket del usuario autenticado junto con sus archivos adjuntos.
     * - user_id se toma de la sesión.
     * - assigned_to queda null (lo tomará un agente).
     * - status_id y priority_id se fijan en 1 por defecto.
     */
    public function store(Request $request)
    {
        $validated = $request->validate([
            'category_id' => 'required|exists:categories,id',
            'subject' => 'required|string|max:255',
            'description' => 'required|string|max:5000',
            'attachments' => 'nullable|array|max:5',
            'attachments.*' => 'file|max:5120|mimes:jpg,jpeg,png,pdf,doc,docx,xls,xlsx,txt,zip',
        ], [
            'category_id.required' => 'Selecciona una categoría.',
            'category_id.exists' => 'La categoría seleccionada no es válida.',
            'subject.required' => 'El asunto es obligatorio.',
            'subject.max' => 'El asunto no puede superar los 255 caracteres.',
            'description.required' => 'Describe tu solicitud.',
            'description.max' => 'La descripción no puede superar los 5000 caracteres.',
            'attachments.max' => 'Puedes adjuntar como máximo 5 archivos.',
            'attachments.*.max' => 'Cada archivo no puede superar los 5 MB.',
            'attachments.*.mimes' => 'Formato de archivo no permitido.',
        ]);

        // Transacción: si falla un adjunto, no queda un ticket a medias
        DB::beginTransaction();

        try {
            // 1. Crear el ticket con el usuario autenticado y los valores por defecto
            $ticket = Ticket::create([
                'user_id' => auth()->id(),
                'assigned_to' => null,   // sin asignar
                'subject' => $validated['subject'],
                'description' => $validated['description'],
                'status_id' => 1,        // estado inicial
                'priority_id' => 1,      // prioridad por defecto
                'category_id' => $validated['category_id'],
            ]);

            // 2. Guardar los adjuntos, si los hay
            if ($request->hasFile('attachments')) {
                $folder = "attachments/tickets/{$ticket->id}";

                foreach ($request->file('attachments') as $file) {
                    // Nombre original saneado + timestamp para evitar colisiones
                    $original = pathinfo($file->getClientOriginalName(), PATHINFO_FILENAME);
                    $filename = Str::slug($original) . '_' . time() . '.' . $file->getClientOriginalExtension();

                    $path = $file->storeAs($folder, $filename, 'public');

                    AttachmentTicket::create([
                        'ticket_id' => $ticket->id,
                        'file_path' => $path,
                        'file_type' => $file->getClientOriginalExtension(),
                    ]);
                }
            }

            DB::commit();

            if ($request->ajax()) {
                return response()->json(['ok' => true, 'id' => $ticket->id]);
            }

            return redirect()
                ->route('tickets.index')
                ->with('success', "Tu ticket #{$ticket->id} se registró correctamente.");

        } catch (\Exception $e) {
            DB::rollBack();
            Log::error('Error en TicketController@store: ' . $e->getMessage());

            if ($request->ajax()) {
                return response()->json(['message' => 'Error al registrar el ticket.'], 500);
            }

            return redirect()
                ->back()
                ->withInput()
                ->with('error', 'Ocurrió un error al registrar el ticket. Inténtalo nuevamente.');
        }
    }
}
