<?php

namespace App\Models;

use Illuminate\Database\Eloquent\Model;

class Fortuna extends Model
{
    protected $table = 'fortunas';
    
    protected $fillable = [
        'mensaje',
        'agregado_por'
    ];
    
    public $timestamps = false;
    
    public function usuario()
    {
        return $this->belongsTo(Usuario::class, 'agregado_por');
    }
}