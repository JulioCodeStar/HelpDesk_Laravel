<?php

namespace App\Models;

use Illuminate\Database\Eloquent\Factories\HasFactory;
use Illuminate\Database\Eloquent\Model;
use Illuminate\Database\Eloquent\Relations\HasMany;

class Priority extends Model
{
    use HasFactory;

    protected $table = 'priority'; // evita que busque "priorities"

    protected $fillable = ['name', 'response_time_minutes'];

    public function tickets(): HasMany
    {
        return $this->hasMany(Ticket::class);
    }
}
