<?php

namespace App\Models;

use Illuminate\Database\Eloquent\Factories\HasFactory;
use Illuminate\Database\Eloquent\Model;

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
