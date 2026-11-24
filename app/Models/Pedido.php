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

    public const EN_CAJA = 'en_caja';

    public const RETIRADO = 'retirado';

    public const CANCELADO = 'cancelado';

    public const ESTADOS = [
        self::PENDIENTE => 'Pendiente',
        self::CONFIRMADO => 'Confirmado',
        self::PREPARANDO => 'Preparando',
        self::EN_CAJA => 'En caja',
        self::RETIRADO => 'Entregado',
        self::CANCELADO => 'Cancelado',
    ];

    public const ESTADOS_CERRADOS = [
        self::EN_CAJA,
        self::RETIRADO,
        self::CANCELADO,
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

    public function esCerrado(): bool
    {
        return in_array($this->estado, self::ESTADOS_CERRADOS, true);
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
