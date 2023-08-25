<?php

namespace App\Models;

use Illuminate\Database\Eloquent\Factories\HasFactory;
use Illuminate\Database\Eloquent\Model;


/**
 * @OA\Schema(
 *     schema="Post",
 *     title="Post",
 *     description="Modèle de publication (post)",
 *     @OA\Property(property="id_candidat", type="integer", description="ID du candidat lié au post"),
 *     @OA\Property(property="titre", type="string", description="Titre de la publication"),
 *     @OA\Property(property="description", type="string", description="Description de la publication"),
 *     @OA\Property(property="url_media", type="string", description="URL du média associé à la publication"),
 *     @OA\Property(property="created_at", type="string", format="date-time", description="Date et heure de création", readOnly="true", nullable=true),
 *     @OA\Property(property="updated_at", type="string", format="date-time", description="Date et heure de mise à jour", readOnly="true", nullable=true)
 * )
 */
class Post extends Model
{
    use HasFactory;

    protected  $table = 'posts';

    protected $fillable = [
        'id_candidat',
        'titre',
        'description',
        'url_media',
        'created_at',
        'updated_at',
    ];

    public function candidat()
    {
        return $this->belongsTo(Candidat::class, 'id_candidat');
    }

    public function likes()
    {
        return $this->hasMany(Likes::class, 'id_post');
    }

    public function scopeSearch($query, $val)
    {
        return $query
            ->where('titre', 'like', '%' . $val . '%')->orWhere('description', 'like', '%' . $val . '%');
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
            return $query->orderBy('titre', 'asc');
        } else if ($val == 'name_desc') {
            return $query->orderBy('titre', 'desc');
        }

    }

}
