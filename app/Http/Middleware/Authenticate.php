<?php

namespace App\Http\Middleware;

use Illuminate\Auth\Middleware\Authenticate as Middleware;

class Authenticate extends Middleware
{
    /**
     * Get the path the user should be redirected to when they are not authenticated.
     *
     * @param  \Illuminate\Http\Request  $request
     * @return string|null
     */
    protected function redirectTo($request)
    {
        if (! $request->expectsJson()) {
            $slug = $request->route('church_slug') ?? session('church_slug');
            if ($slug) {
                return route('login', ['church_slug' => $slug]);
            }
            abort(404, 'Gereja tidak ditentukan.');
        }
    }
}
