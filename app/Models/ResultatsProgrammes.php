<?php

namespace App\Models;

use Illuminate\Database\Eloquent\Factories\HasFactory;
use Illuminate\Database\Eloquent\Model;


/**
 * @property integer $id
 * @property integer $id_candidat
 * @property string $titre
 * @property string $description
 * @property string $url_media
 * @property string $created_at
 * @property string $updated_at
 * @property Candidat $candidat
 * @property ResultatsProgrammes[] $resultats
 */

/**
 * @OA\Schema(
 *    schema="ResultatProgramme",
 *   title="Resultat Programme",
 *  description="Modèle de résultat de programme",
 * @OA\Property(property="id_programme", type="integer", description="ID du programme associé au résultat"),
 * @OA\Property(property="id_user", type="integer", description="ID de l'utilisateur lié au résultat"),
 * @OA\Property(property="avis", type="string", description="Avis de l'utilisateur sur le programme"),
 * @OA\Property(property="created_at", type="string", format="date-time", description="Date et heure de création", readOnly="true", nullable=true),
 * @OA\Property(property="updated_at", type="string", format="date-time", description="Date et heure de mise à jour", readOnly="true", nullable=true)
 * )
 */

class ResultatsProgrammes extends Model
{
    use HasFactory;

    protected $table = 'programmes_resultats';

    protected $fillable = [
        'id_programme',
        'id_user',
        'avis',
    ];

    public function programme()
    {
        return $this->belongsTo(Programmes::class, 'id_programme');
    }

    public function user()
    {
        return $this->belongsTo(User::class, 'id_user');
    }

    public function getAvisAttribute($value)
    {
        return $value ? 'like' : 'dislike';
    }

    public function setAvisAttribute($value)
    {
        $this->attributes['avis'] = $value === 'like';
    }

    public function scopeLike($query)
    {
        return $query->where('avis', true);
    }

    public function scopeDislike($query)
    {
        return $query->where('avis', false);
    }



}
