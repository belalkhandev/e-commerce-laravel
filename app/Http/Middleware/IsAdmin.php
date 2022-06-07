<?php

namespace App\Http\Middleware;

use Closure;
use Illuminate\Http\Request;
use Illuminate\Support\Facades\Auth;

class IsAdmin
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
		if(Auth::check()) {
			if(auth()->user()->role_id == 1){
				return $next($request);
			}else{
				// return redirect('backend/notfound')->with('error', __('You do not have permission to access this page') );
				return redirect('/');
			}
		}else{
			return redirect('/login');
		}
    }
}
