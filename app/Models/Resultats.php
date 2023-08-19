<?php

namespace App\Models;

use Illuminate\Database\Eloquent\Factories\HasFactory;
use Illuminate\Database\Eloquent\Model;

/**
 * @OA\Schema(
 *     schema="Resultat Election",
 *     title="Resultat",
 *     description="Modèle de résultat d'élection",
 *     @OA\Property(property="id_election", type="integer", description="ID de l'élection associée au résultat"),
 *     @OA\Property(property="id_candidat", type="integer", description="ID du candidat lié au résultat"),
 *     @OA\Property(property="nb_votes", type="integer", description="Nombre de votes obtenus par le candidat"),
 *     @OA\Property(property="rang", type="integer", description="Rang du candidat dans les résultats"),
 *     @OA\Property(property="created_at", type="string", format="date-time", description="Date et heure de création", readOnly="true", nullable=true),
 *     @OA\Property(property="updated_at", type="string", format="date-time", description="Date et heure de mise à jour", readOnly="true", nullable=true)
 * )
 */
class Resultats extends Model
{
    use HasFactory;

    protected $table = 'resultats';

    protected $fillable = [
        'id_election',
        'id_candidat',
        'nb_votes',
        'rang',
        'created_at',
        'updated_at',
    ];

    public function election()
    {
        return $this->belongsTo(Elections::class, 'id_election');
    }


    public function candidat()
    {
        return $this->belongsTo(Candidat::class, 'id_candidat');
    }


    public function scopeFilter($query, $val)
    {
        return $query
            ->where('id_election', $val);
    }


    public function scopeFilterByCandidat($query, $val)
    {
        return $query
            ->where('id_candidat', $val);
    }


    public function scopeSearch($query, $val)
    {
        return $query
            ->where('id_candidat', $val);
    }
}
