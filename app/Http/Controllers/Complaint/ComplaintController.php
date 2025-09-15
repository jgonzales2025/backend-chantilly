<?php

namespace App\Http\Controllers\Complaint;

use App\Http\Controllers\Controller;
use App\Http\Requests\Complaint\StoreComplaintRequest;
use App\Http\Resources\ComplaintResource;
use App\Models\Complaint;
use App\Notifications\ComplaintRegistered;
use App\Services\RecaptchaService;
use Illuminate\Http\JsonResponse;
use Illuminate\Support\Facades\Notification;
use Illuminate\Validation\ValidationException;

class ComplaintController extends Controller
{

    protected $recaptchaService;

    public function __construct()
    {
        $this->recaptchaService = new RecaptchaService();
    }

    public function index(): JsonResponse
    {
        $complaints = Complaint::with('local')->get();

        return new JsonResponse(ComplaintResource::collection($complaints), 200);
    }

    /**
     * Obtener el próximo número de queja disponible
     */
    public function getNextComplaintNumber(): JsonResponse
    {
        $nextNumber = Complaint::generateComplaintNumber();
        
        return new JsonResponse([
            'number_complaint' => $nextNumber
        ], 200);
    }

    public function store(StoreComplaintRequest $request): JsonResponse
    {
        $data = $request->validated();

        // Validar el token en Google
        $isValid = $this->recaptchaService->validateToken($data['recaptcha_token']);

        if (!$isValid) {
            throw ValidationException::withMessages([
                'recaptcha' => 'La validación de reCAPTCHA falló.',
            ]);
        }

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
