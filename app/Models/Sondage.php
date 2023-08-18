<?php

namespace App\Models;

use Illuminate\Database\Eloquent\Factories\HasFactory;
use Illuminate\Database\Eloquent\Model;

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
        'type',
        'status',
        'commune',
        'created_at',
        'updated_at',
    ];


    public function user()
    {
        return $this->belongsTo(User::class, 'id_user');
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
