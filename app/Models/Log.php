<?php

namespace App\Models;

use Database\Factories\TicketLogFactory;
use Illuminate\Database\Eloquent\Factories\HasFactory;
use Illuminate\Database\Eloquent\Model;
use Illuminate\Database\Eloquent\Relations\BelongsTo;

class Log extends Model
{
    use HasFactory;

    protected $table = 'logs';

    const UPDATED_AT = null; // la tabla solo tiene created_at

    protected $fillable = [
        'ticket_id',
        'action',
        'user_id',
    ];

    protected static function newFactory(): TicketLogFactory
    {
        return TicketLogFactory::new();
    }


    public function ticket(): BelongsTo
    {
        return $this->belongsTo(Ticket::class);
    }

    public function user(): BelongsTo
    {
        return $this->belongsTo(User::class);
    }
}
