<?php

namespace App\Http\Controllers\Api;

use App\Http\Controllers\Controller;
use App\Services\LCRecordService;
use App\Models\LCRecord;
use Illuminate\Http\Request;
use Illuminate\Http\JsonResponse;

class LCRecordController extends Controller
{
    protected $service;

    public function __construct(LCRecordService $service)
    {
        $this->service = $service;
        $this->middleware('permission:manage lcs')->only(['store', 'update', 'destroy']);
    }

    public function index(): JsonResponse
    {
        return response()->json($this->service->getAllLCRecords());
    }

    public function store(Request $request): JsonResponse
    {
        $data = $request->validate([
            'transaction_id' => 'required|exists:transactions,id',
            'lc_number' => 'required|string|unique:lc_records,lc_number',
            'issuing_bank' => 'required|string',
            'confirming_bank' => 'nullable|string',
            'expiry_date' => 'required|date',
            'shipment_deadline' => 'nullable|date',
            'amount' => 'required|numeric',
            'discrepancy_notes' => 'nullable|string',
            'payment_status' => 'in:PENDING,PAID,PARTIAL,OVERDUE',
        ]);

        if (empty($data['shipment_deadline'])) {
            $data['shipment_deadline'] = $data['expiry_date'];
        }

        return response()->json($this->service->createLCRecord($data), 201);
    }

    public function show($uuid): JsonResponse
    {
        $lcRecord = $this->service->findByUuid($uuid);
        if (!$lcRecord) {
            return response()->json(['message' => 'LC Record not found'], 404);
        }
        
        // Load transaction relationship for detail view
        $lcRecord->load('transaction');
        
        return response()->json($lcRecord);
    }

    public function update(Request $request, $uuid): JsonResponse
    {
        $lcRecord = $this->service->findByUuid($uuid);
        if (!$lcRecord) {
            return response()->json(['message' => 'LC Record not found'], 404);
        }

        $data = $request->validate([
            'lc_number' => 'sometimes|string|unique:lc_records,lc_number,' . $lcRecord->id,
            'issuing_bank' => 'sometimes|string',
            'confirming_bank' => 'nullable|string',
            'expiry_date' => 'sometimes|date',
            'shipment_deadline' => 'sometimes|date',
            'discrepancy_notes' => 'nullable|string',
            'payment_status' => 'in:PENDING,PAID,PARTIAL,OVERDUE',
        ]);

        return response()->json($this->service->updateLCRecord($lcRecord, $data));
    }

    public function destroy($uuid): JsonResponse
    {
        $lcRecord = $this->service->findByUuid($uuid);
        if (!$lcRecord) {
            return response()->json(['message' => 'LC Record not found'], 404);
        }

        $this->service->deleteLCRecord($lcRecord);
        return response()->json(['message' => 'LC Record deleted']);
    }
}
