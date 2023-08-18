<?php

namespace App\Models;

use Illuminate\Database\Eloquent\Factories\HasFactory;
use Illuminate\Database\Eloquent\Model;

class Vote extends Model
{
    use HasFactory;

    protected $table = 'votes';

    protected $fillable = [
        'election_id',
        'voter_id',
        'candidat_id',
        'created_at',
        'updated_at',
    ];

    public function election()
    {
        return $this->belongsTo(Election::class, 'election_id');
    }


    public function candidat()
    {
        return $this->belongsTo(Candidat::class, 'candidat_id');
    }

    public function voter()
    {
        return $this->belongsTo(User::class, 'voter_id');
    }


    public function scopeFilter($query, $val)
    {
        return $query
            ->where('election_id', $val);
    }

    public function scopeFilterByCandidat($query, $val)
    {
        return $query
            ->where('candidat_id', $val);
    }

    public function scopeFilterByVoter($query, $val)
    {
        return $query
            ->where('voter_id', $val);
    }

}
