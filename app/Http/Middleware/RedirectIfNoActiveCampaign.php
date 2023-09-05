<?php

namespace App\Http\Middleware;

use Closure;
use Illuminate\Support\Facades\View;

class RedirectIfNoActiveCampaign
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
        if (! View::shared('activeCampaign')) {
            return redirect()->route('backstage.campaigns.index');
        }

        return $next($request);
    }
}
