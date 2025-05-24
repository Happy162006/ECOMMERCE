<?php

namespace App\Models;

use Illuminate\Database\Eloquent\Model;

class Producto extends Model
{
    protected $table = 'producto'; // Nombre exacto de la tabla
    protected $fillable = ['nombre', 'precio', 'stock', 'imagen', 'categoria_id'];
    
    public function categoria()
    {
        return $this->belongsTo(Categoria::class, 'categoria_id');
    }
}