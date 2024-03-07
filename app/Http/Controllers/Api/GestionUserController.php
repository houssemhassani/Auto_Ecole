<?php

namespace App\Http\Controllers\Api;

use App\Http\Controllers\Controller;
use App\Http\Resources\UserResource;
use App\Models\Candidat;
use App\Models\Monitor;
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
    public function show()
    {
        $email = request()->route('email');
        $user = DB::table('users')->where('email', $email)->first();

        if (!$user) {
            return response()->json(['error' => 'User not found'], 404);
        }

        $userData = [
            'name' => $user->name,
            'email' => $user->email,
        ];

        // Convertir la chaîne JSON en tableau associatif
        $roles = json_decode($user->level, true);

        $additionalData = [];

        foreach ($roles as $role) {
           
            if ($role === 'candidat') {
                // Si l'utilisateur est candidat, on récupère les données de la table candidats
                $candidat = DB::table('candidats')->where('id', $user->id)->first();
              //  dd($candidat);

                if ($candidat) {
                    $candidatData = [
                        'cin' => $candidat->cin,
                        'adresse' => $candidat->adresse,
                        'date_of_birth' => $candidat->date_of_birth,
                        'num_tel' => $candidat->num_tel,
                    ];
                    $additionalData['candidat'] = $candidatData;
                }
            } elseif ($role === 'monitor') {
                // Si l'utilisateur est moniteur, on récupère les données de la table monitors
                $monitor = DB::table('monitors')->where('id', $user->id)->first();
                if ($monitor) {
                    $monitorData = [
                        'num_professional' => $monitor->num_professional,
                        'num_cin' => $monitor->num_cin,
                    ];
                    $additionalData['monitor'] = $monitorData;
                }
            }
        }
        $data['user'] = $userData;
        $data['autre'] = $additionalData;

        return response()->json($data);
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
