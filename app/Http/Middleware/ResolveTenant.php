<?php

namespace App\Http\Middleware;

use Closure;
use Illuminate\Http\Request;
use App\Services\TenantManager;
use App\Models\Church;

class ResolveTenant
{
    public function handle(Request $request, Closure $next)
    {
        $slug = $request->route('church_slug');
        
        if (!$slug) {
            abort(404, 'Gereja tidak ditentukan.');
        }

        $church = Church::where('slug', $slug)->where('status', 'aktif')->first();

        if (!$church) {
            abort(404, 'Gereja tidak ditemukan atau tidak aktif.');
        }

        // Set current active tenant in TenantManager
        app(TenantManager::class)->setTenant($church);

        // Share the active church variable with all Blade views automatically
        view()->share('currentChurch', $church);

        return $next($request);
    }
}
