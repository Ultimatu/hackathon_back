<?php

namespace App\Models;

use Illuminate\Database\Eloquent\Factories\HasFactory;
use Illuminate\Database\Eloquent\Model;

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
        return $query
            ->where('bio', 'like', '%' . $val . '%');
    }

    public function scopeFilter($query, $val)
    {
        return $query
            ->where('pt_id', $val);
    }
    
}
