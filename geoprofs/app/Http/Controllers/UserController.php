<?php

namespace App\Http\Controllers;

use App\Http\Controllers\Controller;
use Illuminate\Http\Request;

class UserController extends Controller
{

    /**
     * @OA\Get(
     *     path="/user",
     *     summary="Get a list of users",
     *     tags={"User"},
     *     @OA\Response(response=200, description="Successful operation"),
     *     @OA\Response(response=400, description="Invalid request")
     * )
     */
    function index(Request $request)
    {
        return $request->user();
    }
}
