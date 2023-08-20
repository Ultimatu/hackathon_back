<?php

namespace App\Models;

use Illuminate\Database\Eloquent\Factories\HasFactory;
use Illuminate\Database\Eloquent\Model;


/**
 * @OA\Schema(
 *     schema="Candidat",
 *     title="Candidat",
 *     description="Model for a Candidat",
 *     @OA\Property(property="user_id", type="integer"),
 *     @OA\Property(property="pt_id", type="integer"),
 *     @OA\Property(property="bio", type="string"),
 *     @OA\Property(property="created_at", type="string", format="date-time"),
 *     @OA\Property(property="updated_at", type="string", format="date-time"),
 * )
 */

class Candidat extends Model
{
    use HasFactory;


    protected $table = 'candidats';

    protected $fillable = [
        'user_id',
        'pt_id',
        'bio',
        'created_at',
        'updated_at',
    ];

    public function user()
    {
        return $this->belongsTo(User::class, 'user_id');
    }

    public function partiPolitique()
    {
        return $this->belongsTo(PartiPolitique::class, 'pt_id');
    }

    public function activites()
    {
        return $this->hasMany(Activities::class, 'id_candidat');
    }

    public function scopeSearch($query, $val)
    {
        //bio et user datas
        return $query
            ->where('bio', 'like', '%' . $val . '%')
            ->orWhereHas('user', function ($q) use ($val) {
                $q->where('nom', 'like', '%' . $val . '%')
                    ->orWhere('prenom', 'like', '%' . $val . '%');
            });
    }

    public function scopeFilter($query, $val)
    {
        return $query
            ->where('pt_id', $val);
    }

}
