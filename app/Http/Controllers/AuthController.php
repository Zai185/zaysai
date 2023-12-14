<?php

namespace App\Http\Controllers;

use Illuminate\Http\Request;
use Illuminate\Validation\Rules\Password;
use App\Models\User;
use Exception;
use Illuminate\Support\Facades\Auth;

class AuthController extends Controller
{
    /**
     * @param Request $request{name, email, password, password_confirmation}
     */
    public function register(Request $request)
    {
        //validate the info from request
        //if ok, create a user,
        //return userdata and token for use

        $data = $request->validate([
            'name' => 'required|string|min:8',
            'email' => 'required|email|unique:users,email',
            'password' => ['required', 'confirmed', Password::min(8)->numbers()->symbols()],
        ]);

        $user = User::create([
            'name' => $data['name'],
            'email' => $data['email'],
            'password' => bcrypt($data['password']),
        ]);
        $token = $user->createToken('user_jwt')->plainTextToken;

        return response([
            'data' => $user,
            'token' => $token
        ]);
    }

    /**
     * @param Request $request{email, password, remember}
     */

    public function login(Request $request)
    {

        $credentials = $request->validate([
            'email' => 'required|email|exists:users,email',
            'password' => 'required',
            'remember' => 'nullable|boolean',
        ]);

        $remember = $credentials['remember'];
        unset($credentials['remember']);

        if(!Auth::attempt($credentials,$remember)){
            return response("Invalid credentials", 423 );
        }

        $user = auth()->user();
        $token = $user->createToken('user_jwt')->plainTextToken;

        return response([
            'data' => $user,
            'token' => $token
        ]);
    }

    /**
     * @param {} 
     */

     public function logout(){
        $user = Auth::user();

        $user->currentAccessToken()->delete();
        return response([
            'success' => "logout successfully",
        ]);


     }
}
