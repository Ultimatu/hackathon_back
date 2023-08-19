<?php

namespace App\Models;

use Illuminate\Database\Eloquent\Factories\HasFactory;
use Illuminate\Database\Eloquent\Model;


/**
 * @OA\Schema(
 *     schema="Election",
 *     title="Élection",
 *     description="Modèle d'élection",
 *     @OA\Property(property="description", type="string", description="Description de l'élection"),
 *     @OA\Property(property="nom", type="string", description="Nom de l'élection"),
 *     @OA\Property(property="duration", type="string", description="Durée de l'élection en jours"),
 *     @OA\Property(property="image_url", type="string", description="URL de l'image de l'élection"),
 *     @OA\Property(property="banner_url", type="string", description="URL de la bannière de l'élection"),
 *     @OA\Property(property="date_debut", type="string", format="date", description="Date de début de l'élection"),
 *     @OA\Property(property="date_fin", type="string", format="date", description="Date de fin de l'élection"),
 *     @OA\Property(property="created_at", type="string", format="date-time", description="Date et heure de création", readOnly="true", nullable=true),
 *     @OA\Property(property="updated_at", type="string", format="date-time", description="Date et heure de mise à jour", readOnly="true", nullable=true)
 * )
 */
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
