<?php

namespace App\Models;

use Illuminate\Database\Eloquent\Factories\HasFactory;
use Illuminate\Database\Eloquent\Model;

/**
 * @OA\Schema(
 *     schema="ResultatSondage",
 *     title="ResultatSondage",
 *     description="Modèle de résultat de sondage",
 *     @OA\Property(property="id_sondage", type="integer", description="ID du sondage associé au résultat"),
 *     @OA\Property(property="id_user", type="integer", description="ID de l'utilisateur ayant participé au sondage"),
 *     @OA\Property(property="choix", type="string", description="Choix de l'utilisateur dans le sondage"),
 *    @OA\Property(property="avis", type="string", description="Avis de l'utilisateur sur le sondage"),
 *     @OA\Property(property="created_at", type="string", format="date-time", description="Date et heure de création", readOnly="true", nullable=true),
 *     @OA\Property(property="updated_at", type="string", format="date-time", description="Date et heure de mise à jour", readOnly="true", nullable=true)
 * )
 */
class ResultatSondage extends Model
{
    use HasFactory;

    protected $table = 'resultats_sondages';


    protected $fillable = [
        'id_sondage',
        'id_user',
        'avis',
        'choix',
        'created_at',
        'updated_at',
    ];

    public function sondage()
    {
        return $this->belongsTo(Sondage::class, 'id_sondage');
    }

    public function user()
    {
        return $this->belongsTo(User::class, 'id_user');
    }

    public function scopeFilter($query, $val)
    {
        return $query
            ->where('id_sondage', $val);
    }
}
