<?php

namespace App\Http\Controllers\Api;

use App\Http\Controllers\Controller;
use App\Services\SupplierService;
use App\Models\Supplier;
use Illuminate\Http\Request;
use Illuminate\Http\JsonResponse;

class SupplierController extends Controller
{
    protected $service;

    public function __construct(SupplierService $service)
    {
        $this->service = $service;
        $this->middleware('permission:manage suppliers')->only(['store', 'update', 'destroy']);
    }

    public function index(): JsonResponse
    {
        return response()->json($this->service->getAllSuppliers());
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

        return response()->json($this->service->createSupplier($data), 201);
    }

    public function show($uuid): JsonResponse
    {
        $supplier = $this->service->findByUuid($uuid);
        if (!$supplier) {
            return response()->json(['message' => 'Supplier not found'], 404);
        }
        return response()->json($supplier);
    }

    public function update(Request $request, $uuid): JsonResponse
    {
        $supplier = $this->service->findByUuid($uuid);
        if (!$supplier) {
            return response()->json(['message' => 'Supplier not found'], 404);
        }

        $data = $request->validate([
            'company_name' => 'sometimes|string',
            'country_id' => 'nullable|integer',
            'tax_id' => 'nullable|string',
            'ubo_details' => 'nullable|array',
            'risk_level' => 'in:low,medium,high',
            'status' => 'in:pending,approved,rejected,flagged',
        ]);

        return response()->json($this->service->updateSupplier($supplier, $data));
    }

    public function destroy($uuid): JsonResponse
    {
        $supplier = $this->service->findByUuid($uuid);
        if (!$supplier) {
            return response()->json(['message' => 'Supplier not found'], 404);
        }

        $this->service->deleteSupplier($supplier);
        return response()->json(['message' => 'Supplier deleted']);
    }
}
