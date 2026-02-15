<?php

namespace App\Http\Controllers\Api;

use App\Http\Controllers\Controller;
use App\Services\BuyerService;
use App\Models\Buyer;
use Illuminate\Http\Request;
use Illuminate\Http\JsonResponse;

class BuyerController extends Controller
{
    protected $service;

    public function __construct(BuyerService $service)
    {
        $this->service = $service;
        $this->middleware('permission:manage buyers')->only(['store', 'update', 'destroy']);
    }

    public function index(): JsonResponse
    {
        return response()->json($this->service->getAllBuyers());
    }

    public function store(Request $request): JsonResponse
    {
        $data = $request->validate([
            'company_name' => 'required|string',
            'country_id' => 'nullable|integer',
            'tax_id' => 'nullable|string',
            'ubo_details' => 'nullable|array',
            'risk_level' => 'in:low,medium,high',
        ]);

        return response()->json($this->service->createBuyer($data), 201);
    }

    public function show($uuid): JsonResponse
    {
        $buyer = $this->service->findByUuid($uuid);
        if (!$buyer) {
            return response()->json(['message' => 'Buyer not found'], 404);
        }
        return response()->json($buyer);
    }

    public function update(Request $request, $uuid): JsonResponse
    {
        $buyer = $this->service->findByUuid($uuid);
        if (!$buyer) {
            return response()->json(['message' => 'Buyer not found'], 404);
        }

        $data = $request->validate([
            'company_name' => 'sometimes|string',
            'country_id' => 'nullable|integer',
            'tax_id' => 'nullable|string',
            'ubo_details' => 'nullable|array',
            'risk_level' => 'in:low,medium,high',
            'status' => 'in:pending,approved,rejected,flagged',
        ]);

        return response()->json($this->service->updateBuyer($buyer, $data));
    }

    public function destroy($uuid): JsonResponse
    {
        $buyer = $this->service->findByUuid($uuid);
        if (!$buyer) {
            return response()->json(['message' => 'Buyer not found'], 404);
        }

        $this->service->deleteBuyer($buyer);
        return response()->json(['message' => 'Buyer deleted']);
    }
}
