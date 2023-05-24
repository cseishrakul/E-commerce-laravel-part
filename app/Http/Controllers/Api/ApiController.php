<?php

namespace App\Http\Controllers\Api;

use App\Http\Controllers\Controller;
use Illuminate\Http\Request;
use App\Models\User;

class ApiController extends Controller
{
    // Register Funtion
    public function registerUser(Request $request)
    {
        if ($request->isMethod('post')) {
            $data = $request->input();
            
            $user = new User;
            $user->name = $data['name'];
            $user->mobile = $data['mobile'];
            $user->email = $data['email'];
            $user->password = bcrypt($data['password']);
            $user->status = 1;
            $user->save();

            return response()->json(['status'=>true,'message'=>'User Register Successfullq!'],201);


        }
    }
}
