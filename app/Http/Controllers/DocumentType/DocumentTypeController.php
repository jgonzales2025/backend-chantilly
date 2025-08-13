<?php

namespace App\Http\Controllers\DocumentType;

use App\Http\Controllers\Controller;
use App\Models\DocumentType;
use Illuminate\Http\JsonResponse;
use Illuminate\Http\Request;

class DocumentTypeController extends Controller
{

    /**
     * Mostrar tipos de documentos.
     */
    public function index(): JsonResponse
    {
        $documentTypes = DocumentType::all();

        if($documentTypes->isEmpty()){
            return new JsonResponse(['message' => 'No hay tipos de documento registrados']);
        }

        return new JsonResponse($documentTypes, 200);
    }
}
