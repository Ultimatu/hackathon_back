<?php

namespace App\Models;

use Illuminate\Database\Eloquent\Factories\HasFactory;
use Illuminate\Database\Eloquent\Model;

class Meet extends Model
{
    use HasFactory;

    protected $table = 'meets';

    protected $fillable = [
        'id_candidat',
        'titre',
        'description',
        'url_media',
        'created_at',
        'updated_at',
    ];

    public function candidat()
    {
        return $this->belongsTo(Candidat::class, 'id_candidat');
    }

    public function scopeSearch($query, $val)
    {
        return $query
            ->where('titre', 'like', '%' . $val . '%');
    }

    public function scopeFilter($query, $val)
    {
        return $query
            ->where('id_candidat', $val);
    }

    public function scopeSort($query, $val)
    {
        if ($val == 'recent') {
            return $query->orderBy('created_at', 'desc');
        } else if ($val == 'old') {
            return $query->orderBy('created_at', 'asc');
        } else if ($val == 'name') {
            return $query->orderBy('titre', 'asc');
        } else if ($val == 'name_desc') {
            return $query->orderBy('titre', 'desc');
        }
    }

    public function scopeFilterByDate($query, $val)
    {
        return $query
            ->where('date_debut', $val);
    }

    public function scopeFilterByDateFin($query, $val)
    {
        return $query
            ->where('date_fin', $val);
    }

    public function scopeFilterByDateDebut($query, $val)
    {
        return $query
            ->where('date_debut', $val);
    }

    public function scopeFilterByDateFinDebut($query, $val)
    {
        return $query
            ->where('date_fin', $val);
    }
}
