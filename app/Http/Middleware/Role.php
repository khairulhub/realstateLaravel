<?php

namespace App\Http\Middleware;

use Closure;
use Illuminate\Http\Request;

class Role
{
    /**
     * Handle an incoming request.
     *
     * @param  \Illuminate\Http\Request  $request
     * @param  \Closure  $next
     * @param  string  $role
     * @return mixed
     */
    public function handle(Request $request, Closure $next, string $role)
    {
        $userRole = $request->user()->role;
        if ($userRole === 'user' && $userRole !== 'user') {
            return redirect('/dashboard');
        }elseif($userRole === 'admin' && $userRole === 'user'){
            return redirect('/admin/dashboard');
        }
        elseif($userRole === 'agent' && $userRole === 'user'){
            return redirect('/agent/dashboard');
        }
        elseif($userRole === 'admin' && $userRole === 'agent'){
            return redirect('/admin/dashboard');
        }
        elseif($userRole === 'agent' && $userRole === 'admin'){
            return redirect('/agent/dashboard');
        }
        return $next($request);
    }
}
