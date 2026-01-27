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

    public function update(Request $request)
    {
        $request->validate([
            'age' => 'nullable|integer|min:18|max:100',
            'functie' => 'nullable|string|max:255',
        ]);

        $user = $request->user();
        $user->update($request->only(['age', 'functie']));

        return redirect()->back()->with('success', 'Accountgegevens bijgewerkt.');
    }
}
