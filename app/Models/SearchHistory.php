<?php

namespace App\Models;

use Illuminate\Database\Eloquent\Factories\HasFactory;
use Illuminate\Database\Eloquent\Model;

class SearchHistory extends Model
{
    use HasFactory;

    protected $table = 'search_history';

    protected $fillable = [
        'from',
        'to',
        'user_id',
        'count',
        'last_searched_at'
    ];

    protected $casts = [
        'last_searched_at' => 'datetime',
        'count' => 'integer',
        'user_id' => 'integer'
    ];

    // Relation avec l'utilisateur
    public function user()
    {
        return $this->belongsTo(User::class);
    }

    // Méthode pour incrémenter le compteur et mettre à jour la date
    public function incrementSearch()
    {
        $this->increment('count');
        $this->update(['last_searched_at' => now()]);
    }

    // Scope pour obtenir les recherches les plus populaires
    public function scopePopular($query, $limit = 10)
    {
        return $query->orderBy('count', 'desc')->limit($limit);
    }

    // Scope pour obtenir les recherches récentes
    public function scopeRecent($query, $limit = 10)
    {
        return $query->orderBy('last_searched_at', 'desc')->limit($limit);
    }

    // Méthode pour obtenir les suggestions populaires
    public static function getPopularSuggestions($limit = 10)
    {
        $popular = self::popular($limit)->get();

        $suggestions = [];

        // Ajouter les destinations populaires
        foreach ($popular as $search) {
            $suggestions[] = $search->to;
            $suggestions[] = $search->from;
        }

        // Retourner les suggestions uniques
        return array_unique($suggestions);
    }
}
