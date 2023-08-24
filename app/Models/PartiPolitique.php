<?php

namespace App\Models;

use Illuminate\Database\Eloquent\Factories\HasFactory;
use Illuminate\Database\Eloquent\Model;

/**
 * @OA\Schema(
 *     schema="Politique",
 *     title="Politique",
 *
 *    @OA\Property(property="nom", type="string", description="nom du parti politique", example="RHDP"),
 *   @OA\Property(property="logo", type="string", description="logo du parti politique", example="logo.png"),
 * @OA\Property(property="description", type="string", description="description du parti politique", example="description du parti politique"),
 * )
 * )
 *
 *
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
