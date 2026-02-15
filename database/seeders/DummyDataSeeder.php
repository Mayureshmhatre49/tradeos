<?php

namespace Database\Seeders;

use Illuminate\Database\Seeder;
use App\Models\Buyer;
use App\Models\Supplier;
use App\Models\Transaction;
use App\Models\LCRecord;
use App\Models\Shipment;
use Illuminate\Support\Str;

class DummyDataSeeder extends Seeder
{
    public function run(): void
    {
        // 1. Buyers & Suppliers
        $buyer1 = Buyer::create([
            'company_name' => 'Global Retail corps',
            'country_id' => 1,
            'risk_level' => 'low',
            'tax_id' => 'TAX-US-001',
        ]);

        $buyer2 = Buyer::create([
            'company_name' => 'Asian Tech Importers',
            'country_id' => 2,
            'risk_level' => 'medium',
            'tax_id' => 'TAX-VN-002',
        ]);

        $supplier1 = Supplier::create([
            'company_name' => 'Shenzhen Electronics Ltd',
            'country_id' => 3,
            'risk_level' => 'low',
            'tax_id' => 'TAX-CN-001',
        ]);

        // 2. Transactions
        $t1 = Transaction::create([
            'buyer_id' => $buyer1->id,
            'supplier_id' => $supplier1->id,
            'origin_country_id' => 3, // China
            'destination_country_id' => 1, // USA (buyer1 is USA based in my mock logic)
            'product_category' => 'Electronics',
            'quantity' => 5000,
            'unit_price' => 120.50,
            'status' => 'LOI_RECEIVED',
            'payment_type' => 'LC_AT_SIGHT',
            'transaction_code' => 'TXN-' . rand(10000, 99999), 
            'calculated_profit' => 15000.00,
            'risk_level' => 'high',
            'risk_score' => 8.5,
            'risk_reason' => 'Sanction Check Fail',
        ]);

        $t2 = Transaction::create([
            'buyer_id' => $buyer2->id,
            'supplier_id' => $supplier1->id,
            'origin_country_id' => 3, // China
            'destination_country_id' => 2, // Angola (buyer2)
            'product_category' => 'Textiles',
            'quantity' => 10000,
            'unit_price' => 15.00,
            'status' => 'SHIPPED',
            'payment_type' => 'TT',
            'transaction_code' => 'TXN-' . rand(10000, 99999),
            'calculated_profit' => 5000.00,
            'risk_level' => 'low',
            'risk_score' => 1.2,
        ]);

        $t3 = Transaction::create([
            'buyer_id' => $buyer1->id,
            'supplier_id' => $supplier1->id,
            'origin_country_id' => 1, // India
            'destination_country_id' => 6, // Switzerland
            'product_category' => 'Machinery',
            'quantity' => 10,
            'unit_price' => 50000.00,
            'status' => 'CLOSED',
            'payment_type' => 'LC_USANCE',
            'transaction_code' => 'TXN-' . rand(10000, 99999),
            'calculated_profit' => 25000.00,
            'created_at' => now()->subMonth(),
            'risk_level' => 'medium',
            'risk_score' => 4.5,
            'risk_reason' => 'UBO Unverified',
        ]);

        // 3. LCs
        LCRecord::create([
            'transaction_id' => $t1->id,
            'lc_number' => 'LC' . rand(1000, 9999),
            'issuing_bank' => 'Bank of America',
            'amount' => 500000.00,
            'expiry_date' => now()->addDays(90),
            'shipment_deadline' => now()->addDays(60),
            'payment_status' => 'PENDING',
        ]);

        // 4. Shipments
        Shipment::create([
            'transaction_id' => $t2->id,
            'port_of_loading' => 'Shanghai',
            'port_of_discharge' => 'Haiphong',
            'vessel_name' => 'Ever Given',
            'bl_number' => 'COSU' . rand(100000, 999999),
            'eta' => now()->addDays(5),
            'risk_flag' => false,
        ]);

        Shipment::create([
            'transaction_id' => $t3->id,
            'port_of_loading' => 'Shenzhen',
            'port_of_discharge' => 'Los Angeles',
            'vessel_name' => 'Maersk Alabama',
            'bl_number' => 'MAEU' . rand(100000, 999999),
            'eta' => now()->subDays(10),
            'risk_flag' => true,
        ]);

        // 5. System Activities
        \App\Models\SystemActivity::create([
            'type' => 'high',
            'title' => 'System Auto-Flag',
            'description' => 'TXN-' . $t1->transaction_code . ' flagged for Sanction List Match (SDN List: ORG-4922).',
            'subject_id' => $t1->id,
            'subject_type' => Transaction::class,
        ]);

        \App\Models\SystemActivity::create([
            'type' => 'normal',
            'title' => 'Rule Engine Triggered',
            'description' => 'Dynamic threshold for corridor IND-AGO exceeded ($2.5M cap).',
        ]);

        \App\Models\SystemActivity::create([
            'type' => 'primary',
            'title' => 'Document AI Analysis',
            'description' => 'Bill of Lading verification complete. Confidence score: 98.2%.',
        ]);

        $this->command->info("Dummy Data Seeded Successfully!");
    }
}
