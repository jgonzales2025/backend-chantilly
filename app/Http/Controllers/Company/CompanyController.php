<?php

namespace App\Http\Controllers\Company;

use App\Http\Controllers\Controller;
use App\Models\Company;
use Illuminate\Http\JsonResponse;
use Illuminate\Http\Request;

class CompanyController extends Controller
{
    public function index(): JsonResponse
    {
        $companies = Company::all();

        if ($companies->isEmpty()){
            return new JsonResponse(['message' => 'No hay compañias registradas']);
        }

        return new JsonResponse($companies, 200);
    }
}
