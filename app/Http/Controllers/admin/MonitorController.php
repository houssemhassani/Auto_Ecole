<?php

namespace App\Http\Controllers\admin;

use App\Http\Controllers\Controller;
use App\Mail\TestMail;
use App\Models\AutoEcole;
use App\Models\Monitor;
use App\Models\User;
use Exception;
use Hash;
use Illuminate\Support\Facades\DB;
use Illuminate\Support\Facades\Mail;
use Illuminate\Support\Str;
use Illuminate\Http\Request;

class MonitorController extends Controller
{
    public function index()
    {

        try {
            $monitors = Monitor::select('monitors.*', 'users.name as user_name', 'users.email as user_email', 'auto_ecoles.name as auto_ecole_name')
                ->leftJoin('users', 'monitors.id', '=', 'users.id')
                ->leftJoin('auto_ecoles', 'monitors.auto_ecole_id', '=', 'auto_ecoles.id')
                ->orderBy('monitors.id', 'DESC')
                ->get();
            $data['monitors'] = $monitors;

            return response()->json($data, 200);
        } catch (Exception $e) {
            return response()->json(['message' => 'Erreur lors de la récupération des moniteurs', 'error' => $e->getMessage()], 422);
        }
    }
    public function create()
    {
        $autoecoles = AutoEcole::get();
        $data['autoecoles'] = $autoecoles;
        return response()->json($data, 200);
    }
    public function store(Request $request)
    {
        try {
            $request->validate([
                'name' => 'required|string',
                'email' => 'required|email',
                'password' => 'required|string',
                'num_professional' => 'required|string',
                'num_cin' => 'required|string',
                'auto_ecole_id' => 'required|int',
            ]);

            // Créer un nouvel utilisateur
            $user = new User();
            $user->name = $request->name;
            $user->email = $request->email;
            $user->password = Hash::make($request->password, [
                'rounds' => 12,
            ]);
            $user->level = json_encode(['monitor']);// Niveau par défaut
            $token = Str::random(60);
            $user->remember_token = hash('sha256', $token);
            $user->save();

            // Générer le jeton d'accès
            $accessToken = $user->createToken('MyApp')->accessToken;

            // Créer un nouveau moniteur
            $monitor = new Monitor();
            $monitor->id = $user->id; // Utilise l'ID de l'utilisateur comme ID du moniteur
            $monitor->num_professional = $request->num_professional;
            $monitor->num_cin = $request->num_cin;
            $monitor->auto_ecole_id = $request->auto_ecole_id;
            $monitor->save();

            // Envoyer un email de bienvenue
            $subject = "Welcome to our App-Auto-Ecole";
            Mail::to($user->email)->send(new TestMail($user, $request->password, $token));

            return response()->json(['message' => 'Utilisateur et moniteur ajoutés avec succès', 'access_token' => $accessToken], 200);
        } catch (Exception $e) {
            return response()->json(['message' => 'Erreur lors de l\'ajout de l\'utilisateur et du moniteur', 'error' => $e->getMessage()], 422);
        }
    }
    public function show(string $id)
    {
        try {
            $monitor = Monitor::findOrFail($id);
            $user = User::findOrFail($monitor->id); // Récupérer l'utilisateur associé au 
            $autoecole = AutoEcole::findOrFail($monitor->auto_ecole_id);

            $data = [
                'name' => $user->name,
                'email' => $user->email,
                'num_professional' => $monitor->num_professional,
                'num_cin' => $monitor->num_cin,
                'auto_ecole_name' => $autoecole->name
            ];

            return response()->json($data, 200);
        } catch (Exception $e) {
            return response()->json(['message' => 'Erreur lors de la récupération du moniteur', 'error' => $e->getMessage()], 422);
        }

    }
    public function destroy(string $id){
        try {
            // Supprimer le moniteur
            Monitor::where('id', $id)->delete();
    
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
