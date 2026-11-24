<?php

namespace App\Models;

use Database\Factories\ProductoFactory;
use Illuminate\Database\Eloquent\Factories\HasFactory;
use Illuminate\Database\Eloquent\Model;
use Illuminate\Database\Eloquent\Relations\HasMany;

class Producto extends Model
{
    /** @use HasFactory<ProductoFactory> */
    use HasFactory;

    protected $fillable = ['categoria_id', 'nombre', 'descripcion', 'precio', 'imagen']; // Permite la asignación masiva de los campos

    public function categoria()
    {
        return $this->belongsTo(Categoria::class); // Relación muchos a uno con el modelo Categoria
    }

    public function itemsPedido(): HasMany
    {
        return $this->hasMany(ItemPedido::class);
    }
}
