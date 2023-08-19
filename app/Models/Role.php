<?php

namespace App\Models;

use Illuminate\Database\Eloquent\Factories\HasFactory;
use Illuminate\Database\Eloquent\Model;

/**
 * @OA\Schema(
 *     schema="Role",
 *     title="Role",
 *     description="Modèle de rôle d'utilisateur",
 *     @OA\Property(property="name", type="string", description="Nom du rôle"),
 *     @OA\Property(property="description", type="string", description="Description du rôle"),
 * )
 */
class Role extends Model
{
    use HasFactory;

    protected $fillable = [
        'name',
        'description'
    ];



}
