<?php
namespace App\Http\Middleware;
use App;
use Closure;
use Auth;
class Localization {
    /**
     * Handle an incoming request.
     * @param \Illuminate\Http\Request $request
     * @param \Closure $next
     * @return mixed
     */
    public function handle($request, Closure $next) {
        if (session()->has('locale')) {
            App::setlocale(session()->get('locale'));
        }
        else if (Auth::guard('customer')->check()) {
            $user = Auth::guard('customer')->user();
            // dd($user);
            App::setlocale($user->lang);
            session()->put('locale', $user->lang);
        }
        return $next($request);
    }
}
