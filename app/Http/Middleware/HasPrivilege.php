<?php

namespace App\Http\Middleware;

use Closure;
use Illuminate\Support\Facades\Auth;

class HasPrivilege
{
    /**
     * Handle an incoming request.
     *
     * @param  \Illuminate\Http\Request  $request
     * @param  \Closure  $next
     * @param  string|null  $guard
     * @return mixed
     */
    public function handle($request, Closure $next, $guard = null)
    {
        if (Auth::guard($guard)->check()) {
            $user = \Auth::guard($guard)->user();
            if(!$user->is_master){
                if($request->is('package') || $request->is('package/*')){
                    \Alert::warning(__('admin.no_privilege_for_package'))->flash();
                }

                if($request->is('company') || $request->is('company/*')){
                    \Alert::warning(__('admin.no_privilege_for_company'))->flash();
                }

                return redirect(backpack_url('dashboard'));
            }
        }
        return $next($request);
    }
}
