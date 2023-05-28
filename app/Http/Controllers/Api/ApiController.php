<?php

namespace App\Http\Controllers\Api;

use App\Http\Controllers\Controller;
use Illuminate\Http\Request;
use App\Models\User;
use Validator;

class ApiController extends Controller
{
    // Register Funtion
    public function registerUser(Request $request)
    {
        if ($request->isMethod('post')) {
            $data = $request->input();

            $rules = [
                "name" => "required",
                "email" => "required|email|unique:users",
                "mobile" => "required",
                "password" => "required"
            ];

            $customMessages = [
                "name.required" => "Name is required",
                "email.required" => "Email is required",
                "email.unique" => "Email already exists",
                "mobile.required" => "Mobile number required",
                "password.required" => "Password is required",

            ];

            $validator = Validator::make($data, $rules, $customMessages);
            if ($validator->fails()) {
                return response()->json($validator->errors(), 422);
            }

            $user = new User;
            $user->name = $data['name'];
            $user->mobile = $data['mobile'];
            $user->email = $data['email'];
            $user->password = bcrypt($data['password']);
            $user->status = 1;
            $user->save();

            return response()->json(['status' => true, 'message' => 'User Register Successfullq!'], 201);
        }
    }

    public function loginUser(Request $request)
    {
        if ($request->isMethod('post')) {
            $data = $request->input();

            // verify user email
            $userCount = User::where('email', $data['email'])->count();

            if ($userCount > 0) {
                // fetch user details
                $userDetails = User::where('email', $data['email'])->first();

                // verify the password
                if (password_verify($data['password'], $userDetails->password)) {
                    return response()->json([
                        "status" => true,
                        "message" => "User login successfully!"
                    ], 201);
                } else {
                    $message = "Password is incorrect!";
                    return response()->json(['status' => false, 'message' => $message], 422);
                }
            } else {
                $message = "Email is incorrect!";
                    return response()->json(['status' => false, 'message' => $message], 422);
            }
        }
    }
}
