<?php

namespace App\Models;

use Illuminate\Database\Eloquent\Factories\HasFactory;
use Illuminate\Database\Eloquent\Model;

class Elections extends Model
{
    use HasFactory;

    protected $table = 'elections';

    protected $fillable = [
        'description',
        'nom',
        'duration',
        'image_url',
        'banner_url',
        'date_debut',
        'date_fin',
        'created_at',
        'updated_at',

    ];

    public function participants()
    {
        return $this->hasMany(ElectionParticipant::class, 'id_election');
    }

    public function scopeSearch($query, $val)
    {
        return $query
            ->where('nom', 'like', '%' . $val . '%')
            ->orWhere('description', 'like', '%' . $val . '%')
            ->orWhere('date_debut', 'like', '%' . $val . '%')
            ->orWhere('date_fin', 'like', '%' . $val . '%');
    }

    public function scopeSort($query, $val)
    {
        if ($val == 'recent') {
            return $query->orderBy('created_at', 'desc');
        } else if ($val == 'old') {
            return $query->orderBy('created_at', 'asc');
        } else if ($val == 'name') {
            return $query->orderBy('nom', 'asc');
        } else if ($val == 'name_desc') {
            return $query->orderBy('nom', 'desc');
        } else if ($val == 'date_debut') {
            return $query->orderBy('date_debut', 'asc');
        } else if ($val == 'date_debut_desc') {
            return $query->orderBy('date_debut', 'desc');
        } else if ($val == 'date_fin') {
            return $query->orderBy('date_fin', 'asc');
        } else if ($val == 'date_fin_desc') {
            return $query->orderBy('date_fin', 'desc');
        }
    }
}
