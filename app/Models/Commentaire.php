<?php

namespace App\Models;

use Illuminate\Database\Eloquent\Factories\HasFactory;
use Illuminate\Database\Eloquent\Model;


/**
 * @OA\Schema(
 *     schema="Commentaire",
 *     title="Commentaire",
 *     description="Commentaire model",
 *     @OA\Property(property="id_user", type="integer", description="User ID associated with the comment"),
 *     @OA\Property(property="id_post", type="integer", description="ID of the post associated with the comment"),
 *     @OA\Property(property="commentaire", type="string", description="Content of the comment"),
 *     @OA\Property(property="created_at", type="string", format="date-time", description="Comment creation date and time", readOnly="true"),
 *     @OA\Property(property="updated_at", type="string", format="date-time", description="Comment last update date and time", readOnly="true")
 * )
 */
class Commentaire extends Model
{
    use HasFactory;

    protected $table = 'commentaires';

    protected $fillable = [
        'id_user',
        'id_post',
        'commentaire',
        'created_at',
        'updated_at',
    ];

    public function user()
    {
        return $this->belongsTo(User::class, 'id_user');
    }

    public function post()
    {
        return $this->belongsTo(Post::class, 'id_post');
    }

    public function response(){
        return $this->hasMany(CommentaireReplique::class, 'id_commentaire');}

    public function scopeSearch($query, $val)
    {
        return $query
            ->where('commentaire', 'like', '%' . $val . '%');
    }


    public function scopeSort($query, $val)
    {
        if ($val == 'recent') {
            return $query->orderBy('created_at', 'desc');
        } else if ($val == 'old') {
            return $query->orderBy('created_at', 'asc');
        }
    }


    public function scopeFilter($query, $val)
    {
        return $query
            ->where('id_post', $val);
    }
}
