<?php

namespace App\Models;

use Illuminate\Database\Eloquent\Factories\HasFactory;
use Illuminate\Database\Eloquent\Model;

class ResultatSondage extends Model
{
    use HasFactory;

    protected $table = 'resultats_sondages';

    protected $fillable = [
        'id_sondage',
        'id_user',
        'choix',
        'created_at',
        'updated_at',
    ];

    public function sondage()
    {
        return $this->belongsTo(Sondage::class, 'id_sondage');
    }

    public function user()
    {
        return $this->belongsTo(User::class, 'id_user');
    }

    public function scopeFilter($query, $val)
    {
        return $query
            ->where('id_sondage', $val);
    }
}
