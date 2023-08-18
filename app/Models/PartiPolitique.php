<?php

namespace App\Models;

use Illuminate\Database\Eloquent\Factories\HasFactory;
use Illuminate\Database\Eloquent\Model;

class PartiPolitique extends Model
{
    use HasFactory;

    protected $table = 'parti_politiques';

    protected $fillable = [
        'nom',
        'logo',
        'description',
        'created_at',
        'updated_at',
    ];

    public function candidats()
    {
        return $this->hasMany(Candidat::class, 'pt_id');
    }

    public function scopeSearch($query, $val)
    {
        return $query
            ->where('nom', 'like', '%' . $val . '%')
            ->orWhere('description', 'like', '%' . $val . '%');
    }
}
