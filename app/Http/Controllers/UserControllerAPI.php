<?php

namespace App\Http\Controllers;

use Illuminate\Http\Request;
use Illuminate\Contracts\Support\Jsonable;
use App\Http\Resources\UserResource;
use Illuminate\Support\Facades\DB;

use App\User;
use Hash;


class UserControllerAPI extends Controller
{
    public function show(User $user)
    {
        return new UserResource($user);
    }

    public function index()
    {
        return new UserResource(User::paginate(10));
    }

    public function store(Request $request)
    {
        $request->validate([
            'name' => 'required|min:3|regex:/^[A-Za-záàâãéèêíóôõúçÁÀÂÃÉÈÍÓÔÕÚÇ ]+$/',
            'email' => 'required|email|unique:users,email',
            'password' => 'required|min:3',
            'nif' => 'required|digits:9',
        ]);

        $user = new User();
        $user->fill($request->all());
        $user->password = bcrypt($request->password);
        $user->save();
        return new UserResource($user);
        
    }

    public function newAdmin(Request $request)
    {
        $request->validate([
            'name' => 'required|min:3|regex:/^[A-Za-záàâãéèêíóôõúçÁÀÂÃÉÈÍÓÔÕÚÇ ]+$/',
            'email' => 'required|email|unique:users,email',
            'password' => 'required|min:3',
            'type' => 'required'
        ]);
        $user = new User();
        $user->fill($request->all());
        $user->password = bcrypt($request->password);
        $user->type = "a";
        $user->save();
        return new UserResource($user);
    }

    public function newOperator(Request $request)
    {
        $request->validate([
            'name' => 'required|min:3|regex:/^[A-Za-záàâãéèêíóôõúçÁÀÂÃÉÈÍÓÔÕÚÇ ]+$/',
            'email' => 'required|email|unique:users,email',
            'password' => 'required|min:3',
            'type' => 'required'
        ]);
        $user = new User();
        $user->fill($request->all());
        $user->password = bcrypt($request->password);
        $user->type = "o";
        $user->save();
        return new UserResource($user);
    }

    public function reactivateUser($id, Request $request)
    {
        $request->validate([
            'active' => 'required'
        ]);
        $user = User::findOrFail($id);
        $user->active = "1";
        $user->update($request->all());
        return new UserResource($user);
    }

    public function deactivateUser($id, Request $request)
    {
        $request->validate([
            'active' => 'required'
        ]);
        $user = User::findOrFail($id);
        $user->active = "0";
        $user->update($request->all());
        return new UserResource($user);
    }

    public function update(Request $request, $id)
    {
        $request->validate([
            'name' => 'min:3|regex:/^[A-Za-záàâãéèêíóôõúçÁÀÂÃÉÈÍÓÔÕÚÇ ]+$/',
            'nif' => 'digits:9'
        ]);
        $user = User::findOrFail($id);
        $user->fill($request->all());
        $user->update($request->all());
        return new UserResource($user);
    }

    public function destroy(User $user)
    {
        $user->delete();
        return response()->json();
    }

    public function myProfile(Request $request)
    {
        return new UserResource($request->user());
    }

    public function changePassword(Request $request, $id){
       $request->validate([
        'password' => 'required|min:3'
        ]);
        $user = User::findOrFail($id);
        $user->password = bcrypt($request->password);
        $user->save();
        return new UserResource($user);
      
   }

   public function verifyEmail($email)
    {
        $data = DB::table('users')
            ->where('email', 'LIKE', "{$email}")
            ->count();
        return $data;
    }

    public function allOperators()
    {
        $data = DB::table('users')
            ->where('type', 'LIKE', "o")
            ->paginate(10);
            //->get();
        return $data;
    }

    public function allAdmins()
    {
        $data = DB::table('users')
            ->where('type', 'LIKE', "a")
            ->paginate(10);
            //->get();
        return $data;
    }

    public function allPlatformUsers()
    {
        $data = DB::table('users')
            ->Join('wallets', 'wallets.id', '=', 'users.id')
            ->where('users.type', 'LIKE', "u")
            ->paginate(10);
            //->get();
        return $data;
    }

    public function searchByName($name)
    {
        $data = DB::table('users')
            ->Join('wallets', 'wallets.id', '=', 'users.id')
            ->where('users.name', 'LIKE', "%{$name}%")
            ->paginate(10);
        return $data;
    }

    public function searchByEmail($email)
    {
        $data = DB::table('users')
            ->Join('wallets', 'wallets.id', '=', 'users.id')
            ->where('users.email', 'LIKE', "%{$email}%")
            ->paginate(10);
        return $data;
    }

    public function totalUsers()
    {
        $data = DB::table('users')->count();
        return $data;
    }

    public function totalOperators()
    {
        $data = DB::table('users')
            ->where('type', 'LIKE', 'O')
            ->count();
        return $data;
    }

    public function totalPlatformUsers()
    {
        $data = DB::table('users')
            ->where('type', 'LIKE', 'u')
            ->count();
        return $data;
    }

    public function totalAdmins()
    {
        $data = DB::table('users')
            ->where('type', 'LIKE', 'a')
            ->count();
        return $data;
    }
    
}
