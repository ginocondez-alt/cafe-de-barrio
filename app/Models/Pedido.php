<?php

namespace App\Models;

use Database\Factories\PedidoFactory;
use Illuminate\Database\Eloquent\Factories\HasFactory;
use Illuminate\Database\Eloquent\Model;
use Illuminate\Database\Eloquent\Relations\BelongsTo;
use Illuminate\Database\Eloquent\Relations\HasMany;

class Pedido extends Model
{
    /** @use HasFactory<PedidoFactory> */
    use HasFactory;

    public const PENDIENTE = 'pendiente';

    public const CONFIRMADO = 'confirmado';

    public const PREPARANDO = 'preparando';

    public const FINALIZADO = 'finalizado';

    public const RETIRADO = 'retirado';

    public const CANCELADO = 'cancelado';

    public const ESTADOS = [
        self::PENDIENTE => 'Pendiente',
        self::CONFIRMADO => 'Confirmado',
        self::PREPARANDO => 'Preparando',
        self::FINALIZADO => 'Listo para retirar',
        self::RETIRADO => 'Retirado',
        self::CANCELADO => 'Cancelado',
    ];

    protected $fillable = ['user_id', 'estado'];

    protected $attributes = [
        'estado' => self::PENDIENTE,
    ];

    public function user(): BelongsTo
    {
        return $this->belongsTo(User::class);
    }

    public function items(): HasMany
    {
        return $this->hasMany(ItemPedido::class);
    }

    public function esPendiente(): bool
    {
        return $this->estado === self::PENDIENTE;
    }

    public function estadoTexto(): string
    {
        return self::ESTADOS[$this->estado] ?? (string) $this->estado;
    }

    public function subtotal(): float
    {
        return (float) $this->items->sum(fn (ItemPedido $item): float => $item->subtotal());
    }
}
