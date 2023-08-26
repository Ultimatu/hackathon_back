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
 *     schema="Programme",
 *     title="Programme",
 *     description="Modèle de programme",
 *     @OA\Property(property="id_candidat", type="integer", description="ID du candidat lié au programme"),
 *     @OA\Property(property="titre", type="string", description="Titre du programme"),
 *     @OA\Property(property="description", type="string", description="Description du programme"),
 *     @OA\Property(property="url_media", type="string", description="URL du média associé au programme"),
 *     @OA\Property(property="created_at", type="string", format="date-time", description="Date et heure de création", readOnly="true", nullable=true),
 *     @OA\Property(property="updated_at", type="string", format="date-time", description="Date et heure de mise à jour", readOnly="true", nullable=true)

 * )
 */
class Programmes extends Model
{
    use HasFactory;

    protected $table = 'programmes';

    protected $fillable = [
        'id_candidat',
        'titre',
        'description',
        'url_media',
    ];

    public function candidat()
    {
        return $this->belongsTo(Candidat::class, 'id_candidat');
    }

    public function resultatsProgrammes()
    {
        return $this->hasMany(ResultatsProgrammes::class, 'id_programme');
    }
}
