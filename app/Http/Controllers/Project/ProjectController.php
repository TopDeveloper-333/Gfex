<?php

namespace App\Http\Controllers\Project;

use App\Http\Controllers\Controller;
use Illuminate\Http\Request;
use Illuminate\Support\Facades\Storage;
use TitasGailius\Terminal\Terminal;

class ProjectController extends Controller
{
    /**
     * Get authenticated user.
     */
    public function save(Request $request)
    {
        return response()->json([]);
    }
}
