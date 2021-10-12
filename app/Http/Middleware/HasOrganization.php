<?php

namespace App\Http\Middleware;

use Closure;
use Illuminate\Http\Request;

class HasOrganization
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
        $user = auth_user()->loadMissing('organizationRelation');

        if (is_null($user->organizationRelation)) {
            throw new \Exception('برای عملیات فعلی، هنوز هیچ تشکلی ایحاد نکرده اید!');
        }

        return $next($request);
    }
}
