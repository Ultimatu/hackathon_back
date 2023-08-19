<?php

namespace App\Models;

use Illuminate\Database\Eloquent\Factories\HasFactory;
use Illuminate\Database\Eloquent\Model;

/**
 * @OA\Schema(
 *     schema="ElectionParticipant",
 *     title="Participant d'Élection",
 *     description="Modèle de participant à une élection",
 *     @OA\Property(property="id_election", type="integer", description="ID de l'élection"),
 *     @OA\Property(property="id_candidat", type="integer", description="ID du candidat participant"),
 *     @OA\Property(property="created_at", type="string", format="date-time", description="Date et heure de création", readOnly="true", nullable=true),
 *     @OA\Property(property="updated_at", type="string", format="date-time", description="Date et heure de mise à jour", readOnly="true", nullable=true)
 * )
 */
class ElectionParticipant extends Model
{
    use HasFactory;

    protected $table = 'participant_elections';

    protected $fillable = [
        'id_election',
        'id_candidat',
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

    public function scopeSort($query, $val)
    {
        if ($val == 'recent') {
            return $query->orderBy('created_at', 'desc');
        } else if ($val == 'old') {
            return $query->orderBy('created_at', 'asc');
        }
    }

    public function scopeSearch($query, $val)
    {
        return $query
            ->where('id_candidat', $val);
    }
}


