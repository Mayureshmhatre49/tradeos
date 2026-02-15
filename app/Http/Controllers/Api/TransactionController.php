<?php

namespace App\Http\Controllers\Api;

use App\Http\Controllers\Controller;
use App\Services\TransactionService;
use App\Models\Transaction;
use Illuminate\Http\Request;
use Illuminate\Http\JsonResponse;

class TransactionController extends Controller
{
    protected $service;

    public function __construct(TransactionService $service)
    {
        $this->service = $service;
        $this->middleware('permission:manage transactions')->only(['store', 'update', 'destroy']);
    }

    public function index(): JsonResponse
    {
        $transactions = $this->service->getAllTransactions();
        
        // Ensure pagination data is loaded with relationships
        if (isset($transactions['data'])) {
            foreach ($transactions['data'] as $transaction) {
                $transaction->load(['buyer', 'supplier']);
            }
        }
        
        return response()->json($transactions);
    }

    public function store(Request $request): JsonResponse
    {
        $data = $request->validate([
            'buyer_id' => 'required|exists:buyers,id',
            'supplier_id' => 'required|exists:suppliers,id',
            'origin_country_id' => 'nullable|integer',
            'destination_country_id' => 'nullable|integer',
            'product_category' => 'nullable|string',
            'quantity' => 'required|numeric',
            'unit_price' => 'required|numeric',
            'margin_percentage' => 'required|numeric',
            'payment_type' => 'required|string',
            'status' => 'in:LOI_RECEIVED,LC_ISSUED,PRODUCTION,SHIPPED,AT_PORT,CLEARED,PAYMENT_REALIZED,CLOSED',
        ]);

        return response()->json($this->service->createTransaction($data), 201);
    }

    public function show($uuid): JsonResponse
    {
        $transaction = $this->service->findByUuid($uuid);
        if (!$transaction) {
            return response()->json(['message' => 'Transaction not found'], 404);
        }
        
        // Load relationships for detail view
        $transaction->load(['buyer', 'supplier']);
        
        return response()->json($transaction);
    }

    public function update(Request $request, $uuid): JsonResponse
    {
        $transaction = $this->service->findByUuid($uuid);
        if (!$transaction) {
            return response()->json(['message' => 'Transaction not found'], 404);
        }

        $data = $request->validate([
            'buyer_id' => 'sometimes|exists:buyers,id',
            'supplier_id' => 'sometimes|exists:suppliers,id',
            'quantity' => 'sometimes|numeric',
            'unit_price' => 'sometimes|numeric',
            'margin_percentage' => 'sometimes|numeric',
            'payment_type' => 'sometimes|string',
            'status' => 'in:LOI_RECEIVED,LC_ISSUED,PRODUCTION,SHIPPED,AT_PORT,CLEARED,PAYMENT_REALIZED,CLOSED',
        ]);

        return response()->json($this->service->updateTransaction($transaction, $data));
    }

    public function destroy($uuid): JsonResponse
    {
        $transaction = $this->service->findByUuid($uuid);
        if (!$transaction) {
            return response()->json(['message' => 'Transaction not found'], 404);
        }

        $this->service->deleteTransaction($transaction);
        return response()->json(['message' => 'Transaction deleted']);
    }
}
