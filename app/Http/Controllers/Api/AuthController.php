<?php

namespace App\Http\Controllers\Api;

use App\Http\Controllers\Controller;
use App\Models\User;
use Illuminate\Http\Request;
use Auth, Validator;
class AuthController extends Controller
{



    /**
     * @OA\Post(
     *   path="/api/create-user",
     *   tags={"Auth"},
     *   summary="Create User",
     *
     *   @OA\Parameter(
     *          name="name",
     *          in="query",
     *          required=true,
     *          @OA\Schema(type="string")
     *   ),
     *
     *   @OA\Parameter(
     *          name="email",
     *          in="query",
     *          required=true,
     *          @OA\Schema(type="string")
     *   ),
     *
     *   @OA\Parameter(
     *          name="password",
     *          in="query",
     *          required=true,
     *          @OA\Schema(type="string")
     *   ),
     *
     *   @OA\Parameter(
     *          name="password_confirmation",
     *          in="query",
     *          required=true,
     *          @OA\Schema(type="string")
     *   ),
     *
     *   @OA\Response(response=200, description="Success"),
     *   @OA\Response(response=400, description="Validation"),
     *   @OA\Response(response=401, description="Unauthorised")
     *
     * )
     */
    public function createUser(Request $request){

        $validator = Validator::make($request->all(), [
            'name' => 'required',
            'email' => 'email|unique:users',
            'password' => 'required|confirmed',
        ]);

        if ($validator->fails()):
            $errors = $validator->errors();
            return $this->errorResponse($errors,$errors->first());
        endif;

        $user = new User();
        $user->name = $request->name;
        $user->email = strtolower($request->email);
        $user->password = bcrypt($request->password);
        $user->save();

        return $this->successResponse([
            'user' =>$user
        ]);
    }

    /**
     * @OA\Post(
     *   path="/api/login",
     *   tags={"Auth"},
     *   summary="Send Magic Login Url",
     *
     *   @OA\Parameter(
     *          name="email",
     *          in="query",
     *          required=true,
     *          @OA\Schema(type="string")
     *   ),
     *
     *   @OA\Parameter(
     *          name="password",
     *          in="query",
     *          required=true,
     *          @OA\Schema(type="string")
     *   ),
     *
     *   @OA\Response(response=200, description="Success"),
     *   @OA\Response(response=404, description="Validation"),
     *   @OA\Response(response=401, description="Unauthorised")
     *
     * )
     */
    public function login(Request $request){

        $validator = Validator::make($request->all(), [
            'email' => 'email|exists:users',
            'password' => 'required',
        ]);

        if ($validator->fails()):
            $errors = $validator->errors();
            return $this->errorResponse($errors,$errors->first());
        endif;

        $credentials = request(['email', 'password']);

        if (!Auth::attempt($credentials)):
            return $this->errorResponse([],'Authentication failed');
        endif;

        $user = $request->user();

        $token = $user->createToken('angular-app')->accessToken;

        return $this->successResponse([
            'user' =>$user,
            'token' =>$token
        ]);
    }

    /**
     * @OA\Get(
     *   path="/api/logout",
     *   tags={"Auth"},
     *   summary="Logout User",
     *
     *   security={
     *      {"JWT": {}}
     *   },
     *
     *
     *   @OA\Response(response=200, description="Success"),
     *   @OA\Response(response=404, description="Validation"),
     *   @OA\Response(response=401, description="Unauthorised"),
     *
     * )
     */

    public function logout(Request $request)
    {
        $authUser = Auth::user();

        $token = $authUser->token();
        $token->revoke();

        return $this->successResponse([], 'Successfully logged out');
    }



}
