<?php

namespace App\Http\Middleware;

use Closure;

class IsAppAdmins
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
        $allowed_emails = config('telescope.allowed_emails');

        if (!$request->user()) {
            abort(433, 'User not login');
        }

        if (!in_array($request->user()->email, $allowed_emails)) {
            abort(433, 'User not enough permissions');
        }

        return $next($request);
    }
}
