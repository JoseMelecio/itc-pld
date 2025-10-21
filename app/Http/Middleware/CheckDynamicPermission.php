<?php

namespace App\Http\Middleware;

use Closure;
use Illuminate\Http\Request;
use Illuminate\Support\Facades\Log;
use Symfony\Component\HttpFoundation\Response;

class CheckDynamicPermission
{
    /**
     * Handle an incoming request.
     *
     * @param  \Closure(Request): (Response)  $next
     */
    public function handle(Request $request, Closure $next): Response
    {
        $user = $request->user();

        // Si el usuario no está autenticado
        if (!$user) {
            abort(401, 'No autenticado');
        }

        // Obtener el nombre de la ruta (por ejemplo: "users.index")
        $routeName = $request->route()->getName();

        // Si la ruta no tiene nombre, puedes decidir dejar pasar o bloquear
//        if (!$routeName) {
//            return $next($request);
//        }

        // Verificar si el permiso existe y si el usuario lo tiene
        if ($user->can($routeName)) {
            return $next($request);
        }

        // Si no tiene el permiso, devolver 403
        abort(403, 'No tienes permiso para acceder a esta sección.');
    }
}
