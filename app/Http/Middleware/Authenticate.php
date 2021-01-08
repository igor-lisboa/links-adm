<?php

namespace App\Http\Middleware;

use App\Models\User;
use Closure;
use Illuminate\Support\Facades\Auth;

class Authenticate
{
    /**
     * Handle an incoming request.
     *
     * @param  \Illuminate\Http\Request  $request
     * @param  \Closure  $next
     * @return mixed
     */
    public function handle($request, Closure $next)
    {
        $user = User::where('token', '=', $request->header('Authorization'))->first();
        if ($user === null) {
            return response()->json([
                "message" => "Credenciais inválidas.",
                "data" => [],
                "success" => false
            ], 401);
        }
        Auth::login($user);
        return $next($request);
    }
}
