<?php

namespace App\Http\Middleware;

use Closure;
use Illuminate\Http\Request;
use Illuminate\Support\Facades\Auth;

class isActive
{
    /**
     * Handle an incoming request.
     *
     * @param  \Illuminate\Http\Request  $request
     * @param  \Closure  $next
     * @return mixed
     */
    public function handle(Request $request, Closure $next)
    {
		
        if (auth()->check()) {
			if(auth()->user()->status_id == 2){
				auth()->logout();
				
				$message = __('You are not active yet. Please contact administrator.');
				return redirect()->back()->withMessage($message);
			}
        }
		
        return $next($request);
    }
}
