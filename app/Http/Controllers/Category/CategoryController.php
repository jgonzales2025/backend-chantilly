<?php

namespace App\Http\Controllers\Category;

use App\Http\Controllers\Controller;
use App\Http\Requests\Category\StoreCategoryRequest;
use App\Http\Requests\Category\UpdateCategoryRequest;
use App\Models\Category;
use Illuminate\Http\JsonResponse;
use Illuminate\Http\Request;

class CategoryController extends Controller
{
    /**
     * Mostrar categorías.
     */
    public function index(): JsonResponse
    {
        $categories = Category::all();

        if($categories->isEmpty()){
            return new JsonResponse(['message' => 'No hay categorías registradas.']);
        }

        return new JsonResponse($categories, 200);
    }

    /**
     * Crear categoría.
     */
    public function store(StoreCategoryRequest $request): JsonResponse
    {
        $validatedData = $request->validated();

        $category = Category::create($validatedData);

        return new JsonResponse(['message' => 'Categoría creada exitosamente', 'category' => $category], 201);
    }

    /**
     * Mostrar categoría por id.
     */
    public function show($id): JsonResponse
    {
        $category = Category::find($id);

        if(!$category){
            return new JsonResponse(['message' => 'Categoría no encontrada'], 404);
        }

        return new JsonResponse($category, 200);
    }

    /**
     * Actualizar categoría por id.
     */
    public function update(UpdateCategoryRequest $request, $id): JsonResponse
    {
        $category = Category::find($id);

        if(!$category){
            return new JsonResponse(['message' => 'Categoría no encontrada'], 404);
        }

        $validatedData = $request->validated();

        $category->update($validatedData);

        return new JsonResponse(['message' => 'Categoría actualizada correctamente', 'category' => $category], 200);
    }

    /**
     * Eliminar categoría por id.
     */
    public function destroy($id): JsonResponse
    {
        $category = Category::find($id);

        if(!$category){
            return new JsonResponse(['message' => 'Categoría no encontrada'], 404);
        }
        
        $category->delete();

        return new JsonResponse(['message' => 'Categoría eliminada correctamente']);
    }
}
