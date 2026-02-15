@extends('layouts.app')

@section('title', 'Transaction Details')

@section('content')
<div class="min-h-screen bg-background-light dark:bg-background-dark">
    <!-- Header Section -->
    <header class="bg-white dark:bg-surface-dark border-b border-slate-200 dark:border-border-dark p-6">
        <div class="max-w-7xl mx-auto">
            <div class="flex flex-col md:flex-row md:items-center justify-between gap-4">
                <div>
                    <a href="/transactions" class="text-slate-400 hover:text-primary flex items-center gap-1 text-sm mb-3 transition-colors">
                        <span class="material-icons text-sm">arrow_back</span>
                        Back to Transactions
                    </a>
                    <div class="flex items-center gap-4">
                        <h1 id="transactionCode" class="text-3xl font-bold tracking-tight dark:text-white">Loading...</h1>
                        <span id="statusBadge" class="px-3 py-1 bg-slate-100 dark:bg-slate-800 text-slate-600 text-sm font-semibold rounded-full flex items-center gap-1.5">
                            <span class="w-2 h-2 bg-slate-400 rounded-full"></span>
                            Loading
                        </span>
                    </div>
                </div>
                <div class="flex items-center gap-3">
                    <button onclick="window.print()" class="px-4 py-2 border border-slate-200 dark:border-slate-700 rounded-lg hover:bg-slate-50 dark:hover:bg-slate-800 font-medium transition-all text-sm flex items-center gap-2 dark:text-white">
                        <span class="material-icons text-sm">print</span> Print Report
                    </button>
                </div>
            </div>
        </div>
    </header>

    <main class="max-w-7xl mx-auto p-6 space-y-8">
        <!-- Status Timeline -->
        <div class="bg-white dark:bg-surface-dark border border-slate-200 dark:border-border-dark rounded-xl p-8">
            <div class="relative flex items-center justify-between">
                <!-- Progress Line Background -->
                <div class="absolute top-5 left-0 w-full h-1 bg-slate-100 dark:bg-slate-800 -z-10"></div>
                <!-- Progress Line Active (will be updated via JS) -->
                <div id="progressLine" class="absolute top-5 left-0 h-1 bg-primary -z-10" style="width: 0%"></div>
                
                <!-- Timeline stages will be populated by JavaScript -->
                <div id="timeline" class="w-full flex justify-between">
                    <!-- Stages inserted here -->
                </div>
            </div>
        </div>

        <div class="grid grid-cols-1 lg:grid-cols-12 gap-8">
            <!-- Left Column (70%) -->
            <div class="lg:col-span-8 space-y-8">
                <!-- Transaction Details -->
                <section class="bg-white dark:bg-surface-dark border border-slate-200 dark:border-border-dark rounded-xl overflow-hidden">
                    <div class="px-6 py-4 border-b border-slate-200 dark:border-border-dark flex justify-between items-center">
                        <h2 class="font-bold flex items-center gap-2 dark:text-white">
                            <span class="material-icons text-primary text-xl">info</span>
                            Transaction Information
                        </h2>
                    </div>
                    <div id="transactionInfo" class="p-6 grid grid-cols-2 md:grid-cols-3 gap-y-6 gap-x-8">
                        <!-- Will be populated by JavaScript -->
                        <div class="col-span-3 text-center text-slate-400">Loading transaction details...</div>
                    </div>
                </section>

                <!-- Profit Analysis Card -->
                <section class="bg-primary/5 border border-primary/20 rounded-xl overflow-hidden">
                    <div class="px-6 py-4 border-b border-primary/10 flex justify-between items-center">
                        <h2 class="font-bold text-primary flex items-center gap-2">
                            <span class="material-icons text-xl">payments</span>
                            Profit Analysis
                        </h2>
                        <span class="text-xs font-medium text-primary px-2 py-0.5 bg-primary/10 rounded">Live Update</span>
                    </div>
                    <div class="p-6">
                        <div id="profitAnalysis" class="grid grid-cols-1 md:grid-cols-4 gap-6">
                            <!-- Will be populated by JavaScript -->
                        </div>
                    </div>
                </section>

                <!-- Linked LC Information -->
                <section id="lcSection" class="bg-white dark:bg-surface-dark border border-slate-200 dark:border-border-dark rounded-xl overflow-hidden hidden">
                    <div class="px-6 py-4 border-b border-slate-200 dark:border-border-dark">
                        <h2 class="font-bold flex items-center gap-2 dark:text-white">
                            <span class="material-icons text-primary text-xl">description</span>
                            Linked LC Details
                        </h2>
                    </div>
                    <div id="lcDetails" class="p-6">
                        <!-- Will be populated by JavaScript -->
                    </div>
                </section>

                <!-- Shipment Information -->
                <section id="shipmentSection" class="bg-white dark:bg-surface-dark border border-slate-200 dark:border-border-dark rounded-xl overflow-hidden hidden">
                    <div class="px-6 py-4 border-b border-slate-200 dark:border-border-dark">
                        <h2 class="font-bold flex items-center gap-2 dark:text-white">
                            <span class="material-icons text-primary text-xl">local_shipping</span>
                            Shipment Details
                        </h2>
                    </div>
                    <div id="shipmentDetails" class="p-6">
                        <!-- Will be populated by JavaScript -->
                    </div>
                </section>
            </div>

            <!-- Right Column (30%) -->
            <div class="lg:col-span-4 space-y-8">
                <!-- Quick Stats Card -->
                <section class="bg-gradient-to-br from-primary to-blue-700 text-white rounded-xl p-6 shadow-lg">
                    <div class="flex items-center justify-between mb-4">
                        <h3 class="text-sm font-semibold uppercase tracking-wider opacity-90">Contract Value</h3>
                        <span class="material-icons opacity-75">account_balance</span>
                    </div>
                    <p id="contractValue" class="text-3xl font-black mb-1">$0.00</p>
                    <p class="text-sm opacity-75">Total Transaction Value</p>
                </section>

                <!-- Key Metrics -->
                <section class="bg-white dark:bg-surface-dark border border-slate-200 dark:border-border-dark rounded-xl p-6">
                    <h3 class="font-bold mb-4 dark:text-white">Key Metrics</h3>
                    <div class="space-y-4">
                        <div class="flex justify-between items-center pb-3 border-b border-slate-100 dark:border-slate-800">
                            <span class="text-sm text-slate-500 dark:text-slate-400">Net Profit</span>
                            <span id="netProfit" class="text-sm font-semibold text-green-600 dark:text-green-400">$0.00</span>
                        </div>
                        <div class="flex justify-between items-center pb-3 border-b border-slate-100 dark:border-slate-800">
                            <span class="text-sm text-slate-500 dark:text-slate-400">Margin %</span>
                            <span id="marginPercent" class="text-sm font-semibold dark:text-white">0%</span>
                        </div>
                        <div class="flex justify-between items-center pb-3 border-b border-slate-100 dark:border-slate-800">
                            <span class="text-sm text-slate-500 dark:text-slate-400">Quantity</span>
                            <span id="quantity" class="text-sm font-semibold dark:text-white">0</span>
                        </div>
                        <div class="flex justify-between items-center">
                            <span class="text-sm text-slate-500 dark:text-slate-400">Unit Price</span>
                            <span id="unitPrice" class="text-sm font-semibold dark:text-white">$0.00</span>
                        </div>
                    </div>
                </section>

                <!-- Activity Log -->
                <section class="bg-white dark:bg-surface-dark border border-slate-200 dark:border-border-dark rounded-xl flex flex-col h-96">
                    <div class="px-5 py-4 border-b border-slate-200 dark:border-border-dark flex justify-between items-center">
                        <h2 class="font-bold flex items-center gap-2 dark:text-white">
                            <span class="material-icons text-primary text-xl">history</span>
                            Activity Log
                        </h2>
                    </div>
                    <div class="flex-1 overflow-y-auto p-4 space-y-4">
                        <div class="flex gap-3">
                            <div class="w-2 h-2 rounded-full bg-green-500 mt-1.5 flex-shrink-0"></div>
                            <div class="flex-1">
                                <p class="text-xs text-slate-500 dark:text-slate-400 mb-1">Just now</p>
                                <p class="text-sm dark:text-white">Transaction viewed</p>
                            </div>
                        </div>
                    </div>
                </section>
            </div>
        </div>
    </main>
</div>
@endsection

@push('scripts')
<script>
    const uuid = '{{ $uuid }}';
    let transactionData = null;

    async function loadTransactionDetail() {
        try {
            console.log('Loading transaction UUID:', uuid);
            const response = await window.api.get(`/transactions/${uuid}`);
            console.log('Transaction response:', response);
            transactionData = response.data;
            
            if (!transactionData) {
                throw new Error('No transaction data received from API');
            }
            
            renderTransactionDetails(transactionData);
            loadRelatedData();
        } catch (error) {
            console.error('Error loading transaction:', error);
            console.error('Error details:', error.response);
            
            // Show error details but don't redirect immediately
            const errorMsg = error.response?.data?.message || error.message || 'Unknown error';
            document.getElementById('transactionInfo').innerHTML = `
                <div class="col-span-3 text-center p-8">
                    <div class="text-red-500 mb-4">
                        <span class="material-icons text-5xl">error_outline</span>
                    </div>
                    <h3 class="text-lg font-bold mb-2">Failed to load transaction</h3>
                    <p class="text-slate-500 mb-4">${errorMsg}</p>
                    <p class="text-sm text-slate-400 mb-4">UUID: ${uuid}</p>
                    <button onclick="window.location.href='/transactions'" class="px-4 py-2 bg-primary text-white rounded-lg">
                        Back to Transactions
                    </button>
                </div>
            `;
        }
    }

    function renderTransactionDetails(data) {
        console.log('Rendering transaction data:', data);
        
        // Update header
        document.getElementById('transactionCode').textContent = data.transaction_code || data.uuid?.substring(0, 8) || 'N/A';
        
        // Update status badge
        const statusBadge = document.getElementById('statusBadge');
        const statusConfig = getStatusConfig(data.status);
        statusBadge.className = `px-3 py-1 ${statusConfig.bg} ${statusConfig.text} text-sm font-semibold rounded-full flex items-center gap-1.5`;
        statusBadge.innerHTML = `
            <span class="w-2 h-2 ${statusConfig.dot} rounded-full animate-pulse"></span>
            ${data.status || 'Unknown'}
        `;

        // Safely calculate contract value
        const quantity = parseFloat(data.quantity) || 0;
        const unitPrice = parseFloat(data.unit_price) || 0;
        const contractValue = quantity * unitPrice;

        // Update contract value
        document.getElementById('contractValue').textContent = formatCurrency(contractValue);

        // Update transaction info
        const infoHtml = `
            <div>
                <p class="text-[11px] uppercase tracking-wider font-bold text-slate-400 mb-1">Buyer</p>
                <p class="font-semibold dark:text-white">${data.buyer?.company_name || 'N/A'}</p>
                <p class="text-xs text-slate-500">${data.buyer?.country || ''}</p>
            </div>
            <div>
                <p class="text-[11px] uppercase tracking-wider font-bold text-slate-400 mb-1">Supplier</p>
                <p class="font-semibold dark:text-white">${data.supplier?.company_name || 'N/A'}</p>
                <p class="text-xs text-slate-500">${data.supplier?.country || ''}</p>
            </div>
            <div>
                <p class="text-[11px] uppercase tracking-wider font-bold text-slate-400 mb-1">Contract Value</p>
                <p class="font-semibold text-lg text-primary">${formatCurrency(contractValue)}</p>
                <p class="text-xs text-slate-500">Currency: ${data.currency || 'USD'}</p>
            </div>
            <div>
                <p class="text-[11px] uppercase tracking-wider font-bold text-slate-400 mb-1">Product Category</p>
                <p class="font-semibold dark:text-white">${data.product_category || 'N/A'}</p>
            </div>
            <div>
                <p class="text-[11px] uppercase tracking-wider font-bold text-slate-400 mb-1">Payment Type</p>
                <p class="font-semibold dark:text-white">${data.payment_type || 'N/A'}</p>
            </div>
            <div>
                <p class="text-[11px] uppercase tracking-wider font-bold text-slate-400 mb-1">Status</p>
                <span class="px-2 py-1 rounded text-xs font-semibold bg-blue-100 text-blue-800">${data.status || 'N/A'}</span>
            </div>
        `;
        document.getElementById('transactionInfo').innerHTML = infoHtml;

        // Update profit analysis
        const grossProfit = parseFloat(data.calculated_profit) || 0;
        const marginPercent = parseFloat(data.margin_percentage) || 0;
        
        const profitHtml = `
            <div class="bg-white dark:bg-slate-900 p-4 rounded-lg border border-slate-100 dark:border-slate-800 shadow-sm">
                <p class="text-[11px] font-bold text-slate-400 uppercase tracking-widest mb-1">Gross Profit</p>
                <p class="text-xl font-bold dark:text-white">${formatCurrency(grossProfit)}</p>
                <p class="text-xs text-green-500 font-medium">${marginPercent.toFixed(2)}% Margin</p>
            </div>
            <div class="bg-white dark:bg-slate-900 p-4 rounded-lg border border-slate-100 dark:border-slate-800 shadow-sm">
                <p class="text-[11px] font-bold text-slate-400 uppercase tracking-widest mb-1">Contract Value</p>
                <p class="text-xl font-bold dark:text-white">${formatCurrency(contractValue)}</p>
                <p class="text-xs text-slate-400 font-medium">Total Value</p>
            </div>
            <div class="bg-white dark:bg-slate-900 p-4 rounded-lg border border-slate-100 dark:border-slate-800 shadow-sm">
                <p class="text-[11px] font-bold text-slate-400 uppercase tracking-widest mb-1">Quantity</p>
                <p class="text-xl font-bold dark:text-white">${quantity || 0}</p>
                <p class="text-xs text-slate-400 font-medium">Units</p>
            </div>
            <div class="bg-primary text-white p-4 rounded-lg shadow-lg shadow-primary/30">
                <p class="text-[11px] font-bold text-white/70 uppercase tracking-widest mb-1">Unit Price</p>
                <p class="text-2xl font-black">${formatCurrency(unitPrice)}</p>
                <p class="text-xs text-white/80 font-medium">Per Unit</p>
            </div>
        `;
        document.getElementById('profitAnalysis').innerHTML = profitHtml;

        // Update key metrics
        document.getElementById('netProfit').textContent = formatCurrency(grossProfit);
        document.getElementById('marginPercent').textContent = marginPercent.toFixed(2) + '%';
        document.getElementById('quantity').textContent = quantity || 0;
        document.getElementById('unitPrice').textContent = formatCurrency(unitPrice);

        // Update timeline based on status
        renderTimeline(data.status);
    }

    function renderTimeline(status) {
        const stages = [
            { name: 'Draft', status: 'draft', icon: 'edit' },
            { name: 'Active', status: 'active', icon: 'check_circle' },
            { name: 'In Transit', status: 'in_transit', icon: 'local_shipping' },
            { name: 'Completed', status: 'completed', icon: 'done_all' }
        ];

        const currentIndex = stages.findIndex(s => s.status === status);
        const progressPercent = currentIndex >= 0 ? ((currentIndex + 1) / stages.length) * 100 : 0;
        document.getElementById('progressLine').style.width = progressPercent + '%';

        const timelineHtml = stages.map((stage, index) => {
            const isComplete = index < currentIndex;
            const isCurrent = index === currentIndex;
            const isPending = index > currentIndex;

            return `
                <div class="flex flex-col items-center gap-3 bg-white dark:bg-background-dark px-4">
                    <div class="w-10 h-10 rounded-full ${
                        isComplete ? 'bg-primary text-white shadow-lg shadow-primary/30' :
                        isCurrent ? 'border-4 border-primary bg-white dark:bg-slate-900 ring-4 ring-primary/10' :
                        'bg-slate-100 dark:bg-slate-800 border-2 border-slate-200 dark:border-slate-700 text-slate-300'
                    } flex items-center justify-center">
                        ${isCurrent ? 
                            '<div class="w-2.5 h-2.5 bg-primary rounded-full"></div>' :
                            `<span class="material-icons text-base">${stage.icon}</span>`
                        }
                    </div>
                    <div class="text-center">
                        <p class="text-xs font-bold uppercase tracking-wider ${isCurrent ? 'text-primary' : 'text-slate-400'}">${isCurrent ? 'Current' : index < currentIndex ? 'Done' : 'Pending'}</p>
                        <p class="text-sm font-semibold ${isPending ? 'text-slate-400' : 'dark:text-white'}">${stage.name}</p>
                    </div>
                </div>
            `;
        }).join('');

        document.getElementById('timeline').innerHTML = timelineHtml;
    }

    async function loadRelatedData() {
        // Load LC if exists
        try {
            const lcResponse = await window.api.get('/lcs', { params: { transaction_id: transactionData.id } });
            if (lcResponse.data.data && lcResponse.data.data.length > 0) {
                renderLCDetails(lcResponse.data.data[0]);
            }
        } catch (error) {
            console.error('Error loading LC:', error);
        }

        // Load Shipment if exists
        try {
            const shipmentResponse = await window.api.get('/shipments', { params: { transaction_id: transactionData.id } });
            if (shipmentResponse.data.data && shipmentResponse.data.data.length > 0) {
                renderShipmentDetails(shipmentResponse.data.data[0]);
            }
        } catch (error) {
            console.error('Error loading shipment:', error);
        }
    }

    function renderLCDetails(lc) {
        document.getElementById('lcSection').classList.remove('hidden');
        const lcHtml = `
            <div class="grid grid-cols-1 md:grid-cols-2 gap-6">
                <div class="space-y-4">
                    <div class="flex justify-between border-b border-slate-100 dark:border-slate-800 pb-2">
                        <span class="text-sm text-slate-500 dark:text-slate-400">LC Number</span>
                        <span class="text-sm font-semibold font-mono uppercase dark:text-white">${lc.lc_number || 'N/A'}</span>
                    </div>
                    <div class="flex justify-between border-b border-slate-100 dark:border-slate-800 pb-2">
                        <span class="text-sm text-slate-500 dark:text-slate-400">Issuing Bank</span>
                        <span class="text-sm font-semibold dark:text-white">${lc.issuing_bank || 'N/A'}</span>
                    </div>
                    <div class="flex justify-between border-b border-slate-100 dark:border-slate-800 pb-2">
                        <span class="text-sm text-slate-500 dark:text-slate-400">Expiry Date</span>
                        <span class="text-sm font-semibold dark:text-white">${formatDate(lc.expiry_date)}</span>
                    </div>
                </div>
                <div class="space-y-4">
                    <div class="flex justify-between border-b border-slate-100 dark:border-slate-800 pb-2">
                        <span class="text-sm text-slate-500 dark:text-slate-400">Amount</span>
                        <span class="text-sm font-semibold dark:text-white">${formatCurrency(lc.amount)}</span>
                    </div>
                    <div class="flex justify-between border-b border-slate-100 dark:border-slate-800 pb-2">
                        <span class="text-sm text-slate-500 dark:text-slate-400">Status</span>
                        <span class="text-xs font-bold px-2 py-0.5 bg-green-100 text-green-700 rounded-full">${lc.status || 'ACTIVE'}</span>
                    </div>
                </div>
            </div>
        `;
        document.getElementById('lcDetails').innerHTML = lcHtml;
    }

    function renderShipmentDetails(shipment) {
        document.getElementById('shipmentSection').classList.remove('hidden');
        const shipmentHtml = `
            <div class="grid grid-cols-1 md:grid-cols-2 gap-6">
                <div class="space-y-4">
                    <div class="flex justify-between border-b border-slate-100 dark:border-slate-800 pb-2">
                        <span class="text-sm text-slate-500 dark:text-slate-400">Vessel Name</span>
                        <span class="text-sm font-semibold dark:text-white">${shipment.vessel_name || 'N/A'}</span>
                    </div>
                    <div class="flex justify-between border-b border-slate-100 dark:border-slate-800 pb-2">
                        <span class="text-sm text-slate-500 dark:text-slate-400">B/L Number</span>
                        <span class="text-sm font-semibold font-mono dark:text-white">${shipment.bl_number || 'N/A'}</span>
                    </div>
                    <div class="flex justify-between border-b border-slate-100 dark:border-slate-800 pb-2">
                        <span class="text-sm text-slate-500 dark:text-slate-400">Port of Loading</span>
                        <span class="text-sm font-semibold dark:text-white">${shipment.port_of_loading || 'N/A'}</span>
                    </div>
                </div>
                <div class="space-y-4">
                    <div class="flex justify-between border-b border-slate-100 dark:border-slate-800 pb-2">
                        <span class="text-sm text-slate-500 dark:text-slate-400">Port of Discharge</span>
                        <span class="text-sm font-semibold dark:text-white">${shipment.port_of_discharge || 'N/A'}</span>
                    </div>
                    <div class="flex justify-between border-b border-slate-100 dark:border-slate-800 pb-2">
                        <span class="text-sm text-slate-500 dark:text-slate-400">ETA</span>
                        <span class="text-sm font-semibold dark:text-white">${formatDate(shipment.eta)}</span>
                    </div>
                    <div class="flex justify-between border-b border-slate-100 dark:border-slate-800 pb-2">
                        <span class="text-sm text-slate-500 dark:text-slate-400">Risk</span>
                        <span class="text-xs font-bold px-2 py-0.5 ${shipment.risk_flag === 'high' ? 'bg-red-100 text-red-700' : 'bg-green-100 text-green-700'} rounded-full">${shipment.risk_flag || 'LOW'}</span>
                    </div>
                </div>
            </div>
        `;
        document.getElementById('shipmentDetails').innerHTML = shipmentHtml;
    }

    // Utility functions
    function getStatusConfig(status) {
        const configs = {
            draft: { bg: 'bg-slate-100 dark:bg-slate-800', text: 'text-slate-600 dark:text-slate-400', dot: 'bg-slate-400' },
            active: { bg: 'bg-green-100 dark:bg-green-900/30', text: 'text-green-600 dark:text-green-400', dot: 'bg-green-500' },
            in_transit: { bg: 'bg-blue-100 dark:bg-blue-900/30', text: 'text-blue-600 dark:text-blue-400', dot: 'bg-blue-500' },
            completed: { bg: 'bg-purple-100 dark:bg-purple-900/30', text: 'text-purple-600 dark:text-purple-400', dot: 'bg-purple-500' },
            cancelled: { bg: 'bg-red-100 dark:bg-red-900/30', text: 'text-red-600 dark:text-red-400', dot: 'bg-red-500' }
        };
        return configs[status] || configs.draft;
    }

    function getRiskClass(risk) {
        const classes = {
            low: 'bg-green-100 text-green-700',
            medium: 'bg-amber-100 text-amber-700',
            high: 'bg-red-100 text-red-700'
        };
        return classes[risk] || classes.low;
    }

    function formatCurrency(amount) {
        return new Intl.NumberFormat('en-US', {
            style: 'currency',
            currency: 'USD',
            minimumFractionDigits: 2
        }).format(amount || 0);
    }

    function formatDate(date) {
        if (!date) return 'N/A';
        return new Date(date).toLocaleDateString('en-US', {
            year: 'numeric',
            month: 'short',
            day: 'numeric'
        });
    }

    // Load on page load
    loadTransactionDetail();
</script>
@endpush
