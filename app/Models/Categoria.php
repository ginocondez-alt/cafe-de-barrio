<?php

namespace App\Models;

use Illuminate\Database\Eloquent\Factories\HasFactory;
use Illuminate\Database\Eloquent\Model;

class Categoria extends Model
{
    /** @use HasFactory<\Database\Factories\CategoriaFactory> */
    use HasFactory;

    protected $fillable = ['nombre']; // Permite la asignación masiva del campo 'nombre'

    public function productos()
    {
        return $this->hasMany(Producto::class); // Relación uno a muchos con el modelo Producto
    }
}
