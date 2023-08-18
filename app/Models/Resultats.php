<?php

namespace App\Models;

use Illuminate\Database\Eloquent\Factories\HasFactory;
use Illuminate\Database\Eloquent\Model;

class Resultats extends Model
{
    use HasFactory;

    protected $table = 'resultats';

    protected $fillable = [
        'id_election',
        'id_candidat',
        'nb_votes',
        'rang',
        'created_at',
        'updated_at',
    ];

    public function election()
    {
        return $this->belongsTo(Elections::class, 'id_election');
    }


    public function candidat()
    {
        return $this->belongsTo(Candidat::class, 'id_candidat');
    }


    public function scopeFilter($query, $val)
    {
        return $query
            ->where('id_election', $val);
    }
}
