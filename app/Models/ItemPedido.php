<?php

namespace App\Models;

use Database\Factories\ItemPedidoFactory;
use Illuminate\Database\Eloquent\Factories\HasFactory;
use Illuminate\Database\Eloquent\Model;
use Illuminate\Database\Eloquent\Relations\BelongsTo;

class ItemPedido extends Model
{
    /** @use HasFactory<ItemPedidoFactory> */
    use HasFactory;

    protected $fillable = [
        'pedido_id',
        'producto_id',
        'nombre',
        'imagen',
        'precio_unitario',
        'cantidad',
    ];

    protected $attributes = [
        'cantidad' => 1,
    ];

    protected $casts = [
        'precio_unitario' => 'decimal:2',
        'cantidad' => 'integer',
    ];

    public function pedido(): BelongsTo
    {
        return $this->belongsTo(Pedido::class);
    }

    public function producto(): BelongsTo
    {
        return $this->belongsTo(Producto::class);
    }

    public function subtotal(): float
    {
        return (float) $this->precio_unitario * $this->cantidad;
    }
}
