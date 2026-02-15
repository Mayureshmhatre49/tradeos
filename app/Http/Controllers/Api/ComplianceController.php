<?php

namespace App\Http\Controllers\Api;

use App\Http\Controllers\Controller;
use App\Models\Transaction;
use App\Models\SystemActivity;
use Illuminate\Http\Request;
use Illuminate\Http\JsonResponse;
use Illuminate\Support\Facades\DB;

class ComplianceController extends Controller
{
    public function getStats(): JsonResponse
    {
        $avgRiskScore = Transaction::avg('risk_score') ?? 0;
        $totalVolume = Transaction::sum(DB::raw('quantity * unit_price'));
        $flaggedVolume = Transaction::whereIn('risk_level', ['high', 'medium'])
            ->sum(DB::raw('quantity * unit_price'));

        $highRiskPercentage = $totalVolume > 0 ? ($flaggedVolume / $totalVolume) * 100 : 0;

        return response()->json([
            'risk_index' => round($avgRiskScore, 1),
            'total_volume' => $totalVolume,
            'flagged_volume' => $flaggedVolume,
            'flagged_percentage' => round($highRiskPercentage, 1),
        ]);
    }

    public function getFlaggedTransactions(): JsonResponse
    {
        $flagged = Transaction::with(['buyer', 'supplier'])
            ->whereIn('risk_level', ['high', 'medium'])
            ->orderByRaw("FIELD(risk_level, 'high', 'medium')")
            ->orderBy('risk_score', 'desc')
            ->get();

        return response()->json($flagged);
    }

    public function getAuditTrail(): JsonResponse
    {
        $activities = SystemActivity::orderBy('created_at', 'desc')
            ->limit(20)
            ->get();

        return response()->json($activities);
    }

    public function freezeTransaction(Request $request, $uuid): JsonResponse
    {
        $transaction = Transaction::where('uuid', $uuid)->firstOrFail();
        $transaction->update(['status' => 'CLOSED', 'risk_reason' => 'Frozen by Compliance']);

        SystemActivity::create([
            'type' => 'high',
            'title' => 'Transaction Frozen',
            'description' => "Transaction {$transaction->transaction_code} was frozen by compliance officer.",
            'subject_id' => $transaction->id,
            'subject_type' => Transaction::class,
        ]);

        return response()->json(['message' => 'Transaction frozen successfully']);
    }
}
