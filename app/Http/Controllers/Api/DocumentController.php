<?php

namespace App\Http\Controllers\Api;

use App\Http\Controllers\Controller;
use App\Services\DocumentService;
use App\Models\Document;
use Illuminate\Http\Request;
use Illuminate\Http\JsonResponse;

class DocumentController extends Controller
{
    protected $service;

    public function __construct(DocumentService $service)
    {
        $this->service = $service;
        // Permission middleware if needed
    }

    public function index(): JsonResponse
    {
        return response()->json($this->service->getAllDocuments());
    }

    public function store(Request $request): JsonResponse
    {
        $data = $request->validate([
            'transaction_id' => 'nullable|exists:transactions,id',
            'shipment_id' => 'nullable|exists:shipments,id',
            'lc_record_id' => 'nullable|exists:l_c_records,id',
            'type' => 'required|string',
            'file' => 'required|file|max:10240', // 10MB max
            'uploaded_by' => 'nullable|integer',
        ]);

        return response()->json($this->service->uploadDocument($data, $request->file('file')), 201);
    }

    public function show($uuid): JsonResponse
    {
        $document = $this->service->findByUuid($uuid);
        if (!$document) {
            return response()->json(['message' => 'Document not found'], 404);
        }
        return response()->json($document);
    }

    public function destroy($uuid): JsonResponse
    {
        $document = $this->service->findByUuid($uuid);
        if (!$document) {
            return response()->json(['message' => 'Document not found'], 404);
        }

        $this->service->deleteDocument($document);
        return response()->json(['message' => 'Document deleted']);
    }
}
