<?php

namespace App\Http\Middleware;

use Illuminate\Auth\Middleware\Authenticate as Middleware;

class ApiAuthenticate extends Middleware
{
    protected function redirectTo($request)
    {
        return null;
        // if (! $request->expectsJson()) {
        //     return route('login');
        // }
    }

    protected function unauthenticated($request, array $guards)
    {
        return response()->json([
            'response_code' => 401,
            'status' => 'error',
            'message' => 'Unauthenticated',
        ], 401);
    }
}
