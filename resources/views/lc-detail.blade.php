@extends('layouts.app')

@section('title', 'LC Details')

@section('content')
<div class="min-h-screen bg-background-light dark:bg-background-dark">
    <!-- Header Section -->
    <header class="bg-white dark:bg-surface-dark border-b border-slate-200 dark:border-border-dark p-6">
        <div class="max-w-7xl mx-auto">
            <div class="flex flex-col md:flex-row md:items-center justify-between gap-4">
                <div>
                    <a href="/lcs" class="text-slate-400 hover:text-primary flex items-center gap-1 text-sm mb-3 transition-colors">
                        <span class="material-icons text-sm">arrow_back</span>
                        Back to LCs
                    </a>
                    <div class="flex items-center gap-4">
                        <h1 id="lcNumber" class="text-3xl font-bold tracking-tight dark:text-white">Loading...</h1>
                        <span id="statusBadge" class="px-3 py-1 bg-slate-100 dark:bg-slate-800 text-slate-600 text-sm font-semibold rounded-full flex items-center gap-1.5">
                            <span class="w-2 h-2 bg-slate-400 rounded-full"></span>
                            Loading
                        </span>
                    </div>
                </div>
                <div class="flex items-center gap-3">
                    <button onclick="window.print()" class="px-4 py-2 border border-slate-200 dark:border-slate-700 rounded-lg hover:bg-slate-50 dark:hover:bg-slate-800 font-medium transition-all text-sm flex items-center gap-2 dark:text-white">
                        <span class="material-icons text-sm">print</span> Print LC
                    </button>
                </div>
            </div>
        </div>
    </header>

    <main class="max-w-7xl mx-auto p-6 space-y-8">
        <div class="grid grid-cols-1 lg:grid-cols-12 gap-8">
            <!-- Left Column (70%) -->
            <div class="lg:col-span-8 space-y-8">
                <!-- LC Details -->
                <section class="bg-white dark:bg-surface-dark border border-slate-200 dark:border-border-dark rounded-xl overflow-hidden">
                    <div class="px-6 py-4 border-b border-slate-200 dark:border-border-dark flex justify-between items-center">
                        <h2 class="font-bold flex items-center gap-2 dark:text-white">
                            <span class="material-icons text-primary text-xl">description</span>
                            LC Information
                        </h2>
                    </div>
                    <div id="lcInfo" class="p-6 grid grid-cols-2 md:grid-cols-3 gap-y-6 gap-x-8">
                        <div class="col-span-3 text-center text-slate-400">Loading LC details...</div>
                    </div>
                </section>

                <!-- Banking Details -->
                <section class="bg-white dark:bg-surface-dark border border-slate-200 dark:border-border-dark rounded-xl overflow-hidden">
                    <div class="px-6 py-4 border-b border-slate-200 dark:border-border-dark">
                        <h2 class="font-bold flex items-center gap-2 dark:text-white">
                            <span class="material-icons text-primary text-xl">account_balance</span>
                            Banking Details
                        </h2>
                    </div>
                    <div id="bankingInfo" class="p-6">
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
                <!-- Amount Card -->
                <section class="bg-gradient-to-br from-green-600 to-green-800 text-white rounded-xl p-6 shadow-lg">
                    <div class="flex items-center justify-between mb-4">
                        <h3 class="text-sm font-semibold uppercase tracking-wider opacity-90">LC Amount</h3>
                        <span class="material-icons opacity-75">attach_money</span>
                    </div>
                    <p id="lcAmount" class="text-3xl font-black mb-1">$0.00</p>
                    <p class="text-sm opacity-75">Total Value</p>
                </section>

                <!-- Expiry Warning -->
               <section class="bg-white dark:bg-surface-dark border border-slate-200 dark:border-border-dark rounded-xl p-6">
                    <h3 class="font-bold mb-4 dark:text-white">Key Dates</h3>
                    <div class="space-y-4">
                        <div class="flex justify-between items-center pb-3 border-b border-slate-100 dark:border-slate-800">
                            <span class="text-sm text-slate-500 dark:text-slate-400">Issue Date</span>
                            <span id="issueDate" class="text-sm font-semibold dark:text-white">--</span>
                        </div>
                        <div class="flex justify-between items-center pb-3 border-b border-slate-100 dark:border-slate-800">
                            <span class="text-sm text-slate-500 dark:text-slate-400">Expiry Date</span>
                            <span id="expiryDate" class="text-sm font-semibold dark:text-white">--</span>
                        </div>
                        <div id="daysUntilExpiry" class="p-3 bg-amber-50 dark:bg-amber-900/20 rounded-lg border border-amber-200 dark:border-amber-800">
                            <p class="text-xs text-amber-600 dark:text-amber-400 font-semibold">Days Until Expiry</p>
                            <p class="text-2xl font-bold text-amber-700 dark:text-amber-300">--</p>
                        </div>
                    </div>
                </section>

                <!-- LC Details Sidebar -->
                <section class="bg-white dark:bg-surface-dark border border-slate-200 dark:border-border-dark rounded-xl p-6">
                    <h3 class="font-bold mb-4 dark:text-white">Additional Info</h3>
                    <div class="space-y-3">
                        <div>
                            <p class="text-xs text-slate-500 dark:text-slate-400 mb-1">Currency</p>
                            <p id="currency" class="text-sm font-semibold dark:text-white">--</p>
                        </div>
                        <div>
                            <p class="text-xs text-slate-500 dark:text-slate-400 mb-1">Payment Terms</p>
                            <p id="paymentTerms" class="text-sm font-semibold dark:text-white">--</p>
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
    let lcData = null;

    async function loadLCDetail() {
        try {
            console.log('Loading LC UUID:', uuid);
            const response = await window.api.get(`/lcs/${uuid}`);
            console.log('LC response:', response);
            lcData = response.data;
            
            if (!lcData) {
                throw new Error('No LC data received from API');
            }
            
            renderLCDetails(lcData);
            loadRelatedData();
        } catch (error) {
            console.error('Error loading LC:', error);
            console.error('Error details:', error.response);
            
            const errorMsg = error.response?.data?.message || error.message || 'Unknown error';
            document.getElementById('lcInfo').innerHTML = `
                <div class="col-span-3 text-center p-8">
                    <div class="text-red-500 mb-4">
                        <span class="material-icons text-5xl">error_outline</span>
                    </div>
                    <h3 class="text-lg font-bold mb-2">Failed to load LC</h3>
                    <p class="text-slate-500 mb-4">${errorMsg}</p>
                    <p class="text-sm text-slate-400 mb-4">UUID: ${uuid}</p>
                    <button onclick="window.location.href='/lcs'" class="px-4 py-2 bg-primary text-white rounded-lg">
                        Back to LCs
                    </button>
                </div>
            `;
        }
    }

    function renderLCDetails(data) {
        console.log('Rendering LC data:', data);
        
        // Update header
        document.getElementById('lcNumber').textContent = data.lc_number || data.uuid?.substring(0, 8) || 'N/A';
        
        // Update status badge
        const statusBadge = document.getElementById('statusBadge');
        const statusConfig = getStatusConfig(data.status);
        statusBadge.className = `px-3 py-1 ${statusConfig.bg} ${statusConfig.text} text-sm font-semibold rounded-full flex items-center gap-1.5`;
        statusBadge.innerHTML = `
            <span class="w-2 h-2 ${statusConfig.dot} rounded-full animate-pulse"></span>
            ${data.status || 'Unknown'}
        `;

        // Update LC info
        const infoHtml = `
            <div>
                <p class="text-[11px] uppercase tracking-wider font-bold text-slate-400 mb-1">LC Number</p>
                <p class="font-semibold font-mono dark:text-white">${data.lc_number || 'N/A'}</p>
            </div>
            <div>
                <p class="text-[11px] uppercase tracking-wider font-bold text-slate-400 mb-1">Amount</p>
                <p class="font-semibold text-lg text-primary">${formatCurrency(data.amount)}</p>
            </div>
            <div>
                <p class="text-[11px] uppercase tracking-wider font-bold text-slate-400 mb-1">Currency</p>
                <p class="font-semibold dark:text-white">${data.currency || 'USD'}</p>
            </div>
            <div>
                <p class="text-[11px] uppercase tracking-wider font-bold text-slate-400 mb-1">Issue Date</p>
                <p class="font-semibold dark:text-white">${formatDate(data.issue_date)}</p>
            </div>
            <div>
                <p class="text-[11px] uppercase tracking-wider font-bold text-slate-400 mb-1">Expiry Date</p>
                <p class="font-semibold dark:text-white">${formatDate(data.expiry_date)}</p>
            </div>
            <div>
                <p class="text-[11px] uppercase tracking-wider font-bold text-slate-400 mb-1">Status</p>
                <span class="px-2 py-1 rounded text-xs font-semibold ${statusConfig.bg} ${statusConfig.text}">${data.status || 'N/A'}</span>
            </div>
        `;
        document.getElementById('lcInfo').innerHTML = infoHtml;

        // Update banking info
        const bankingHtml = `
            <div class="grid grid-cols-1 md:grid-cols-2 gap-6">
                <div class="space-y-4">
                    <div class="flex justify-between border-b border-slate-100 dark:border-slate-800 pb-2">
                        <span class="text-sm text-slate-500 dark:text-slate-400">Issuing Bank</span>
                        <span class="text-sm font-semibold dark:text-white">${data.issuing_bank || 'N/A'}</span>
                    </div>
                    <div class="flex justify-between border-b border-slate-100 dark:border-slate-800 pb-2">
                        <span class="text-sm text-slate-500 dark:text-slate-400">Advising Bank</span>
                        <span class="text-sm font-semibold dark:text-white">${data.advising_bank || 'N/A'}</span>
                    </div>
                </div>
                <div class="space-y-4">
                    <div class="flex justify-between border-b border-slate-100 dark:border-slate-800 pb-2">
                        <span class="text-sm text-slate-500 dark:text-slate-400">Confirming Bank</span>
                        <span class="text-sm font-semibold dark:text-white">${data.confirming_bank || 'N/A'}</span>
                    </div>
                    <div class="flex justify-between border-b border-slate-100 dark:border-slate-800 pb-2">
                        <span class="text-sm text-slate-500 dark:text-slate-400">Payment Terms</span>
                        <span class="text-sm font-semibold dark:text-white">${data.payment_terms || 'N/A'}</span>
                    </div>
                </div>
            </div>
        `;
        document.getElementById('bankingInfo').innerHTML = bankingHtml;

        // Update sidebar
        document.getElementById('lcAmount').textContent = formatCurrency(data.amount);
        document.getElementById('issueDate').textContent = formatDate(data.issue_date);
        document.getElementById('expiryDate').textContent = formatDate(data.expiry_date);
        document.getElementById('currency').textContent = data.currency || 'USD';
        document.getElementById('paymentTerms').textContent = data.payment_terms || 'N/A';
        
        // Calculate days until expiry
        if (data.expiry_date) {
            const daysUntil = Math.ceil((new Date(data.expiry_date) - new Date()) / (1000 * 60 * 60 * 24));
            const expiryDiv = document.getElementById('daysUntilExpiry');
            if (daysUntil < 0) {
                expiryDiv.className = 'p-3 bg-red-50 dark:bg-red-900/20 rounded-lg border border-red-200 dark:border-red-800';
                expiryDiv.innerHTML = `
                    <p class="text-xs text-red-600 dark:text-red-400 font-semibold">EXPIRED</p>
                    <p class="text-2xl font-bold text-red-700 dark:text-red-300">${Math.abs(daysUntil)} days ago</p>
                `;
            } else {
                expiryDiv.querySelector('p:last-child').textContent = daysUntil;
            }
        }
    }

    async function loadRelatedData() {
        if (!lcData.transaction_id) return;
        
        try {
            const txResponse = await window.api.get(`/transactions/${lcData.transaction_id}`);
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
            active: { bg: 'bg-green-100 dark:bg-green-900/30', text: 'text-green-600 dark:text-green-400', dot: 'bg-green-500' },
            pending: { bg: 'bg-slate-100 dark:bg-slate-800', text: 'text-slate-600 dark:text-slate-400', dot: 'bg-slate-400' },
            expired: { bg: 'bg-red-100 dark:bg-red-900/30', text: 'text-red-600 dark:text-red-400', dot: 'bg-red-500' },
            drawn: { bg: 'bg-blue-100 dark:bg-blue-900/30', text: 'text-blue-600 dark:text-blue-400', dot: 'bg-blue-500' }
        };
        return configs[status] || configs.pending;
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
    loadLCDetail();
</script>
@endpush
