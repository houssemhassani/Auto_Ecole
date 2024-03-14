<?php

namespace App\Http\Controllers\admin;

use App\Http\Controllers\Controller;
use App\Models\Candidat;
use App\Models\Cour;
use App\Models\Monitor;
use Google\Auth\OAuth2;
use Google\Client as GoogleClient;
use Illuminate\Support\Facades\Validator;
use Carbon\Carbon;
use Illuminate\Http\Request;
use Illuminate\Support\Facades\DB;

class CourController extends Controller
{
    public function index()
    {
        $cours = DB::table("cours")
            ->join('monitors', 'cours.monitor_id', '=', 'monitors.id')
            ->join('users', 'cours.monitor_id', '=', 'users.id')
            ->select(
                'cours.id',
                'cours.titre',
                'cours.description',
                'cours.date_debut',
                'cours.nombre_heures',
                'cours.date_fin',
                'users.name',
                'monitors.num_professional'
            )
            ->orderBy('cours.date_debut')
            ->get();
        $data['cours'] = $cours;
        return response()->json($data, 200);
    }

    public function myCourses(Request $request)
    {
        $candidatId = $request->input('candidat_id');

        $cours = Cour::where(function ($query) use ($candidatId) {
            $query->whereDoesntHave('candidats', function ($query) use ($candidatId) {
                $query->where('candidat_id', $candidatId);
            })->orWhereHas('candidats', function ($query) use ($candidatId) {
                $query->where('candidat_id', $candidatId)->where('type', 'qcm');
            });
        })->where(function ($query) {
            $query->where('nombre_heures', '>=', 8)
                ->orWhere('type', 'pratique')->where('nombre_heures', '>=', 5);
        })->orderBy('date_debut')->get();

        return response()->json(['cours' => $cours], 200);
    }

    public function getCoursesForCandidate(Request $request)
    {
        // Valider les données reçues
        $request->validate([
            'candidat_id' => 'required|exists:candidats,id',
        ]);

        // Récupérer la liste des cours disponibles pour le candidat
        $cours = $this->myCourses($request);

        return response()->json($cours, 200);
    }
    public function finishCourse(Request $request)
    {
        $validator = Validator::make($request->all(), [
            'candidat_id' => 'required|exists:candidats,id',
            'cour_id' => 'required|exists:cours,id',
            'type' => 'required|in:qcm,pratique',
        ]);

        if ($validator->fails()) {
            return response()->json(['error' => $validator->errors()->first()], 400);
        }

        $candidat = Candidat::findOrFail($request->input('candidat_id'));
        $type = $request->input('type');

        $candidat->cours()->updateExistingPivot($request->input('cour_id'), ['type' => $type, 'termine' => true]);

        if ($type === 'qcm') {
            $candidat->increment('seances_theoriques');
        } elseif ($type === 'pratique') {
            $candidat->increment('seances_pratiques');
        }

        return response()->json(['message' => 'Le cours a été terminé avec succès'], 200);
    }


    public function create()
    {
        // Récupérer la liste des monitors
        $monitors = DB::table('monitors')
            ->join('users', 'monitors.id', '=', 'users.id')
            ->select('monitors.id', 'users.name')

            ->get();

        $data['monitors'] = $monitors;

        // Retourner la liste des monitors au format JSON
        return response()->json($data);
    }
    public function store(Request $request)
    {
        // Valider les données reçues
        $request->validate([
            'monitor_id' => 'required|exists:monitors,id',
            'titre' => 'required|string',
            'description' => 'nullable|string',
            'date_debut' => 'required|date',
            'date_fin' => 'nullable|date|after_or_equal:date_debut',
            'nombre_heures' => 'nullable|integer',
            'type' => 'required'
        ]);

        // Créer un nouveau cours avec les données fournies
        $cour = new Cour([
            'monitor_id' => $request->input('monitor_id'),
            'titre' => $request->input('titre'),
            'description' => $request->input('description'),
            'date_debut' => $request->input('date_debut'),
            'date_fin' => $request->input('date_fin'),
            'nombre_heures' => $request->input('nombre_heures'),
            'type' => $request->input('type') // Ajout du type de cours
        ]);

        // Enregistrer le cours
        $cour->save();
        $data['cour'] = $cour;

        return response()->json($data, 200);
    }
    public function edit($id)
    {
        try {
            // Rechercher le cours par son ID
            $cour = Cour::findOrFail($id);

            // Récupérer la liste des monitors
            $monitors = DB::table('monitors')
                ->join('users', 'monitors.id', '=', 'users.id')
                ->select('monitors.id', 'users.name')
                ->get();

            $data['cour'] = $cour;
            $data['monitors'] = $monitors;

            // Retourner une réponse JSON avec les données du cours et la liste des monitors
            return response()->json($data, 200);
        } catch (\Exception $e) {
            // En cas d'erreur, retourner une réponse JSON avec le message d'erreur
            return response()->json(['message' => 'Une erreur est survenue lors de la récupération du cours'], 400);
        }
    }
    public function update(Request $request, $id)
    {
        try {
            // Valider les données reçues
            $request->validate([
                'monitor_id' => 'required|exists:monitors,id',
                'titre' => 'required|string',
                'description' => 'nullable|string',
                'date_debut' => 'required|date',
                'date_fin' => 'nullable|date|after_or_equal:date_debut',
                'nombre_heures' => 'nullable|integer',
            ]);

            // Rechercher le cours par son ID
            $cour = Cour::findOrFail($id);

            // Mettre à jour les données du cours avec les nouvelles données fournies
            $cour->update([
                'monitor_id' => $request->input('monitor_id'),
                'titre' => $request->input('titre'),
                'description' => $request->input('description'),
                'date_debut' => $request->input('date_debut'),
                'date_fin' => $request->input('date_fin'),
                'nombre_heures' => $request->input('nombre_heures'),
            ]);

            // Retourner une réponse JSON pour indiquer que le cours a été mis à jour avec succès
            return response()->json(['message' => 'Le cours a été mis à jour avec succès'], 200);
        } catch (\Exception $e) {
            // En cas d'erreur, retourner une réponse JSON avec le message d'erreur
            return response()->json(['message' => 'Une erreur est survenue lors de la mise à jour du cours'], 400);
        }
    }

    public function destroy($id)
    {
        try {
            // Rechercher le cours par son ID
            $cour = Cour::findOrFail($id);

            // Supprimer le cours
            $result = $cour->delete();
            $data['result'] = $result;

            // Retourner une réponse JSON pour indiquer que le cours a été supprimé avec succès
            return response()->json($data, 200);
        } catch (\Exception $e) {
            // En cas d'erreur, retourner une réponse JSON avec le message d'erreur
            return response()->json(['message' => 'Une erreur est survenue lors de la suppression du cours'], 400);
        }
    }
    public function show($id)
    {
        $cours = DB::table("cours")
            ->where('cours.id', $id)
            ->join('monitors', 'cours.monitor_id', '=', 'monitors.id')
            ->join('users', 'cours.monitor_id', '=', 'users.id')
            ->select(
                'cours.id',
                'cours.titre',
                'cours.description',
                'cours.date_debut',
                'cours.nombre_heures',
                'cours.date_fin',
                'users.name',
                'monitors.num_professional'
            )
            ->orderBy('cours.date_debut')
            ->get();
        $data['cours'] = $cours;
        return response()->json($data, 200);
    }

    public function assignCourseToCandidate(Request $request, $id)
{
    // Valider les données reçues
    $request->validate([
        'candidat_id' => 'required|exists:candidats,id',
        'type' => 'required|in:theorique,pratique', // Validation pour le type de cours
    ]);

    // Rechercher le cours par son ID
    $cour = Cour::findOrFail($id);

    // Vérifier le type de cours et les conditions pour attribuer le cours au candidat
    if ($request->input('type') === 'theorique') {
        // Associer le cours au candidat avec le type 'theorique'
        $cour->candidats()->attach($request->input('candidat_id'), ['type' => 'theorique']);
        // Augmenter le nombre de séances théoriques du candidat
        $candidat = Candidat::findOrFail($request->input('candidat_id'));
        $candidat->seances_theoriques += $cour->nombre_heures;
        $candidat->save();
        return response()->json(['message' => 'Cours attribué avec succès'], 200);
    } elseif ($request->input('type') === 'pratique') {
        // Vérifier si le candidat a fait au minimum 8 séances théoriques
        $candidat = Candidat::findOrFail($request->input('candidat_id'));
        if ($candidat->seances_theoriques >= 8) {
            // Associer le cours au candidat avec le type 'pratique'
            $cour->candidats()->attach($request->input('candidat_id'), ['type' => 'pratique']);
            // Augmenter le nombre de séances pratiques du candidat
            $candidat->seances_pratiques += $cour->nombre_heures;
            $candidat->save();
            return response()->json(['message' => 'Cours attribué avec succès'], 200);
        } else {
            return response()->json(['message' => 'Le candidat n\'a pas suivi assez de séances théoriques pour ce type de cours'], 400);
        }
    } else {
        return response()->json(['message' => 'Type de cours invalide'], 400);
    }
}

}
