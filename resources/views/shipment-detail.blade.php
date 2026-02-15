@extends('layouts.app')

@section('title', 'Shipment Details')

@section('content')
<div class="min-h-screen bg-background-light dark:bg-background-dark">
    <!-- Header Section -->
    <header class="bg-white dark:bg-surface-dark border-b border-slate-200 dark:border-border-dark p-6">
        <div class="max-w-7xl mx-auto">
            <div class="flex flex-col md:flex-row md:items-center justify-between gap-4">
                <div>
                    <a href="/shipments" class="text-slate-400 hover:text-primary flex items-center gap-1 text-sm mb-3 transition-colors">
                        <span class="material-icons text-sm">arrow_back</span>
                        Back to Shipments
                    </a>
                    <div class="flex items-center gap-4">
                        <h1 id="shipmentBL" class="text-3xl font-bold tracking-tight dark:text-white">Loading...</h1>
                        <span id="statusBadge" class="px-3 py-1 bg-slate-100 dark:bg-slate-800 text-slate-600 text-sm font-semibold rounded-full flex items-center gap-1.5">
                            <span class="w-2 h-2 bg-slate-400 rounded-full"></span>
                            Loading
                        </span>
                    </div>
                </div>
                <div class="flex items-center gap-3">
                    <button onclick="window.print()" class="px-4 py-2 border border-slate-200 dark:border-slate-700 rounded-lg hover:bg-slate-50 dark:hover:bg-slate-800 font-medium transition-all text-sm flex items-center gap-2 dark:text-white">
                        <span class="material-icons text-sm">print</span> Print B/L
                    </button>
                </div>
            </div>
        </div>
    </header>

    <main class="max-w-7xl mx-auto p-6 space-y-8">
        <div class="grid grid-cols-1 lg:grid-cols-12 gap-8">
            <!-- Left Column (70%) -->
            <div class="lg:col-span-8 space-y-8">
                <!-- Shipment Details -->
                <section class="bg-white dark:bg-surface-dark border border-slate-200 dark:border-border-dark rounded-xl overflow-hidden">
                    <div class="px-6 py-4 border-b border-slate-200 dark:border-border-dark flex justify-between items-center">
                        <h2 class="font-bold flex items-center gap-2 dark:text-white">
                            <span class="material-icons text-primary text-xl">local_shipping</span>
                            Shipment Information
                        </h2>
                    </div>
                    <div id="shipmentInfo" class="p-6 grid grid-cols-2 md:grid-cols-3 gap-y-6 gap-x-8">
                        <div class="col-span-3 text-center text-slate-400">Loading shipment details...</div>
                    </div>
                </section>

                <!-- Port & Vessel Information -->
                <section class="bg-white dark:bg-surface-dark border border-slate-200 dark:border-border-dark rounded-xl overflow-hidden">
                    <div class="px-6 py-4 border-b border-slate-200 dark:border-border-dark">
                        <h2 class="font-bold flex items-center gap-2 dark:text-white">
                            <span class="material-icons text-primary text-xl">directions_boat</span>
                            Vessel & Route Details
                        </h2>
                    </div>
                    <div id="vesselInfo" class="p-6">
                        <!-- Will be populated by JavaScript -->
                    </div>
                </section>

                <!-- Transaction Link -->
                <section id="transactionSection" class="bg-white dark:bg-surface-dark border border-slate-200 dark:border-border-dark rounded-xl overflow-hidden hidden">
                    <div class="px-6 py-4 border-b border-slate-200 dark:border-border-dark">
                        <h2 class="font-bold flex items-center gap-2 dark:text-white">
                            <span class="material-icons text-primary text-xl">link</span>
                            Linked Transaction
                        </h2>
                    </div>
                    <div id="transactionDetails" class="p-6">
                        <!-- Will be populated by JavaScript -->
                    </div>
                </section>
            </div>

            <!-- Right Column (30%) -->
            <div class="lg:col-span-4 space-y-8">
                <!-- Timeline Card -->
                <section class="bg-gradient-to-br from-blue-600 to-blue-800 text-white rounded-xl p-6 shadow-lg">
                    <div class="flex items-center justify-between mb-4">
                        <h3 class="text-sm font-semibold uppercase tracking-wider opacity-90">Estimated Arrival</h3>
                        <span class="material-icons opacity-75">schedule</span>
                    </div>
                    <p id="etaDisplay" class="text-3xl font-black mb-1">--</p>
                    <p class="text-sm opacity-75">Port of Discharge</p>
                </section>

                <!-- Risk Assessment -->
                <section class="bg-white dark:bg-surface-dark border border-slate-200 dark:border-border-dark rounded-xl p-6">
                    <h3 class="font-bold mb-4 dark:text-white">Risk Assessment</h3>
                    <div class="space-y-4">
                        <div class="flex justify-between items-center pb-3 border-b border-slate-100 dark:border-slate-800">
                            <span class="text-sm text-slate-500 dark:text-slate-400">Risk Flag</span>
                            <span id="riskFlag" class="text-sm font-semibold px-2 py-1 rounded bg-slate-100">--</span>
                        </div>
                        <div class="flex justify-between items-center pb-3 border-b border-slate-100 dark:border-slate-800">
                            <span class="text-sm text-slate-500 dark:text-slate-400">Inspection Required</span>
                            <span id="inspectionRequired" class="text-sm font-semibold dark:text-white">--</span>
                        </div>
                    </div>
                </section>

                <!-- Tracking Info -->
                <section class="bg-white dark:bg-surface-dark border border-slate-200 dark:border-border-dark rounded-xl flex flex-col">
                    <div class="px-5 py-4 border-b border-slate-200 dark:border-border-dark flex justify-between items-center">
                        <h2 class="font-bold flex items-center gap-2 dark:text-white">
                            <span class="material-icons text-primary text-xl">info</span>
                            Tracking Details
                        </h2>
                    </div>
                    <div class="p-4">
                        <div class="space-y-3">
                            <div>
                                <p class="text-xs text-slate-500 dark:text-slate-400 mb-1">Container Number</p>
                                <p id="containerNumber" class="text-sm font-mono font-semibold dark:text-white">--</p>
                            </div>
                            <div>
                                <p class="text-xs text-slate-500 dark:text-slate-400 mb-1">B/L Number</p>
                                <p id="blNumber" class="text-sm font-mono font-semibold dark:text-white">--</p>
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
    let shipmentData = null;

    async function loadShipmentDetail() {
        try {
            console.log('Loading shipment UUID:', uuid);
            const response = await window.api.get(`/shipments/${uuid}`);
            console.log('Shipment response:', response);
            shipmentData = response.data;
            
            if (!shipmentData) {
                throw new Error('No shipment data received from API');
            }
            
            renderShipmentDetails(shipmentData);
            loadRelatedData();
        } catch (error) {
            console.error('Error loading shipment:', error);
            console.error('Error details:', error.response);
            
            const errorMsg = error.response?.data?.message || error.message || 'Unknown error';
            document.getElementById('shipmentInfo').innerHTML = `
                <div class="col-span-3 text-center p-8">
                    <div class="text-red-500 mb-4">
                        <span class="material-icons text-5xl">error_outline</span>
                    </div>
                    <h3 class="text-lg font-bold mb-2">Failed to load shipment</h3>
                    <p class="text-slate-500 mb-4">${errorMsg}</p>
                    <p class="text-sm text-slate-400 mb-4">UUID: ${uuid}</p>
                    <button onclick="window.location.href='/shipments'" class="px-4 py-2 bg-primary text-white rounded-lg">
                        Back to Shipments
                    </button>
                </div>
            `;
        }
    }

    function renderShipmentDetails(data) {
        console.log('Rendering shipment data:', data);
        
        // Update header
        document.getElementById('shipmentBL').textContent = data.bl_number || data.uuid?.substring(0, 8) || 'N/A';
        
        // Update status badge
        const statusBadge = document.getElementById('statusBadge');
        const statusConfig = getStatusConfig(data.status);
        statusBadge.className = `px-3 py-1 ${statusConfig.bg} ${statusConfig.text} text-sm font-semibold rounded-full flex items-center gap-1.5`;
        statusBadge.innerHTML = `
            <span class="w-2 h-2 ${statusConfig.dot} rounded-full animate-pulse"></span>
            ${data.status || 'Unknown'}
        `;

        // Update shipment info
        const infoHtml = `
            <div>
                <p class="text-[11px] uppercase tracking-wider font-bold text-slate-400 mb-1">B/L Number</p>
                <p class="font-semibold font-mono dark:text-white">${data.bl_number || 'N/A'}</p>
            </div>
            <div>
                <p class="text-[11px] uppercase tracking-wider font-bold text-slate-400 mb-1">Container Number</p>
                <p class="font-semibold font-mono dark:text-white">${data.container_number || 'N/A'}</p>
            </div>
            <div>
                <p class="text-[11px] uppercase tracking-wider font-bold text-slate-400 mb-1">Seal Number</p>
                <p class="font-semibold dark:text-white">${data.seal_number || 'N/A'}</p>
            </div>
            <div>
                <p class="text-[11px] uppercase tracking-wider font-bold text-slate-400 mb-1">Departure Date</p>
                <p class="font-semibold dark:text-white">${formatDate(data.departure_date)}</p>
            </div>
            <div>
                <p class="text-[11px] uppercase tracking-wider font-bold text-slate-400 mb-1">ETA</p>
                <p class="font-semibold text-lg text-primary">${formatDate(data.eta)}</p>
            </div>
            <div>
                <p class="text-[11px] uppercase tracking-wider font-bold text-slate-400 mb-1">Status</p>
                <span class="px-2 py-1 rounded text-xs font-semibold ${statusConfig.bg} ${statusConfig.text}">${data.status || 'N/A'}</span>
            </div>
        `;
        document.getElementById('shipmentInfo').innerHTML = infoHtml;

        // Update vessel info
        const vesselHtml = `
            <div class="grid grid-cols-1 md:grid-cols-2 gap-6">
                <div class="space-y-4">
                    <div class="flex justify-between border-b border-slate-100 dark:border-slate-800 pb-2">
                        <span class="text-sm text-slate-500 dark:text-slate-400">Vessel Name</span>
                        <span class="text-sm font-semibold dark:text-white">${data.vessel_name || 'N/A'}</span>
                    </div>
                    <div class="flex justify-between border-b border-slate-100 dark:border-slate-800 pb-2">
                        <span class="text-sm text-slate-500 dark:text-slate-400">Port of Loading</span>
                        <span class="text-sm font-semibold dark:text-white">${data.port_of_loading || 'N/A'}</span>
                    </div>
                    <div class="flex justify-between border-b border-slate-100 dark:border-slate-800 pb-2">
                        <span class="text-sm text-slate-500 dark:text-slate-400">Port of Discharge</span>
                        <span class="text-sm font-semibold dark:text-white">${data.port_of_discharge || 'N/A'}</span>
                    </div>
                </div>
                <div class="space-y-4">
                    <div class="flex justify-between border-b border-slate-100 dark:border-slate-800 pb-2">
                        <span class="text-sm text-slate-500 dark:text-slate-400">Voyage Number</span>
                        <span class="text-sm font-semibold dark:text-white">${data.voyage_number || 'N/A'}</span>
                    </div>
                    <div class="flex justify-between border-b border-slate-100 dark:border-slate-800 pb-2">
                        <span class="text-sm text-slate-500 dark:text-slate-400">Carrier</span>
                        <span class="text-sm font-semibold dark:text-white">${data.carrier || 'N/A'}</span>
                    </div>
                </div>
            </div>
        `;
        document.getElementById('vesselInfo').innerHTML = vesselHtml;

        // Update sidebar
        document.getElementById('etaDisplay').textContent = formatDate(data.eta);
        document.getElementById('containerNumber').textContent = data.container_number || 'N/A';
        document.getElementById('blNumber').textContent = data.bl_number || 'N/A';
        
        const riskSpan = document.getElementById('riskFlag');
        const riskClass = data.risk_flag === 'high' ? 'bg-red-100 text-red-700' : data.risk_flag === 'medium' ? 'bg-amber-100 text-amber-700' : 'bg-green-100 text-green-700';
        riskSpan.className = `text-sm font-semibold px-2 py-1 rounded ${riskClass}`;
        riskSpan.textContent = (data.risk_flag || 'low').toUpperCase();
        
        document.getElementById('inspectionRequired').textContent = data.inspection_required ? 'Yes' : 'No';
    }

    async function loadRelatedData() {
        if (!shipmentData.transaction_id) return;
        
        try {
            const txResponse = await window.api.get(`/transactions/${shipmentData.transaction_id}`);
            if (txResponse.data) {
                renderTransactionLink(txResponse.data);
            }
        } catch (error) {
            console.error('Error loading transaction:', error);
        }
    }

    function renderTransactionLink(tx) {
        document.getElementById('transactionSection').classList.remove('hidden');
        const txHtml = `
            <div class="flex items-center justify-between p-4 bg-slate-50 dark:bg-slate-800 rounded-lg">
                <div>
                    <p class="text-sm font-semibold dark:text-white">${tx.transaction_code || 'Transaction'}</p>
                    <p class="text-xs text-slate-500 dark:text-slate-400">
                        ${tx.buyer?.company_name || 'N/A'} → ${tx.supplier?.company_name || 'N/A'}
                    </p>
                </div>
                <a href="/transactions/${tx.uuid || tx.id}" class="px-3 py-1 bg-primary text-white text-sm rounded-lg hover:bg-primary/90">
                    View Details
                </a>
            </div>
        `;
        document.getElementById('transactionDetails').innerHTML = txHtml;
    }

    function getStatusConfig(status) {
        const configs = {
            pending: { bg: 'bg-slate-100 dark:bg-slate-800', text: 'text-slate-600 dark:text-slate-400', dot: 'bg-slate-400' },
            in_transit: { bg: 'bg-blue-100 dark:bg-blue-900/30', text: 'text-blue-600 dark:text-blue-400', dot: 'bg-blue-500' },
            arrived: { bg: 'bg-green-100 dark:bg-green-900/30', text: 'text-green-600 dark:text-green-400', dot: 'bg-green-500' },
            delayed: { bg: 'bg-amber-100 dark:bg-amber-900/30', text: 'text-amber-600 dark:text-amber-400', dot: 'bg-amber-500' },
            delivered: { bg: 'bg-purple-100 dark:bg-purple-900/30', text: 'text-purple-600 dark:text-purple-400', dot: 'bg-purple-500' }
        };
        return configs[status] || configs.pending;
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
    loadShipmentDetail();
</script>
@endpush
