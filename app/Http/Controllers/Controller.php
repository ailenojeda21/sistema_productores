<?php

namespace App\Http\Controllers;

use Illuminate\Foundation\Auth\Access\AuthorizesRequests;
use Illuminate\Http\Request;

abstract class Controller
{
    use AuthorizesRequests;

    protected function isApiRequest(Request $request): bool
    {
        return $request->expectsJson() && ! $request->header('X-Inertia');
    }
}
