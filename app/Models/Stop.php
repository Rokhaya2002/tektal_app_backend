<?php

namespace App\Models;

use Illuminate\Database\Eloquent\Factories\HasFactory;
use Illuminate\Database\Eloquent\Model;

class Stop extends Model
{
    use HasFactory;

    protected $fillable = [
        'name',
    ];

    /**
     * Relation many-to-many avec les lignes
     */
    public function lines()
    {
        return $this->belongsToMany(Line::class, 'line_stop')
            ->withPivot('stop_order')
            ->orderBy('pivot_stop_order');
    }
}
