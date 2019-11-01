<?php

namespace App\Http\Controllers\Api;

use App\Http\Controllers\Controller;
use App\Models\User;
use Illuminate\Http\Request;
use Auth;
class UserController extends Controller
{

    /**
     * @OA\Get(
     *   path="/api/profile",
     *   tags={"User"},
     *   summary="Get User Profile",
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

    public function profile(Request $request)
    {
        $authUser = Auth::user();

        return $this->successResponse(['user'=>$authUser]);
    }


    /**
     * @OA\Get(
     *   path="/api/users",
     *   tags={"User"},
     *   summary="Get Users",
     *
     *   security={
     *      {"JWT": {}}
     *   },
     *
     *
     *   @OA\Response(response=200, description="Success"),
     *   @OA\Response(response=400, description="Validation"),
     *   @OA\Response(response=401, description="Unauthorised"),
     *
     * )
     */
    public function getUsers(Request $request)
    {
        $results = User::orderBy('id','desc')->paginate(10);

        return $this->successResponse([
            'users'=>$results->items(),
            'total' => $results->total(),
            'current_page' => $results->currentPage(),
            'last_page' => $results->lastPage(),
        ]);
    }

    /**
     * @OA\Get(
     *   path="/api/user/{id}",
     *   tags={"User"},
     *   summary="Get User",
     *
     *   @OA\Parameter(
     *          name="id",
     *          in="path",
     *          description="User id",
     *          required=true,
     *          @OA\Schema(type="string")
     *   ),
     *
     *   security={
     *      {"JWT": {}}
     *   },
     *
     *
     *   @OA\Response(response=200, description="Success"),
     *   @OA\Response(response=404, description="User not found"),
     *   @OA\Response(response=401, description="Unauthorised"),
     *
     * )
     */
    public function getUser($id)
    {
        $user = User::find($id);

        if(!$user) {
            return $this->errorResponse([], 'User not found',404);
        }

        return $this->successResponse(['user'=>$user]);
    }
}
