<?php

namespace App\Http\Controllers\api;

use App\Mail\CodeResetMail;
use App\Models\CodeResetPassword;
use Carbon\Carbon;
use Illuminate\Support\Str;


use App\Http\Controllers\Controller;
use App\Models\User;
use Illuminate\Support\Facades\Log;
use Illuminate\Http\JsonResponse;
use Illuminate\Http\Request;
use Mail;
use App\Mail\TestMail;
use Illuminate\Support\Facades\Hash;
use Illuminate\Support\Facades\Auth;
use App\Http\Requests\RegisterUser;
use App\Http\Requests\LogUserRequest;
use Illuminate\Validation\ValidationException;
use Password;
use Spatie\Permission\Models\Role;
use Spatie\Permission\Traits\HasRoles;

class UserController extends Controller
{

    public function login(LogUserRequest $request)
    {

        if (auth()->attempt($request->only(['email', 'password']))) {
            // L'authentification a réussi
            $user = auth()->user();
            $accessToken = $user->createToken('MyApp')->accessToken;
            $tokens = $user->createToken('MA_CLE_VISIBLE_UNIQUEMENT_AU_BACKEND')
                ->plainTextToken;
            //Auth::login($user);
            return response()->json([
                'status_code' => 200,
                'status_message' => 'User logged in',
                'user' => $user,
                'token' => $tokens
            ]);
        } else {
            // L'authentification a échoué, vous ne devriez pas essayer d'accéder à auth()->user() ici
            return response()->json([
                'status_code' => 401,
                'status_message' => 'Unauthorized',
            ], 401);
        }

    }
    public function store(RegisterUser $request): JsonResponse
    {
        $user = User::create([
            'name' => $request->name,
            'email' => $request->email,
            'password' => Hash::make($request->password, [
                'rounds' => 12,
            ])
        ]); // Assurez-vous de hasher le mot de passe
        // Connecter l'utilisateur après l'enregistrement
        Auth::login($user);

        $token = Str::random(60);
        $user->remember_token = hash('sha256', $token);
        $user->save();

        $accessToken = $user->createToken('MyApp')->accessToken;
        $subject = "Welcome to our App-Auto-Ecole";
        Mail::to($user->email)->send(new TestMail($user, $request->password, $token));

        //$user->save();
        return response()->json([
            'status_code' => 200,
            'status_message' => 'registered user',
            'user' => $user
        ]);
    }
    public function assignRoles(Request $request, $userId)
    {
        try {
            // Récupérer l'utilisateur
            $user = User::findOrFail($userId);

            // Récupérer les niveaux sélectionnés
            $newLevels = $request->input('roles');

            // Assurez-vous que $newLevels est un tableau
            if (!is_array($newLevels)) {
                // Si ce n'est pas un tableau, vous pouvez initialiser $newLevels avec un tableau vide
                $newLevels = [];
            }

            // Ensuite, vous pouvez utiliser $newLevels avec array_merge() en toute sécurité

            // Vérifier si l'utilisateur existe
            if ($user) {
                // Récupérer les niveaux actuels de l'utilisateur
                $currentLevels = $user->level;

                // Ajouter les nouveaux niveaux à la liste des niveaux actuels
                // $currentLevels +=$newLevels;

                $type = gettype($user->level);
                /* return response()->json(['message' => 'Levels added successfully', "" => $newLevels], 200); */
                // Mettre à jour les niveaux de l'utilisateur
                $user->level = $newLevels;
                $user->save();
                
                
                

                // Réponse réussie
                return response()->json(['message' => 'Levels added successfully', "" => $user->level], 200);
            } else {
                // Si l'utilisateur n'est pas trouvé
                return response()->json(['message' => 'User not found'], 404);
            }
        } catch (\Exception $e) {
            // En cas d'erreur, retourner un message d'erreur
            return response()->json(['message' => 'Failed to add levels', 'error' => $e->getMessage()], 500);
        }
    }



    public function logout(Request $request): JsonResponse
    {
        $request->user()->currentAccessToken()->delete();

        return response()->json([
            'status_code' => 200,
            'status_message' => 'User logged out successfully',
        ]);
    }
    public function forgotPassword(Request $request): JsonResponse
    {
        $request->validate([
            'email' => ['required', 'email']
        ]);

        $user = User::where('email', $request->email)->first();

        if (!$user) {
            return response()->json(['message' => 'User not found'], 404);
        }

        $code = $this->generateRandomCode();

        $expiration = now()->addHour();
        CodeResetPassword::where('email', $request->email)->delete();

        $cc = CodeResetPassword::create([
            'email' => $request->email,
            'code' => $code,
            'expires_at' => $expiration
        ]);

        Mail::to($user->email)->send(new CodeResetMail($user, $code));

        return response()->json(['message' => 'Code generated successfully', 'email' => $user->email]);
    }
    public function checkCode(Request $request): JsonResponse
    {
        $request->validate([
            'email' => ['required', 'email'],
            'code' => ['required', 'string'],
        ]);

        // Recherche le code de réinitialisation dans la base de données
        $resetCode = CodeResetPassword::where('email', $request->email)
            ->where('code', $request->code)
            ->first();

        // Vérifie si le code de réinitialisation a été trouvé et si l'expiration est future
        if ($resetCode && $resetCode->expires_at > now()) {
            return response()->json(['message' => 'Code is valid'], 200);
        } else {
            // Si le code n'est pas valide ou a expiré
            throw ValidationException::withMessages([
                'code' => ['Invalid or expired code'],
            ]);
        }
    }
    private function generateRandomCode()
    {
        // Générer un code aléatoire de longueur 8 (vous pouvez ajuster la longueur selon vos besoins)
        $characters = 'abcdefghijklmnopqrstuvwxyzABCDEFGHIJKLMNOPQRSTUVWXYZ0123456789&é()[]=}{#@*-+';
        $code_length = 8;
        $code = '';
        for ($i = 0; $i < $code_length; $i++) {
            $code .= $characters[rand(0, strlen($characters) - 1)];
        }
        return $code;
    }

    public function resetPassword(Request $request): JsonResponse
    {
        $request->validate([
            'email' => 'required|email',
            'password' => 'required|string|min:6',
        ]);

        // Trouver l'utilisateur par e-mail
        $user = User::where('email', $request->email)->first();

        // Vérifier si l'utilisateur existe
        if (!$user) {
            return response()->json(['message' => 'User not found'], 404);
        }

        // Vérifier si le nouveau mot de passe est différent de l'ancien
        if (Hash::check($request->password, $user->password)) {
            return response()->json(['message' => 'New password must be different from the old password'], 400);
        }

        // Mettre à jour le mot de passe de l'utilisateur
        $user->password = Hash::make($request->password);
        $user->save();

        // Générer un nouveau token pour l'utilisateur
        $token = Str::random(60);
        $user->remember_token = hash('sha256', $token); // Suppose que vous avez une méthode pour générer un nouveau token dans votre modèle User

        // Envoyer un e-mail à l'utilisateur pour l'informer du changement de mot de passe
        Mail::to($user->email)->send(new TestMail($user, $request->password, $user->remember_token));

        return response()->json(['message' => 'Password reset successfully']);
    }

    /*  public function login(Request $request): JsonResponse
     {
         
      
         
         // if ($user!=null) {
             if (Hash::check($credentials['password'], $user->password)) {
                 $accessToken = $user->createToken('MyApp')->accessToken;

                 // Marquer l'utilisateur comme connecté
                 //Auth::login($user); 
                 return response()->json([
                     'success' => true,
                     'user' => $user,
                     'access_token' => $accessToken,
                     'message' => 'Logged in successfully.',
                 ]);
             }
         }

         return response()->json([
             'success' => false,
             'message' => 'Invalid credentials.',
         ], 401);
     }  */
}
