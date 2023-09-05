<?php

namespace App\Http\Middleware;

use App\Models\Campaign;
use Closure;
use Illuminate\Http\Request;
use Symfony\Component\HttpFoundation\Response;

class SetActiveCampaign
{
    /**
     * Handle an incoming request.
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
