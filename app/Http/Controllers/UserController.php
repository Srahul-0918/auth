<?php

namespace App\Http\Controllers;

use http\Env\Response;
use Illuminate\Http\Request;
use App\Models\User;
use Illuminate\Support\Facades\Validator;
use Tymon\JWTAuth\Contracts\Providers\Auth;

use Illuminate\Support\Facades\Hash;

class UserController extends Controller
{
    public function register(Request $request)


    {
        $requestData = json_decode($request->getContent(), true);
        $validator = Validator::make($requestData, [

            'name' => 'required|string|min:2|max:100',
            'email' => 'required|string|email|max:100',
            'password' => 'required|string|min:6|confirmed'

        ]);


        if ($validator->fails()) {

            return response()->json($validator->errors(), 400);

        }
        $user = User::create([
            'name' => $requestData['name'],
            'email' => $requestData['email'],
            'password' => HASH::make($requestData['password']),


        ]);

        return \response()->json(["Message" => "user registered succesfully",
                'user' => $user]
        );

    }

    //for login

    public function login(Request $request)
    {

        $requestData = json_decode($request->getContent(), true);
        $validator = Validator::make($requestData, [

            'email' => 'required|string|email|max:100',
            'password' => 'required|string|min:6'
        ]);

        if ($validator->fails()) {

            return response()->json($validator->errors(), 400);

        }

        if (!$token = auth()->attempt($validator->validated())) {
            return response()->json(['error' => 'Unauthorised']);
        }
        return $this->respondWithToken($token);

    }

    protected function respondWithToken($token)
    {

        return response()->json(['access_token' => $token,
            'token_type' => 'bearer',
            'expires_in' => auth()->factory()->getTTL() * 6]);
    }

    public function profile()
    {
        return response()->json(auth()->user());
    }

    public function refresh()
    {

        return $this->respondWithToken(auth()->refresh());
    }

    public function logout(){

        auth()->logout();
        return response()->json(['Message'=>"Logged Out Successfully"]);
    }


}
