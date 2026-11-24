<?php

namespace App\Models;

use Illuminate\Database\Eloquent\Model;
use Illuminate\Support\Carbon;

class Horario extends Model
{
    public const DIAS = [
        1 => 'Lunes',
        2 => 'Martes',
        3 => 'Miércoles',
        4 => 'Jueves',
        5 => 'Viernes',
        6 => 'Sábado',
        7 => 'Domingo',
    ];

    protected $fillable = ['dia', 'apertura', 'cierre', 'activo'];

    protected $casts = ['activo' => 'boolean'];

    public function nombreDia(): string
    {
        return self::DIAS[$this->dia];
    }

    public function rango(): string
    {
        if (! $this->activo || ! $this->apertura) {
            return 'Cerrado';
        }

        return self::formatearHora($this->apertura).' - '.self::formatearHora($this->cierre);
    }

    public static function formatearHora(?string $hora): string
    {
        if (! $hora) {
            return '';
        }

        return Carbon::parse($hora)->format('h:i A');
    }

    // Lo vamos a usar cuando hagamos los pedidos.
    public static function abiertoAhora(): bool
    {
        $ahora = now();

        return static::where('dia', $ahora->dayOfWeekIso)
            ->where('activo', true)
            ->where('apertura', '<=', $ahora->format('H:i:s'))
            ->where('cierre', '>', $ahora->format('H:i:s'))
            ->exists();
    }
}
