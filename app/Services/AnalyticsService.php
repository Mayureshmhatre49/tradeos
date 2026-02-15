<?php

namespace App\Services;

use App\Models\Transaction;
use App\Models\Shipment;
use Illuminate\Support\Facades\DB;

class AnalyticsService
{
    public function getDashboardStats(): array
    {
        return [
            'total_trade_volume' => $this->getTotalTradeVolume(),
            'monthly_revenue' => $this->getMonthlyRevenue(),
            'margin_by_corridor' => $this->getMarginByCorridor(),
            // 'buyer_reliability' => $this->getBuyerReliability(), // Placeholder for complex logic
            'risk_distribution' => $this->getRiskDistribution(),
        ];
    }

    protected function getTotalTradeVolume(): float
    {
        return Transaction::sum(DB::raw('quantity * unit_price'));
    }

    protected function getMonthlyRevenue(): array
    {
        // Simple aggregation by month for current year
        return Transaction::select(
            DB::raw('SUM(calculated_profit) as revenue'),
            DB::raw("DATE_FORMAT(created_at, '%Y-%m') as month")
        )
        ->whereYear('created_at', date('Y'))
        ->groupBy('month')
        ->orderBy('month')
        ->get()
        ->toArray();
    }

    protected function getMarginByCorridor(): array
    {
        // Group by Origin -> Destination
        // This requires joining with countries table if names needed, assuming IDs for now or basic grouping
        return Transaction::select(
            'origin_country_id',
            'destination_country_id',
            DB::raw('AVG(margin_percentage) as avg_margin')
        )
        ->groupBy('origin_country_id', 'destination_country_id')
        ->limit(10)
        ->get()
        ->toArray();
    }

    protected function getRiskDistribution(): array
    {
        return [
            'buyers' => \App\Models\Buyer::select('risk_level', DB::raw('count(*) as count'))->groupBy('risk_level')->get(),
            'suppliers' => \App\Models\Supplier::select('risk_level', DB::raw('count(*) as count'))->groupBy('risk_level')->get(),
            'shipments' => Shipment::select('risk_flag', DB::raw('count(*) as count'))->groupBy('risk_flag')->get(),
        ];
    }

    public function getCorridorActivity(): array
    {
        return Transaction::select(
            'origin_country_id',
            'destination_country_id',
            DB::raw('SUM(quantity * unit_price) as volume'),
            DB::raw('COUNT(*) as transaction_count')
        )
        ->whereNotNull('origin_country_id')
        ->whereNotNull('destination_country_id')
        ->groupBy('origin_country_id', 'destination_country_id')
        ->get()
        ->toArray();
    }
}
