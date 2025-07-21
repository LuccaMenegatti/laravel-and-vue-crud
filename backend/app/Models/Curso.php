<?php

namespace App\Models;

use Illuminate\Database\Eloquent\Model;

class Curso extends Model
{
    protected $fillable = ['descricao', 'tipo'];

    public function usuarios()
    {   
        return $this->belongsToMany(Usuario::class);
    }
}
