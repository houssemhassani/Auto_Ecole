<?php

namespace App\Http\Controllers\admin;

use App\Exports\VehiculesExport;
use App\Http\Controllers\Controller;
use App\Models\Monitor;
use App\Models\Vehicule;
use League\Csv\Writer;
use Maatwebsite\Excel\Facades\Excel;
use Barryvdh\DomPDF\Facade as PDF;
use Illuminate\Http\Request;
use Illuminate\Support\Facades\DB;

class VehiculeController extends Controller
{
    /**
     * Récupère la liste des véhicules.
     *
     * @return \Illuminate\Http\JsonResponse
     */
    public function index()
    {
        $vehicles = Vehicule::all();
        return response()->json(['vehicules' => $vehicles], 200);
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

    /**
     * Crée un nouveau véhicule.
     *
     * @param  \Illuminate\Http\Request  $request
     * @return \Illuminate\Http\JsonResponse
     */
    public function store(Request $request)
    {
        $request->validate([
            'brand' => 'required|string',
            'model' => 'required|string',
            'year' => 'required|integer',
            'registration_number' => 'required|string|unique:vehicules',
            'monitor_id' => 'required|exists:monitors,id',
            // 'image' => 'nullable|image|mimes:jpeg,png,jpg,gif,svg|max:2048',
        ]);

        $vehicle = new Vehicule([
            'brand' => $request->input('brand'),
            'model' => $request->input('model'),
            'year' => $request->input('year'),
            'registration_number' => $request->input('registration_number'),
            'monitor_id' => $request->input('monitor_id'),
        ]);

        // Enregistrer l'image si elle est présente
        if ($request->hasFile('image')) {
            $imagePath = $request->file('image')->store('images/vehicules');
            $vehicle->image = $imagePath;
        }

        $vehicle->save();

        return response()->json(['message' => 'Véhicule ajouté avec succès'], 201);
    }

    /**
     * Affiche les détails d'un véhicule spécifique.
     *
     * @param  int  $id
     * @return \Illuminate\Http\JsonResponse
     */
    public function show($id)
    {
        $vehicule = Vehicule::findOrFail($id);
        $details['vehicule'] = $vehicule;

        $monitorDetails = DB::table('monitors')
            ->join('users', 'monitors.id', '=', 'users.id')
            ->select('users.name', 'monitors.num_cin', 'monitors.num_professional')
            ->where('monitors.id', $vehicule->monitor_id)
            ->first();
        if ($monitorDetails) {
            $details['monitor'] = [
                'name' => $monitorDetails->name,
                'num_cin' => $monitorDetails->num_cin,
                'num_professional' => $monitorDetails->num_professional,
            ];
        }

        return response()->json(['details' => $details], 200);
    }

    /**
     * Met à jour les informations d'un véhicule.
     *
     * @param  \Illuminate\Http\Request  $request
     * @param  int  $id
     * @return \Illuminate\Http\JsonResponse
     */
    public function update(Request $request, $id)
    {
        $request->validate([
            'brand' => 'required|string',
            'model' => 'required|string',
            'year' => 'required|integer',
            'registration_number' => 'required|string|unique:vehicles,registration_number,' . $id,
            'monitor_id' => 'required|exists:monitors,id',
            'image.*' => 'nullable|image|mimes:jpeg,png,jpg,gif,svg|max:2048',
        ]);
        $vehicle = Vehicule::findOrFail($id);
        $vehicle->brand = $request->input('brand');
        $vehicle->model = $request->input('model');
        $vehicle->year = $request->input('year');
        $vehicle->registration_number = $request->input('registration_number');
        $vehicle->monitor_id = $request->input('monitor_id');

        
        if ($request->hasFile('image')) {
            $imagePath = $request->file('image')->store('images/vehicules');
            $vehicle->image = $imagePath;
        }

        $vehicle->save();

        return response()->json(['message' => 'Véhicule mis à jour avec succès'], 200);
    }

    /**
     * Associe un véhicule à un moniteur spécifique.
     *
     * @param  int  $vehicleId
     * @param  int  $monitorId
     * @return \Illuminate\Http\JsonResponse
     */
    public function assignToMonitor($vehicleId, $monitorId)
    {
        $vehicle = Vehicule::findOrFail($vehicleId);
        $monitor = Monitor::findOrFail($monitorId);

        if ($vehicle->monitor_id === null) {
            $vehicle->monitor_id = $monitorId;
            $vehicle->save();
            return response()->json(['message' => 'Véhicule associé au moniteur avec succès'], 200);
        } else {
            return response()->json(['message' => 'Le véhicule est déjà associé à un moniteur'], 400);
        }
    }

    /**
     * Désassocie un véhicule d'un moniteur spécifique.
     *
     * @param  int  $vehicleId
     * @param  int  $monitorId
     * @return \Illuminate\Http\JsonResponse
     */
    public function unassignFromMonitor($vehicleId, $monitorId)
    {
        $vehicle = Vehicule::findOrFail($vehicleId);

        if ($vehicle->monitor_id === $monitorId) {
            $vehicle->monitor_id = null;
            $vehicle->save();
            return response()->json(['message' => 'Véhicule désassocié du moniteur avec succès'], 200);
        } else {
            return response()->json(['message' => 'Le véhicule n\'est pas associé à ce moniteur'], 400);
        }
    }
    /**
     * Affiche tous les véhicules associés à un moniteur spécifique.
     *
     * @param  int  $monitorId
     * @return \Illuminate\Http\JsonResponse
     */
    public function getVehiclesByMonitor($monitorId)
    {
        $vehicles = Vehicule::where('monitor_id', $monitorId)->get();
        return response()->json(['vehicules' => $vehicles], 200);
    }

    /**
     * Supprime un véhicule spécifique.
     *
     * @param  int  $id
     * @return \Illuminate\Http\JsonResponse
     */
    public function destroy($id)
    {
        $vehicle = Vehicule::findOrFail($id);
        $vehicle->delete();
        return response()->json(['message' => 'Véhicule supprimé avec succès'], 200);
    }
    /**
     * Recherche et filtre les véhicules en fonction des critères spécifiés.
     *
     * @param  \Illuminate\Http\Request  $request
     * @return \Illuminate\Http\JsonResponse
     */
    public function search(Request $request)
    {

        $request->validate([
            'brand' => 'nullable|string',
            'model' => 'nullable|string',
            'year' => 'nullable|integer',
            'registration_number' => 'nullable|string',

        ]);


        $query = Vehicule::query();

        if ($request->has('brand')) {
            $query->where('brand', $request->brand);
        }


        if ($request->has('model')) {
            $query->where('model', $request->model);
        }


        if ($request->has('year')) {
            $query->where('year', $request->year);
        }


        if ($request->has('registration_number')) {
            $query->where('registration_number', $request->registration_number);
        }

        $query->orderBy('id');
        $results = $query->get();

        // Retourner les résultats de la recherche
        return response()->json(['vehicules' => $results], 200);
    }

    /**
     *
     * @param  \Illuminate\Http\Request  $request
     * @return \Illuminate\Http\Response
     */
    public function exportCsv(Request $request)
    {
        // Vérifier que l'utilisateur est authentifié et a les autorisations nécessaires
        if (!auth()->check() || !auth()->user()->isAdmin()) {
            abort(403, 'Unauthorized');
        }

        // Définir les critères de recherche
        $criteria = $request->only(['brand', 'model', 'year', 'registration_number']);

        // Rechercher les véhicules correspondant aux critères
        $vehicles = Vehicule::where($criteria)->get();

        // Créer un objet Writer CSV
        $csv = Writer::createFromString('');

        // Ajouter les en-têtes CSV
        $csv->insertOne(['ID', 'Marque', 'Modèle', 'Année', 'Numéro d\'immatriculation', 'ID du moniteur']);

        // Ajouter les données des véhicules au CSV
        foreach ($vehicles as $vehicle) {
            $csv->insertOne([
                $vehicle->id,
                $vehicle->brand,
                $vehicle->model,
                $vehicle->year,
                $vehicle->registration_number,
                $vehicle->monitor_id,
            ]);
        }


        $headers = [
            'Content-Type' => 'text/csv',
            'Content-Disposition' => 'attachment; filename="vehicules.csv"',
        ];

        return response()->stream(function () use ($csv) {
            echo $csv->getContent();
        }, 200, $headers);
    }

    /**
     *
     * @param  \Illuminate\Http\Request  $request
     * @return \Illuminate\Http\Response
     */
    public function exportExcel(Request $request)
    {

        if (!auth()->check() || !auth()->user()->isAdmin()) {
            abort(403, 'Unauthorized');
        }


        $criteria = $request->only(['brand', 'model', 'year', 'registration_number']);

        $vehicles = Vehicule::where($criteria)->get();

        return Excel::download(new VehiculesExport($vehicles), 'vehicules.xlsx');
    }

    /**
     * Exporte les résultats de la recherche au format PDF.
     *
     * @param  \Illuminate\Http\Request  $request
     * @return \Illuminate\Http\Response
     */
    public function exportPdf(Request $request)
    {
        // Vérifier les autorisations de l'utilisateur
        if (!auth()->check() || !auth()->user()->isAdmin()) {
            abort(403, 'Unauthorized');
        }

        // Définir les critères de recherche
        $criteria = $request->only(['brand', 'model', 'year', 'registration_number']);

        // Rechercher les véhicules correspondant aux critères
        $vehicles = Vehicule::where($criteria)->get();

        // Générer une vue PDF pour les véhicules
        $pdf = PDF::loadView('pdf.vehicules', ['vehicles' => $vehicles]);

        // Retourner la réponse PDF
        return $pdf->download('vehicules.pdf');
    }
}
