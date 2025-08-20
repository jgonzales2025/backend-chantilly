<?php

namespace App\Http\Controllers\Ubigeo;

use App\Http\Controllers\Controller;
use App\Models\Department;
use App\Models\District;
use App\Models\Province;
use Illuminate\Http\Request;

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
        $provincias = Province::where('coddep', $coddep)
            ->select('coddep', 'codpro', 'nompro')
            ->get();

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
