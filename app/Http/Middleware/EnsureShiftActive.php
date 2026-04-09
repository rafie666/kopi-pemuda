<?php

namespace App\Http\Middleware;

use Closure;
use Illuminate\Http\Request;
use Symfony\Component\HttpFoundation\Response;

class EnsureShiftActive
{
    /**
     * Handle an incoming request.
     *
     * @param  \Closure(\Illuminate\Http\Request): (\Symfony\Component\HttpFoundation\Response)  $next
     */
    public function handle(Request $request, Closure $next): Response
    {
        if (auth()->check() && auth()->user()->role === 'kasir') {
            $activeShift = \App\Models\Shift::where('user_id', auth()->id())
                ->whereNull('end_time')
                ->first();

            if (!$activeShift && !$request->routeIs('kasir.shift') && !$request->routeIs('kasir.shift.start')) {
                return redirect()->route('kasir.shift')
                    ->with('error', 'Silahkan masukkan Saldo Awal untuk memulai shift.');
            }
        }

        return $next($request);
    }
}
