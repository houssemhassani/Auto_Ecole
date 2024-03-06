<?php

namespace App\Http\Controllers\admin;

use App\Http\Controllers\Controller;
use App\Models\AutoEcole;
use Illuminate\Http\Request;

class AutoEcoleController extends Controller
{
    /**
     * Display a listing of the resource.
     */
    public function index()
    {
        //
    }

    /**
     * Show the form for creating a new resource.
     */
    public function create()
    {
        //
    }

    /**
     * Store a newly created resource in storage.
     *
     * @param  \Illuminate\Http\Request  $request
     * @return \Illuminate\Http\Response
     */
    public function store(Request $request)
    {
        // Valider les données du formulaire pour l'auto-école
        $request->validate([
            'name' => 'required|string',
            'address' => 'required|string',
            'num_tel' => 'nullable|string',
            'email' => 'nullable|email',
        ]);

        // Créer une nouvelle auto-école
        $autoEcole = AutoEcole::create([
            'name' => $request->input('name'),
            'address' => $request->input('address'),
            'num_tel' => $request->input('num_tel'),
            'email' => $request->input('email'),
        ]);

        // Retourner une réponse avec les informations de l'auto-école créée
        return response()->json([
            'status_code' => 200,
            'status_message' => 'Auto-école enregistrée avec succès',
            'auto_ecole' => $autoEcole
        ]);
    }

    /**
     * Display the specified resource.
     */
    public function show(string $id)
    {
        //
    }

    /**
     * Show the form for editing the specified resource.
     */
    public function edit(string $id)
    {
        //
    }

    /**
     * Update the specified resource in storage.
     */
    public function update(Request $request, string $id)
    {
        //
    }

    /**
     * Remove the specified resource from storage.
     */
    public function destroy(string $id)
    {
        //
    }
}
