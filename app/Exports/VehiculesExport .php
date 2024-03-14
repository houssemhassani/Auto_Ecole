<?php

namespace App\Exports;

use Maatwebsite\Excel\Concerns\FromCollection;
use Maatwebsite\Excel\Concerns\WithHeadings;

class VehiculesExport implements FromCollection, WithHeadings
{
    protected $vehicules;

    public function __construct($vehicules)
    {
        $this->vehicules = $vehicules;
    }

    /**
     * @return \Illuminate\Support\Collection
     */
    public function collection()
    {
        return $this->vehicules;
    }

    /**
     * @return array
     */
    public function headings(): array
    {
        return [
            'ID',
            'Marque',
            'Modèle',
            'Année',
            'Numéro d\'immatriculation',
            'ID du moniteur',
        ];
    }
}
