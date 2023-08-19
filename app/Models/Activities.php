<?php

namespace App\Models;

use Illuminate\Database\Eloquent\Factories\HasFactory;
use Illuminate\Database\Eloquent\Model;


/**
 * @OA\Schema(
 *     schema="Activity",
 *     title="Activité d'un candidat",
 *     description="Model for a Activity",
 *     @OA\Property(property="id_candidat", type="integer"),
 *     @OA\Property(property="description", type="string"),
 *     @OA\Property(property="nom", type="string"),
 *     @OA\Property(property="date_debut", type="string", format="date"),
 *     @OA\Property(property="date_fin", type="string", format="date"),
 *     @OA\Property(property="created_at", type="string", format="date-time"),
 *     @OA\Property(property="updated_at", type="string", format="date-time"),
 * )
 */

class Activities extends Model
{
    use HasFactory;

    protected $table = 'activities';

    protected $fillable = [
        'id_candidat',
        'description',
        'nom',
        'date_debut',
        'date_fin',
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
            ->where('nom', 'like', '%' . $val . '%')
            ->orWhere('description', 'like', '%' . $val . '%')
            ->orWhere('date_debut', 'like', '%' . $val . '%')
            ->orWhere('date_fin', 'like', '%' . $val . '%');
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
        } else {
            return $query->orderBy('created_at', 'desc');
        }
    }



}
