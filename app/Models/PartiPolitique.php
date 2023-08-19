<?php

namespace App\Models;

use Illuminate\Database\Eloquent\Factories\HasFactory;
use Illuminate\Database\Eloquent\Model;

/**
 * @OA\Schema(
 *     schema="PartiPolitique",
 *     title="PartiPolitique",
 *     description="Modèle de parti politique",
 *     @OA\Property(property="nom", type="string", description="Nom du parti politique"),
 *     @OA\Property(property="logo", type="string", format="binary", description="Logo du parti politique (type photo)"),
 *     @OA\Property(property="description", type="string", description="Description du parti politique"),
 *     @OA\Property(property="created_at", type="string", format="date-time", description="Date et heure de création", readOnly="true", nullable=true),
 *     @OA\Property(property="updated_at", type="string", format="date-time", description="Date et heure de mise à jour", readOnly="true", nullable=true)
 * )
 */
class PartiPolitique extends Model
{
    use HasFactory;

    protected $table = 'parti_politiques';

    protected $fillable = [
        'nom',
        'logo',
        'description',
        'created_at',
        'updated_at',
    ];

    public function candidats()
    {
        return $this->hasMany(Candidat::class, 'pt_id');
    }

    public function scopeSearch($query, $val)
    {
        return $query
            ->where('nom', 'like', '%' . $val . '%')
            ->orWhere('description', 'like', '%' . $val . '%');
    }
}
