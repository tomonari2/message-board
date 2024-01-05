<?php

namespace App\Http\Controllers;

use Illuminate\Http\Request;

class VerifyAge extends Controller
{
    public function __invoke(Request $request)
    {
        abort_if(!($request->ajax() && $request->isMethod('POST')), 404);

        $isAgeVerified = (bool) $request->input('is_age_verified');
        session(['is_age_verified' => $isAgeVerified]);

        return response()->json(['isAgeVerified' => $isAgeVerified]);
    }
}
