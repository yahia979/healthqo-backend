<?php

namespace App\Http\Controllers;

use Illuminate\Http\Request;
use Illuminate\Support\Facades\Auth;

class AuthController extends Controller
{
    // login 
    public function login (Request $request){
        
        // login with email or username and password
        $credentials = [
            'password' => $request->password
        ];

        if ($request->username) {
            $credentials['username'] = $request->username;
        } else if($request->email) {
            $credentials['email'] = $request->email;
        }else{
            $credentials = null;
        }

        // try to log the user in
        if ($credentials && Auth::attempt($credentials)) {
            $token = auth()->user()->createToken('2heal')->accessToken;

            $user = Auth::user();
            $data['user'] = $user;
            $data['token'] = $token;
            return response()->json(['status' => 201, 'msg' => 'user logged in', 'data' => $data], 200);
        } else {
            if ($credentials) {
                // email/username or password are wrong
                return response()->json(['status' => 404, 'msg' => 'user not found', 'data' => null], 200);
            } else {
                // token is not valid
                return response()->json(['status' => 500, 'msg' => 'user not authanticated', 'data' => null], 200);
            }
            
        }

    }

}
