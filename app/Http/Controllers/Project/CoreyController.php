<?php

namespace App\Http\Controllers\Project;

use App\Http\Controllers\Controller;
use Illuminate\Http\Request;

class CoreyController extends Controller
{
    /**
     * Get authenticated user.
     */
    public function requestKGKO(Request $request)
    {
        return response()->json([
            [0, 0, 1],
            [0.003, 0.000, 0.985],
        ]);
    }
}
