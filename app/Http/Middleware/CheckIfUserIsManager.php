<?php

namespace App\Http\Middleware;

use Closure;
use Illuminate\Http\Request;
use Illuminate\Support\Facades\Auth;

use App\Models\User;

class CheckIfUserIsManager
{
    /**
     * Handle an incoming request.
     *
     * @param  \Illuminate\Http\Request  $request
     * @param  \Closure(\Illuminate\Http\Request): (\Illuminate\Http\Response|\Illuminate\Http\RedirectResponse)  $next
     * @return \Illuminate\Http\Response|\Illuminate\Http\RedirectResponse
     */
    public function handle(Request $request, Closure $next)
    {
        $user = Auth::guard('api')->user();
        
        //Если пользователь является администратором, перенаправить его на страницу заказов в админке
        if($user->isAdmin())
            return redirect()->route('api.admin.orders.index');

        return 
            $user->isManager()
            ? $next($request)
            : response()->json(['message' => 'У вас нет полномочий на просмотр данных']);
    }
}
