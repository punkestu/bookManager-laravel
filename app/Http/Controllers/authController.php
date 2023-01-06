<?php

namespace App\Http\Controllers;

use Illuminate\Support\Facades\Auth;
use Illuminate\Support\Facades\Validator;
use Illuminate\Http\Request;
use App\Models\User;

class authController extends Controller
{
    public function signup(Request $request)
    {
        $validator = Validator::make($request->all(), [
            "name" => "required|unique:users,name",
            "email" => "required|email|unique:users,email",
            "password" => "required"
        ]);

        if ($validator->fails()) {
            return $this->sendError($validator->errors());
        }

        $input = $request->all();
        $input['password'] = bcrypt($input['password']);
        $user = User::create($input);
        $data["token"] = $user->createToken("AuthToken")->plainTextToken;
        $data["name"] = $user->name;

        return $this->sendSuccess($data);
    }

    public function signin(Request $request)
    {
        $validator = Validator::make($request->all(), [
            "email" => "required|email",
            "password" => "required"
        ]);

        if ($validator->fails()) {
            return $this->sendError($validator->errors());
        }

        if (Auth::attempt(["email" => $request->email, "password" => $request->password])) {
            $user = Auth::user();
            $data["token"] = $user->createToken("AuthToken")->plainTextToken;
            $data["name"] = $user->name;

            return $this->sendSuccess($data);
        } else {
            return $this->sendError(["status" => "Unauthorized"]);
        }
    }

    public function noAuth(Request $request)
    {
        if ($request->bearerToken() != null) {
            return $this->sendError(["token" => "token is invalid"], 403);
        } else {
            return $this->sendError(["token" => "token is required"]);
        }
    }
}
