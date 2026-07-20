<?php

namespace App\Exceptions;

use Exception;

class BusinessRuleException extends Exception
{
    public function render($request)
    {
        if ($request->expectsJson()) {
            return response()->json([
                'error' => $this->getMessage(),
            ], 422);
        }

        return redirect()->back()
            ->with('error', $this->getMessage())
            ->withInput();
    }
}
