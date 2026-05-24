<?php

namespace App\Http\Controllers;

use Illuminate\Http\Request;

abstract class Controller
{
    protected function isApiRequest(Request $request): bool
    {
        return $request->expectsJson() && ! $request->header('X-Inertia');
    }
}
