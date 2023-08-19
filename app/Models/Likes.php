<?php

namespace App\Models;

use Illuminate\Database\Eloquent\Factories\HasFactory;
use Illuminate\Database\Eloquent\Model;


/**
 * @OA\Schema(
 *     schema="Like",
 *     title="Like des posts",
 *     description="Modèle de like",
 *     @OA\Property(property="id_user", type="integer", description="ID de l'utilisateur"),
 *     @OA\Property(property="id_post", type="integer", description="ID du post liké"),
 *     @OA\Property(property="created_at", type="string", format="date-time", description="Date et heure de création", readOnly="true", nullable=true),
 *     @OA\Property(property="updated_at", type="string", format="date-time", description="Date et heure de mise à jour", readOnly="true", nullable=true)
 * )
 */
class Likes extends Model
{
    use HasFactory;

    protected $table = 'likes';

    protected $fillable = [
        'id_user',
        'id_post',
        'created_at',
        'updated_at',
    ];

    public function user()
    {
        return $this->belongsTo(User::class, 'id_user');
    }

    public function posts()
    {
        return $this->belongsTo(Post::class, 'id_post');
    }

    public function scopeSearch($query, $val)
    {
        return $query
            ->where('id_post', $val);
    }

    public function scopeFilter($query, $val)
    {
        return $query
            ->where('id_user', $val);
    }


}
