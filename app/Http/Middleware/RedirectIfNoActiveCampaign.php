<?php

declare(strict_types=1);

namespace App\Http\Middleware;

use Closure;
use Illuminate\Http\Request;
use Illuminate\Support\Facades\View;
use Symfony\Component\HttpFoundation\Response;

final class RedirectIfNoActiveCampaign
{
    /**
     * Handle an incoming request.
     */
    public function handle(Request $request, Closure $next): Response
    {
        if (! View::shared('activeCampaign')) {
            return redirect()->route('backstage.campaigns.index');
        }

        return $next($request);
    }
}
