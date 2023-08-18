<?php

namespace App\Models;

use Illuminate\Database\Eloquent\Factories\HasFactory;
use Illuminate\Database\Eloquent\Model;

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
