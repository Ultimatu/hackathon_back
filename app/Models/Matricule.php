<?php

namespace App\Models;

use Illuminate\Database\Eloquent\Factories\HasFactory;
use Illuminate\Database\Eloquent\Model;

class Matricule extends Model
{
    use HasFactory;

    protected $table = 'matricule_candidats';

    protected $fillable = [
        'matricule',
        'created_at',
        'updated_at',
    ];

    
}
