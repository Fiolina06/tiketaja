<?php

namespace App\Http\Controllers;

use Illuminate\Http\Request;
use Auth;
use App\Models\User;

class UserController extends Controller
{
    public function getUser()
    {
        $admin = Auth::user();
        return response()->json(['data' => $admin]);
    }

    public function getAllUser()
    {
        $admin = User::all();
        return $admin;
    }

    public function login(Request $request)
    {
        $input = $request->all();

        $validate = \Validator::make($input,[
            'email' => 'required|email',
            'password' => 'required'
        ]);

        if ($validate->fails()){
            return response()->json(['error' => $validate->errors()], 422);
        }

        if (Auth::guard('user')->attempt(['email' => $input['email'], 'password' => $input['password']])){
            $user = Auth::guard('user')->user();

            $token = $user->createToken('MyToken', ['user'])->plainTextToken;

            return response()->json(['token' => $token]);

        }
        else {
            return response([
                'message' => 'Unathorized'
            ], 401);
        }
    }

    public function register(Request $request)
    {
        $fields = $request->validate([
            'name' => 'required|string|max:100',
            'username' => 'required|string|max:100',
            'no_hp' => 'required|string|max:100',
            'email' => 'required|string|unique:users,email',
            'tgl_lahir' => 'required',
            'password' => 'required|string|confirmed|min:6'
        ]);

    

        $user = User::create([
            'name' => $fields['name'],
            'username' => $fields['username'],
            'email'=> $fields['email'],
            'no_hp' => $fields['no_hp'],
            'tgl_lahir' => $fields['tgl_lahir'],
            'password' => bcrypt($fields['password'])
        ]);

        $token = $user->createToken('MyToken', ['user'])->plainTextToken;

        $response = [
            'user' => $user,
            'token' => $token
        ];

        return response($response, 201);
    }

    public function updateProfile(Request $request)
    {
        $idUser = $user = Auth::id();
        $user = User::where('id', $idUser)->first();
        if($user){
            $user->name = $request->name ? $request->name : $user->name;
            $user->username = $request->username ? $request->username : $user->username;
            $user->email = $request->email ? $request->email : $user->email;
            $user->no_hp = $request->no_hp ? $request->no_hp : $user->no_hp;
            $user->tgl_lahir = $request->tgl_lahir ? $request->tgl_lahir : $user->tgl_lahir;
            $user->password = bcrypt($request->password) ? bcrypt($request->password) : $user->password;
            $user->save();
            return response()->json([
                'status' => 200,
                'message' => "Data user berhasil diubah", 
                'data' => $user
            ], 200);
            
        } else {
            return response()->json([
                'status' => 404,
                'message' => 'Data dengan id ' . $id . ' tidak ditemukan'
            ], 404);
        }
    }

    public function deleteAccount()
    {
        $idUser = $user = Auth::id();
        $user = User::where('id', $idUser)->first();
        if($user){
            $user->delete();
            return response()->json([
                'status' => 200,
                'message' => "Account berhasil dihapus", 
            ], 200);
        } else {
            return response()->json([
                'status' => 404,
                'message' => 'Data dengan Id ' . $id . ' tidak ditemukan' 
            ], 404);
        }
    }

    public function logout(Request $request){
        $request->user()->currentAccessToken()->delete();

        return [
            'message' => 'Logged Out'
        ];

    }
}
