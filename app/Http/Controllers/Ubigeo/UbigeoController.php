<?php

namespace App\Http\Controllers\Ubigeo;

use App\Http\Controllers\Controller;
use App\Models\Department;
use App\Models\District;
use App\Models\Province;

class UbigeoController extends Controller
{
    public function departamentos()
    {
        $departamentos = Department::whereIn('nomdep', ['Lima', 'Callao'])
            ->select('coddep', 'nomdep')
            ->get();

        return response()->json($departamentos);
    }

    public function provincias($coddep)
    {
        // Si el código de Lima es '15', ajusta según tu base de datos
        if ($coddep === '15') {
            $provincias = Province::where('coddep', $coddep)
                ->where('nompro', 'Lima') // Solo la provincia de Lima
                ->select('coddep', 'codpro', 'nompro')
                ->get();
        } else {
            $provincias = Province::where('coddep', $coddep)
                ->select('coddep', 'codpro', 'nompro')
                ->get();
        }

        return response()->json($provincias);
    }

    public function distritos($coddep, $codpro)
    {
        $distritos = District::where('coddep', $coddep)
            ->where('codpro', $codpro)
            ->select('coddep', 'codpro', 'coddis', 'nomdis')
            ->get();

        return response()->json($distritos);
    }

}
