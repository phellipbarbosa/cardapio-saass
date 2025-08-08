<?php

namespace App\Http\Middleware;

use Closure;
use Illuminate\Http\Request;
use Illuminate\Support\Facades\Auth;
use Carbon\Carbon;
use Symfony\Component\HttpFoundation\Response;

class CheckTrialStatus
{
    public function handle(Request $request, Closure $next): Response
    {
        $user = Auth::user();

        // Se o usuário está logado e não tem um plano ativo
        if ($user && !$user->is_active) {
            
            // Se o período de teste expirou (ou nunca existiu), o acesso é bloqueado
            if ($user->trial_ends_at === null || Carbon::now()->isAfter(Carbon::parse($user->trial_ends_at))) {
                
                Auth::logout();
                $request->session()->invalidate();
                $request->session()->regenerateToken();
                
                return redirect('/login')->with('status', 'Seu período de teste expirou. Por favor, entre em contato para reativar seu plano.');
            }
        }
        
        return $next($request);
    }
}