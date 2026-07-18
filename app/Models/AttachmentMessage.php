<?php

namespace App\Models;

use Illuminate\Database\Eloquent\Factories\HasFactory;
use Illuminate\Database\Eloquent\Model;
use Illuminate\Database\Eloquent\Relations\BelongsTo;

class AttachmentMessage extends Model
{
    use HasFactory;

    protected $table = 'attachments_messages';

    protected $fillable = [
        'ticket_message_id',
        'file_path',
        'file_type',
    ];

    public function ticketMessage(): BelongsTo
    {
        return $this->belongsTo(TicketMessage::class);
    }
}
