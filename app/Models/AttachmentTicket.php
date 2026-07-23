<?php

namespace App\Models;

use Illuminate\Database\Eloquent\Factories\HasFactory;
use Illuminate\Database\Eloquent\Model;
use Illuminate\Database\Eloquent\Relations\BelongsTo;

class AttachmentTicket extends Model
{
    use HasFactory;
    protected $table = 'attachment_tickets';

    protected $fillable = [
        'ticket_id',
        'file_path',
        'file_type',
    ];

    public function ticket(): BelongsTo
    {
        return $this->belongsTo(Ticket::class);
    }
}
