<?php

namespace App\Http\Controllers\Complaint;

use App\Http\Controllers\Controller;
use App\Http\Requests\Complaint\StoreComplaintRequest;
use App\Http\Resources\ComplaintResource;
use App\Models\Complaint;
use App\Notifications\ComplaintRegistered;
use Illuminate\Http\JsonResponse;
use Illuminate\Http\Request;
use Illuminate\Support\Facades\Notification;

class ComplaintController extends Controller
{
    public function index(): JsonResponse
    {
        $complaints = Complaint::with('local')->get();

        return new JsonResponse(ComplaintResource::collection($complaints), 200);
    }

    public function store(StoreComplaintRequest $request): JsonResponse
    {
        $data = $request->validated();

        if ($request->hasFile('path_evidence')) {
            $data['path_evidence'] = $request->file('path_evidence')->store('complaints/evidence', 'public');
        }

        if ($request->hasFile('path_customer_signature')) {
            $data['path_customer_signature'] = $request->file('path_customer_signature')->store('complaints/signatures', 'public');
        }

        $complaint = Complaint::create($data);

        Notification::route('mail', $complaint->email)
            ->notify(new ComplaintRegistered($complaint));

        return new JsonResponse(new ComplaintResource($complaint), 201);
    }
}
