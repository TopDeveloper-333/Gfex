<?php

namespace App\Http\Controllers\Settings;

use App\Http\Controllers\Controller;
use Illuminate\Http\Request;

use App\Http\Models;
use App\Models\User;

class ProfileController extends Controller
{
    /**
     * Update the user's profile information.
     */
    public function update(Request $request)
    {
        error_log('update() is called');
        $user = $request->user();

        $this->validate($request, [
            'name' => 'required',
            'email' => 'required|email|unique:users,email,'.$user->id,
        ]);

        $user->update($request->only('name', 'email'));

        return response()->json($user);
    }


    public function getUsers(Request $request)
    {
        // $page = $request->get('page');
        $current = $request->user();

        if ($current->is_admin != '1') {
            return response()->json([]);
        }

        // $users = User::getAllUsers($page);
        $users = User::getAllUsers();
        return response()->json($users);
    }


}
