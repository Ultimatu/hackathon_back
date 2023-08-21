<?php

namespace App\Models;

use Illuminate\Database\Eloquent\Factories\HasFactory;
use Illuminate\Database\Eloquent\Model;



class ContactUs extends Model
{

    use HasFactory;

    protected $table = 'contactus';

    protected $fillable = [
        'email',
        'nom',
        'sujet',
        'message',
        'created_at',
        'updated_at'
    ];

    protected $hidden = [
        'created_at',
        'updated_at'
    ];




}
