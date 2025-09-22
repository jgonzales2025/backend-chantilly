<?php

namespace App\Http\Controllers\PointHistory;

use App\Http\Controllers\Controller;
use App\Models\PointHistory;
use Illuminate\Http\Request;

class PointHistoryController extends Controller
{
    public function index()
    {
        $pointHistories = PointHistory::all();
        return response()->json($pointHistories);
    }
}
