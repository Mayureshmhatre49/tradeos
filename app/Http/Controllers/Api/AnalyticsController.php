<?php

namespace App\Http\Controllers\Api;

use App\Http\Controllers\Controller;
use App\Services\AnalyticsService;
use Illuminate\Http\JsonResponse;

class AnalyticsController extends Controller
{
    protected $service;

    public function __construct(AnalyticsService $service)
    {
        $this->service = $service;
        $this->middleware('permission:view analytics');
    }

    public function dashboard(): JsonResponse
    {
        return response()->json($this->service->getDashboardStats());
    }

    public function corridors(): JsonResponse
    {
        return response()->json($this->service->getCorridorActivity());
    }
}
