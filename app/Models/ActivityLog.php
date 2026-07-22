<?php

namespace App\Models;

use Illuminate\Database\Eloquent\Factories\HasFactory;
use Illuminate\Database\Eloquent\Model;
use Illuminate\Database\Eloquent\Relations\BelongsTo;
use Illuminate\Database\Eloquent\Relations\MorphTo;

class ActivityLog extends Model
{
    use HasFactory;
    const UPDATED_AT = null; // solo created_at

    protected $fillable = [
        'user_id',
        'action',
        'auditable_type',
        'auditable_id',
        'changes',
    ];

    protected $casts = [
        'changes' => 'array', // el JSON se lee/escribe como array PHP
    ];

    // Quién hizo la acción
    public function user(): BelongsTo
    {
        return $this->belongsTo(User::class);
    }

    // El registro auditado (Category, Department, etc.)
    public function auditable(): MorphTo
    {
        return $this->morphTo();
    }
}
