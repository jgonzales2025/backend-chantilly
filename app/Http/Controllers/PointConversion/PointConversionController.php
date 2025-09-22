<?php

namespace App\Http\Controllers\PointConversion;

use App\Http\Controllers\Controller;
use App\Models\PointConversion;
use Illuminate\Http\Request;

class PointConversionController extends Controller
{
    public function index()
    {
        $pointConversions = PointConversion::all();
        return response()->json($pointConversions);
    }

    public function store(Request $request)
    {
        $validatedData = $request->validate([
            'soles_to_points' => 'required|integer',
            'points_to_soles' => 'required|integer',
        ]);

        $pointConversion = PointConversion::create($validatedData);

        return response()->json($pointConversion, 201);
    }


    public function show($id)
    {
        $pointConversion = PointConversion::find($id);

        if (!$pointConversion) {
            return response()->json(['message' => 'Point conversion not found'], 404);
        }

        return response()->json($pointConversion);
    }

    public function update(Request $request, $id)
    {
        $pointConversion = PointConversion::find($id);

        if (!$pointConversion) {
            return response()->json(['message' => 'Point conversion not found'], 404);
        }

        $validatedData = $request->validate([
            'soles_to_points' => 'sometimes|integer',
            'points_to_soles' => 'sometimes|integer',
        ]);

        $pointConversion->update($validatedData);

        return response()->json($pointConversion);
    }

    public function destroy($id)
    {
        $pointConversion = PointConversion::find($id);

        if (!$pointConversion) {
            return response()->json(['message' => 'Point conversion not found'], 404);
        }

        $pointConversion->delete();

        return response()->json(['message' => 'Point conversion deleted successfully']);
    }

    public function discountedAmount(Request $request)
    {
         $validatedData = $request->validate([
            'amount' => 'required|numeric|min:0',
            'points' => 'required|integer|min:0',
        ]);

        $amount = $validatedData['amount'];
        $points = $validatedData['points'];

        // Calculate the discounted amount based on the points
        $pointConversion = PointConversion::first(); // Get the first conversion rates
        if (!$pointConversion) {
            return response()->json(['message' => 'Point conversion rates not found'], 404);
        }

        $discountedAmount = $amount - ($points * $pointConversion->points_to_soles);

        return response()->json(['discounted_amount' => $discountedAmount]);
    }
}
