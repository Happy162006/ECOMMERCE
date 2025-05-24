<?php

namespace App\Models;

use Illuminate\Database\Eloquent\Model;

class Categoria extends Model
{
    protected $table = 'categoria'; // Nombre exacto de la tabla
    protected $fillable = ['nombre'];

    public function productos()
    {
        return $this->hasMany(Producto::class, 'categoria_id');
    }
}