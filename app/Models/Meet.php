<?php

namespace App\Models;

use Illuminate\Database\Eloquent\Factories\HasFactory;
use Illuminate\Database\Eloquent\Model;




 /**
 * @OA\Schema(
 *     schema="Meet",
 *     title="Meet",
 *     description="Modèle de réunion",
 *     @OA\Property(property="id_candidat", type="integer", description="ID du candidat"),
 *     @OA\Property(property="titre", type="string", description="Titre de la réunion"),
 *     @OA\Property(property="description", type="string", description="Description de la réunion"),
 *     @OA\Property(property="url_media", type="string", description="URL du média associé à la réunion"),
 *     @OA\Property(property="created_at", type="string", format="date-time", description="Date et heure de création", readOnly="true", nullable=true),
 *     @OA\Property(property="updated_at", type="string", format="date-time", description="Date et heure de mise à jour", readOnly="true", nullable=true)
 * )
 */
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
