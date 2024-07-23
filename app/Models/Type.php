<?php

namespace App\Models;

use Illuminate\Database\Eloquent\Factories\HasFactory;
use Illuminate\Database\Eloquent\Model;

class Type extends Model
{
    use HasFactory;

    // Définir explicitement le nom de la table
    protected $table = 'types';
    
    protected $fillable = [
        'type_name',
        'description',
        'image',
    ];
}
