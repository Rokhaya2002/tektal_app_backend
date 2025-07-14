<?php

namespace App\Models;

use Illuminate\Database\Eloquent\Factories\HasFactory;
use Illuminate\Database\Eloquent\Model;

class Line extends Model
{
    use HasFactory;

    protected $fillable = [
        'name',
        'departure',
        'destination',
    ];

    /**
     * Relation many-to-many avec les arrêts (avec ordre)
     */
    public function stops()
    {
        return $this->belongsToMany(Stop::class, 'line_stop')
            ->withPivot('stop_order')
            ->orderBy('line_stop.stop_order');
    }
}
