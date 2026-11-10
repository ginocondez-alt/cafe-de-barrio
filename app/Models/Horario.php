<?php

namespace App\Models;

use Illuminate\Database\Eloquent\Model;

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

        return substr($this->apertura, 0, 5) . ' - ' . substr($this->cierre, 0, 5);
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