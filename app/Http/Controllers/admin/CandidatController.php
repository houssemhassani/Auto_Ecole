<?php

namespace App\Http\Controllers\admin;

use App\Http\Controllers\Controller;
use App\Mail\TestMail;
use App\Models\Candidat;
use App\Models\Cour;
use App\Models\User;
use Illuminate\Support\Facades\DB;
use Illuminate\Support\Facades\Mail;
use Illuminate\Support\Str;
use Exception;
use Illuminate\Http\Request;
use Illuminate\Support\Facades\Hash;

class CandidatController extends Controller
{
    public function index()
    {

        try {
            $monitors = Candidat::select('candidats.*', 'users.name as user_name', 'users.email as user_email')
                ->leftJoin('users', 'candidats.id', '=', 'users.id')
                ->orderBy('candidats.id', 'DESC')
                ->get();
            $data['candidats'] = $monitors;

            return response()->json($data, 200);
        } catch (Exception $e) {
            return response()->json(['message' => 'Erreur lors de la récupération des moniteurs', 'error' => $e->getMessage()], 422);
        }
    }
    /* public function create()
    {
        $autoecoles = AutoEcole::get();
        $data['autoecoles'] = $autoecoles;
        return response()->json($data, 200);
    } */
    public function store(Request $request)
    {
        try {
            $request->validate([
                'name' => 'required|string',
                'first_name' => 'required|string',
                'last_name' => 'required|string',
                'email' => 'required|email|unique:candidats',
                'password' => 'required|string',
                'cin' => 'required|string|unique:candidats',
                'adresse' => 'nullable|string',
                'date_of_birth' => 'nullable|date',
                'num_tel' => 'nullable|string',
                'cour_id' => 'nullable|exists:cours,id'
            ]);

            // Créer un nouvel utilisateur
            $user = new User();
            $user->name = $request->name;
            $user->email = $request->email;

            $user->password = Hash::make($request->password, [
                'rounds' => 12,
            ]);
            $user->level = json_encode(['candidat']);// Niveau par défaut
            $token = Str::random(60);
            $user->remember_token = hash('sha256', $token);
            $user->save();

            // Générer le jeton d'accès
            $accessToken = $user->createToken('MyApp')->accessToken;

            // Créer un nouveau moniteur
            $monitor = new Candidat();
            $monitor->id = $user->id; // Utilise l'ID de l'utilisateur comme ID du moniteur
            $monitor->num_tel = $request->num_tel;
            $monitor->first_name = $request->first_name;
            $monitor->last_name = $request->last_name;
            $monitor->cin = $request->cin;
            $monitor->email = $request->email;
            $monitor->adresse = $request->adresse;
            $monitor->date_of_birth = $request->date_of_birth;
            $monitor->save();

            // Envoyer un email de bienvenue
            $subject = "Welcome to our App-Auto-Ecole";
            Mail::to($user->email)->send(new TestMail($user, $request->password, $token));

            return response()->json(['message' => 'Utilisateur et moniteur ajoutés avec succès', 'access_token' => $accessToken], 200);
        } catch (Exception $e) {
            return response()->json(['message' => 'Erreur lors de l\'ajout de l\'utilisateur et du moniteur', 'error' => $e->getMessage()], 422);
        }
    }
    public function update(Request $request)
    {
        try {
            $request->validate([
                'cin' => 'required|string',
                'name' => 'required|string',
                'date_of_birth' => 'nullable|date_format:Y-m-d',
                'num_tel' => 'nullable|string',
                'email' => 'required|string|email',
                'address' => 'required|string'
            ]);

            $user = User::where('email', $request->email)->first();

            if (!$user) {
                return response()->json(['message' => 'Candidat non trouvé'], 404);
            }

            $user->name = $request->name;
            $user->save();

            $candidat = Candidat::where('email', $user->email)->first();

            if (!$candidat) {
                return response()->json(['message' => 'Candidat non trouvé'], 404);
            }

            $candidat->num_tel = $request->num_tel;
            $candidat->date_of_birth = $request->date_of_birth;
            $candidat->adresse = $request->address;
            $candidat->save();

            $data['candidat'] = $candidat;
            $data['user'] = $user;

            return response()->json($data, 200);
        } catch (Exception $e) {
            return response()->json(['message' => 'Erreur', 'error' => $e->getMessage()]);
        }
    }

    public function getCoursesWithoutCandidates()
    {
        try {
            // Sélectionner tous les cours qui ne sont pas associés à des candidats via la table de liaison candidat_course
            $courses = Cour::whereDoesntHave('candidats')
                ->orderByRaw('ABS(DATEDIFF(NOW(), date_debut))')
                ->get();

            return response()->json(['courses' => $courses], 200);
        } catch (Exception $e) {
            return response()->json(['message' => 'Erreur lors de la récupération des cours non assignés', 'error' => $e->getMessage()], 422);
        }
    }
    public function getCoursesByCandidateId(string $candidatId)
    {
        try {
            // Recherche du candidat par son ID
            $candidat = Candidat::findOrFail($candidatId);

            // Sélectionner tous les cours associés au candidat et les trier par date de début
            $courses = $candidat->cours()->orderBy('date_debut')->get();

            return response()->json(['courses' => $courses], 200);
        } catch (Exception $e) {
            return response()->json(['message' => 'Erreur lors de la récupération des cours du candidat', 'error' => $e->getMessage()], 422);
        }
    }
    public function assignCourse(Request $request, string $candidatId)
    {
        try {
            $request->validate([
                'cour_id' => 'required|exists:cours,id',
            ]);

            // Recherche du candidat par son ID
            $candidat = Candidat::findOrFail($candidatId);

            // Attachement du cours au candidat
            $candidat->cours()->attach($request->cour_id);

            // Vous pouvez également envoyer un e-mail contenant les détails du cours assigné au candidat ici

            return response()->json(['message' => 'Le cours a été assigné avec succès au candidat'], 200);
        } catch (Exception $e) {
            return response()->json(['message' => 'Erreur lors de l\'assignation du cours au candidat', 'error' => $e->getMessage()], 422);
        }
    }

    public function show(string $id)
    {
        try {
            $monitor = Candidat::findOrFail($id);
            $user = User::findOrFail($monitor->id); // Récupérer l'utilisateur associé au 
            // $autoecole = AutoEcole::findOrFail($monitor->auto_ecole_id);

            $data = [
                'name' => $user->name,
                'email' => $user->email,
                'date_of_birth' => $monitor->date_of_birth,
                'adresse' => $monitor->adresse,
                'num_tel' => $monitor->num_tel,
                'cin' => $monitor->cin,
                //'auto_ecole_name' => $autoecole->name
            ];

            return response()->json($data, 200);
        } catch (Exception $e) {
            return response()->json(['message' => 'Erreur lors de la récupération du moniteur', 'error' => $e->getMessage()], 422);
        }

    }
    public function destroy(string $id)
    {
        try {
            // Supprimer le moniteur
            Candidat::where('id', $id)->delete();

            // Supprimer l'utilisateur associé
            User::where('id', $id)->delete();

            // Supprimer les jetons d'accès associés
            DB::table('personal_access_tokens')->where('tokenable_id', $id)->delete();

            return response()->json(['message' => 'Le moniteur a été supprimé avec succès'], 200);
        } catch (Exception $e) {
            return response()->json(['message' => 'Erreur lors de la suppression du moniteur', 'error' => $e->getMessage()], 422);
        }
    }
}
