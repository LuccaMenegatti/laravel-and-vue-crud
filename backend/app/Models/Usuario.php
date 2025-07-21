<?php

namespace App\Models;

use Illuminate\Database\Eloquent\Model;

class Usuario extends Model
{
    protected $fillable = ['nome_completo', 'data_nascimento', 'cpf'];
    
    public function cursos()
    {
        return $this->belongsToMany(Curso::class);
    }
}
