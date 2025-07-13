<?php

namespace App\Models;

use Illuminate\Database\Eloquent\Factories\HasFactory;
use Illuminate\Database\Eloquent\Model;

class Stop extends Model
{
    use HasFactory;

    protected $table = 'stops';

    protected $fillable = [
        'name',
        'line_id',
        'stop_order',
    ];

    protected $casts = [
        'created_at' => 'datetime',
        'updated_at' => 'datetime',
    ];

    public function line()
    {
        return $this->belongsTo(Line::class);
    }

    // Éviter le conflit avec le mot-clé 'order' de Laravel
    public function scopeOrdered($query)
    {
        return $query->orderBy('stop_order', 'asc');
    }
}
