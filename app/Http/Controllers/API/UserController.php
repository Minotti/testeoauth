<?php

/**
 * Created by PhpStorm.
 * User: marlon
 * Date: 23/07/17
 * Time: 11:30
 */
namespace App\Http\Controllers\API;

use App\Http\Controllers\Controller;
use Illuminate\Support\Facades\Auth;
use Validator;
use App\User;
use Illuminate\Http\Request;

class UserController extends Controller
{
    public $successStatus = 200;
    public function login()
    {
        if (Auth::attempt(['email' => Request('email'), 'password' => Request('password')])):
            $user = Auth::user();
            $success['token'] = $user->createToken('app')->accessToken;

            return response()->json(['success' => $success], $this->successStatus);
        endif;

        return response()->json(['error' => 'Unauthorized'], 401);
    }

    public function register(Request $request)
    {
        $validator = Validator::make($request->all(), [
            'name' => 'required',
            'email' => 'required|email|unique:users,email',
            'password' => 'required',
            'c_password' => 'required|same:password'
        ]);

        if ($validator->fails()):
            return response()->json(['error' => $validator->errors()], 401);
        endif;

        $input = $request->all();
        $input['password'] = bcrypt($input['password']);
        $user = User::create($input);
        $success['token'] = $user->createToken('app')->accessToken;
        $success['name'] = $user->name;

        return response()->json(['success'=>$success], $this->successStatus);
    }

    //POST
    public function details()
    {
        $user = Auth::user();
        return response()->json(['success' => $user], $this->successStatus);
    }

    //GET
    public function detailsUser($id)
    {
        $user = User::find($id);
        return response(['success' => $user], $this->successStatus);
    }

    //OUTRO POST
    public function teste()
    {
        return response()->json(['success'=>'Jean feioso'], 200);
    }
}