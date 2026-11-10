<?php

namespace App\Http\Middleware;

use Closure;
use Illuminate\Http\Request;
use Symfony\Component\HttpFoundation\Response;

class VerificarRol
{
    public function handle(Request $request, Closure $next, string ...$roles): Response
    {
        $user = $request->user();

        if (! $user || ! $user->tieneRol(...$roles)) {
            abort(403, 'No tenés permiso para entrar acá.');
        }

        return $next($request);
    }
}