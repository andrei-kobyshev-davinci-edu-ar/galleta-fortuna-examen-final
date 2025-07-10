<?php

namespace App\Models;

use Illuminate\Database\Eloquent\Model;

class Usuario extends Model
{
    protected $table = 'usuarios';
    
    protected $fillable = [
        'nombre',
        'email', 
        'password',
        'rol'
    ];
    
    protected $hidden = [
        'password'
    ];
    
    public $timestamps = false;
    
    public function fortunas()
    {
        return $this->hasMany(Fortuna::class, 'agregado_por');
    }
}