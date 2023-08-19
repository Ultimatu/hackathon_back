<?php

namespace App\Models;

use Illuminate\Database\Eloquent\Factories\HasFactory;
use Illuminate\Database\Eloquent\Model;

/**
 * @OA\Schema(
 *     schema="CommentaireReplique",
 *     title="CommentaireReplique",
 *     description="CommentaireReplique model",
 *     @OA\Property(property="id_commentaire", type="integer", description="ID of the original comment"),
 *     @OA\Property(property="id_user", type="integer", description="User ID associated with the reply"),
 *     @OA\Property(property="reponse", type="string", description="Content of the reply"),
 *     @OA\Property(property="created_at", type="string", format="date-time", description="Reply creation date and time", readOnly="true"),
 *     @OA\Property(property="updated_at", type="string", format="date-time", description="Reply last update date and time", readOnly="true")
 * )
 */
class CommentaireReplique extends Model
{
    use HasFactory;


    protected $table = 'commentaire_responses';


    protected $fillable = [
        'id_commentaire',
        'id_user',
        'reponse',
        'created_at',
        'updated_at',
    ];


    public function commentaire()
    {
        return $this->belongsTo(Commentaire::class, 'id_commentaire');
    }


    public function user()
    {
        return $this->belongsTo(User::class, 'id_user');
    }


    public function scopeSearch($query, $val)
    {
        return $query
            ->where('reponse', 'like', '%' . $val . '%');
    }


    public function scopeFilter($query, $val)
    {
        return $query
            ->where('id_commentaire', $val);
    }


    public function scopeSort($query, $val)
    {
        if ($val == 'recent') {
            return $query->orderBy('created_at', 'desc');
        } else if ($val == 'old') {
            return $query->orderBy('created_at', 'asc');
        }
    }
}
