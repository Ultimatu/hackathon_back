<?php

namespace App\Models;

use Illuminate\Database\Eloquent\Factories\HasFactory;
use Illuminate\Database\Eloquent\Model;

/**
 * @OA\Schema(
 *     schema="Sondage",
 *     title="Sondage",
 *     description="Model for representing a survey.",
 *     @OA\Property(property="id", type="integer", format="int64", description="Sondage ID"),
 *     @OA\Property(property="id_user", type="integer", description="User ID who created the survey"),
 *     @OA\Property(property="titre", type="string", description="Title of the survey"),
 *     @OA\Property(property="description", type="string", description="Description of the survey"),
 *     @OA\Property(property="date_debut", type="string", format="date", description="Start date of the survey"),
 *     @OA\Property(property="date_fin", type="string", format="date", description="End date of the survey"),
 *     @OA\Property(property="url_media", type="string", description="URL of the media associated with the survey"),
 *     @OA\Property(property="type", type="string", description="Type of the survey"),
 *     @OA\Property(property="status", type="string", description="Status of the survey"),
 *     @OA\Property(property="commune", type="string", description="Commune associated with the survey"),
 *     @OA\Property(property="created_at", type="string", format="date-time", description="Creation date"),
 *     @OA\Property(property="updated_at", type="string", format="date-time", description="Last update date")
 * )
 */
class Sondage extends Model
{
    use HasFactory;

    protected $table = 'sondages';

    protected $fillable = [
        'id_user',
        'titre',
        'description',
        'date_debut',
        'date_fin',
        'url_media',
        'id_type_sondage',
        'status',
        'commune',
        'created_at',
        'updated_at',
    ];


    public function user()
    {
        return $this->belongsTo(User::class, 'id_user');
    }

    public function type()
    {
        return $this->belongsTo(TypeSondage::class, 'id_type_sondage');
    }

    public function resultatsSondages()
    {
        return $this->hasMany(ResultatSondage::class, 'id_sondage', 'id');
    }

    public function scopeSearch($query, $val)
    {
        return $query
            ->where('titre', 'like', '%' . $val . '%')
            ->orWhere('description', 'like', '%' . $val . '%')
            ->orWhere('date_debut', 'like', '%' . $val . '%')
            ->orWhere('date_fin', 'like', '%' . $val . '%');
    }

    public function scopeFilter($query, $val)
    {
        return $query
            ->where('id_user', $val);
    }
}
