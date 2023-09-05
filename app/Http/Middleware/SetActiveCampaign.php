<?php

namespace App\Http\Middleware;

use Symfony\Component\HttpFoundation\Response;
use Illuminate\Http\Request;
use App\Models\Campaign;
use Closure;

class SetActiveCampaign
{
    /**
     * Handle an incoming request.
     *
     * @param  \Illuminate\Http\Request  $request
     * @param  \Closure  $next
     * @return mixed
     */
    public function handle(Request $request, Closure $next): Response
    {
        $activeCampaign = null;

        if (session('activeCampaign')) {
            $activeCampaign = Campaign::find(session('activeCampaign'));

            if ($activeCampaign === null) {
                session()->forget('activeCampaign');
            }
        }

        view()->share('activeCampaign', $activeCampaign);

        return $next($request);
    }
}
