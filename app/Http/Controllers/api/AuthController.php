<?php

namespace App\Http\Controllers\api;

use App\Http\Controllers\Controller;
use App\Http\Requests\AuthRequest;
use App\Models\User;
use GuzzleHttp\Psr7\Request;
use Illuminate\Support\Facades\Hash;
use Symfony\Component\HttpFoundation\Response as ResponseAlias;



class AuthController extends Controller
{
    public  function  login(AuthRequest $request){

        return  $this->getUser($request->email,$request->password);

    }
    public function getUser($email,$password){
        $user  = User::query()->where('email',$email)->first();
        if($user && Hash::check($password, $user->password)  ){
            $token = $user->createToken("access_token")->plainTextToken;
            return response(['token'=>$token],ResponseAlias::HTTP_OK);
        }
        else{
            return response(['message'=>"invalid-credentials"],ResponseAlias::HTTP_UNPROCESSABLE_ENTITY);
        }
    }
}
