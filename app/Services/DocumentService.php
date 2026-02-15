<?php

namespace App\Services;

use App\Repositories\DocumentRepository;
use App\Models\Document;
use Illuminate\Http\UploadedFile;
use Illuminate\Pagination\LengthAwarePaginator;
use Illuminate\Support\Facades\Storage;

class DocumentService
{
    protected $repository;

    public function __construct(DocumentRepository $repository)
    {
        $this->repository = $repository;
    }

    public function getAllDocuments(int $perPage = 15): LengthAwarePaginator
    {
        return $this->repository->paginate($perPage);
    }

    public function uploadDocument(array $data, UploadedFile $file): Document
    {
        $path = $file->store('documents'); // Default storage driver

        $data['file_path'] = $path;
        $data['file_name'] = $file->getClientOriginalName();
        $data['mime_type'] = $file->getClientMimeType();
        $data['file_size'] = $file->getSize();
        
        // Handle User ID if passed or auth context
        // $data['uploaded_by'] = auth()->id();

        return $this->repository->create($data);
    }

    public function findByUuid(string $uuid): ?Document
    {
        return $this->repository->findByUuid($uuid);
    }

    public function deleteDocument(Document $document): bool
    {
        if (Storage::exists($document->file_path)) {
            Storage::delete($document->file_path);
        }
        return $this->repository->delete($document);
    }
}
