<?php

namespace App\Http\Controllers\Api;

use App\Http\Controllers\Controller;
use App\Http\Resources\UserResource;
use App\Models\User;
use Illuminate\Http\Request;
use Illuminate\Support\Facades\DB;

class GestionUserController extends Controller
{
    /**
     * Display a listing of the resource.
     */
    public function index(Request $request)
    {

        // Assuming you have some way to fetch all users, such as a User model
        $search = $request->input('search');

        // Récupérer les utilisateurs correspondant à la recherche
        $users = User::when($search, function ($query, $search) {
            $query->where('name', 'like', '%' . $search . '%')
                ->orWhere('email', 'like', '%' . $search . '%');
        })->get();

        // Retourner les utilisateurs sous forme de ressources
        return UserResource::collection($users);

        //
    }

    /**
     * Store a newly created resource in storage.
     */
    public function store(Request $request)
    {
        //
    }

    /**
     * Display the specified resource.
     */
    public function show(User $user)
    {
        //
    }

    /**
     * Update the specified resource in storage.
     */
    public function update(Request $request, User $user)
    {
        //
    }

    /**
     * Remove the specified resource from storage.
     */
    public function destroy($userId)
    {

        try {
            // Supprimer l'utilisateur en utilisant son ID
            User::destroy($userId);

            // Supprimer également le token d'accès personnel correspondant
            DB::table('personal_access_tokens')
                ->where('tokenable_id', $userId)
                ->delete();

            return response()->json(['message' => 'User deleted successfully']);
        } catch (\Exception $e) {
            return response()->json(['message' => 'Failed to delete user'], 500);
        }
    }
}
