<?php

namespace App\Http\Controllers\Api;

use App\Http\Controllers\Controller;
use App\Services\ShipmentService;
use App\Models\Shipment;
use Illuminate\Http\Request;
use Illuminate\Http\JsonResponse;

class ShipmentController extends Controller
{
    protected $service;

    public function __construct(ShipmentService $service)
    {
        $this->service = $service;
        $this->middleware('permission:manage shipments')->only(['store', 'update', 'destroy']);
    }

    public function index(): JsonResponse
    {
        return response()->json($this->service->getAllShipments());
    }

    public function store(Request $request): JsonResponse
    {
        $data = $request->validate([
            'transaction_id' => 'required|exists:transactions,id',
            'port_of_loading' => 'required|string',
            'port_of_discharge' => 'required|string',
            'vessel_name' => 'required|string',
            'bl_number' => 'required|string|unique:shipments,bl_number',
            'insurance_details' => 'nullable|string',
            'eta' => 'required|date',
        ]);

        return response()->json($this->service->createShipment($data), 201);
    }

    public function show($uuid): JsonResponse
    {
        $shipment = $this->service->findByUuid($uuid);
        if (!$shipment) {
            return response()->json(['message' => 'Shipment not found'], 404);
        }
        
        // Load transaction relationship for detail view
        $shipment->load('transaction');
        
        return response()->json($shipment);
    }

    public function update(Request $request, $uuid): JsonResponse
    {
        $shipment = $this->service->findByUuid($uuid);
        if (!$shipment) {
            return response()->json(['message' => 'Shipment not found'], 404);
        }

        $data = $request->validate([
            'port_of_loading' => 'sometimes|string',
            'port_of_discharge' => 'sometimes|string',
            'vessel_name' => 'sometimes|string',
            'bl_number' => 'sometimes|string|unique:shipments,bl_number,' . $shipment->id,
            'insurance_details' => 'nullable|string',
            'eta' => 'sometimes|date',
        ]);

        return response()->json($this->service->updateShipment($shipment, $data));
    }

    public function destroy($uuid): JsonResponse
    {
        $shipment = $this->service->findByUuid($uuid);
        if (!$shipment) {
            return response()->json(['message' => 'Shipment not found'], 404);
        }

        $this->service->deleteShipment($shipment);
        return response()->json(['message' => 'Shipment deleted']);
    }
}
