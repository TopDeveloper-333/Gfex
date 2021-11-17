<?php

namespace App\Http\Controllers\Auth;

use App\Http\Controllers\Controller;
use Illuminate\Http\Request;
use Illuminate\Http\Response;
use Illuminate\Support\Facades\Validator;
use App\Models\User;

class UserController extends Controller
{
    /**
     * Get authenticated user.
     */
    public function current(Request $request)
    {
        return response()->json($request->user());
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

    public function addUser(Request $request)
    {
        $currentUser = $request->user();
        
        if ($currentUser->is_admin != 1)
            return response()->json(['code' => -1, 'message' => 'The user has not administrator priviledge']);

        $validator = Validator::make($request->all(), [
            'name' => 'required|max:255',
            'email' => 'required|email:filter|max:255|unique:users',
            'password' => 'required|min:6|confirmed',
            'role' => 'required',
            'is_revoke' => 'required'
        ]);

        if ($validator->fails()) {
            return response()->json(['message' => json_encode($validator->messages())]);
        }

        $from = $request->get('from');
        // if ($from == null)
        //     $from = '0000-00-00';

        $to = $request->get('to');
        // if ($to == null)
        //     $to = '0000-00-00';            

        return User::create([
            'name' => $request->get('name'),
            'email' => $request->get('email'),
            'password' => bcrypt($request->get('password')),
            'role' => $request->get('role'),
            'from' => $from,
            'to' => $to,
            'is_revoke' => $request->get('is_revoke')
        ]);
    }

    public function removeUser(Request $request)
    {
        $currentUser = $request->user();
        
        if ($currentUser->is_admin != 1)
            return response()->json(['code' => -1, 'message' => 'The user has not administrator priviledge']);

        $exitUser = User::find($request->get('id'));
        $exitUser->delete();

        return response()->json([]);
    }

    public function updateUser(Request $request)
    {
        $currentUser = $request->user();
        
        if ($currentUser->is_admin != 1)
            return response()->json(['code' => -1, 'message' => 'The user has not administrator priviledge']);

        $updateUser = $request->get('updateuser');

        $this->validate($updateUser, [
            'name' => 'required',
            'email' => 'required|email|unique:users,email,'.$updateUser->id,
            'role' => 'required',
            'is_revoke' => 'required'
        ]);

        $updateUser->update($updateUser->only('name', 'email', 'role', 'from', 'to', 'is_rovoke'));
        return response()->json($updateUser);
            
    }
}
