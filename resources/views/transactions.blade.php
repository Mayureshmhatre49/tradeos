@extends('layouts.app')

@section('title', 'Transaction Management')

@section('content')
<div class="flex min-h-screen relative">
    <!-- Main Content Area - Full Width -->
    <section class="flex-1 flex flex-col p-8">
        <!-- Header -->
        <header class="flex justify-between items-center mb-8">
            <div>
                <h1 class="text-2xl font-bold tracking-tight dark:text-white">Transactions</h1>
                <p class="text-slate-500 dark:text-slate-400 text-sm mt-1">Manage and track your cross-border enterprise trades.</p>
            </div>
            <div class="flex gap-3">
                <button class="px-4 py-2 border border-slate-200 dark:border-slate-700 bg-white dark:bg-slate-800 rounded-lg text-sm font-medium hover:bg-slate-50 dark:hover:bg-slate-700 transition-colors flex items-center gap-2">
                    <span class="material-icons-outlined text-sm">file_download</span> Export
                </button>
                <button onclick="openModal()" class="px-4 py-2 bg-primary text-white rounded-lg text-sm font-medium hover:bg-primary/90 transition-colors flex items-center gap-2">
                    <span class="material-icons-outlined text-sm">add</span> Create New Trade
                </button>
            </div>
        </header>

        <!-- Filters Bar -->
        <div class="bg-white dark:bg-slate-900 p-4 rounded-xl border border-slate-200 dark:border-slate-800 flex flex-wrap items-center gap-4 mb-6 shadow-sm">
            <div class="flex-1 relative">
                <span class="material-icons-outlined absolute left-3 top-1/2 -translate-y-1/2 text-slate-400 text-lg">search</span>
                <input id="searchInput" class="w-full pl-10 pr-4 py-2 bg-slate-50 dark:bg-slate-800 border-none rounded-lg text-sm focus:ring-2 focus:ring-primary/20" placeholder="Search by ID, counterparty, or amount..." type="text"/>
            </div>
            <div class="flex items-center gap-2">
                <select id="statusFilter" class="bg-white dark:bg-slate-800 border border-slate-200 dark:border-slate-700 rounded-lg text-sm py-2 px-3 focus:ring-2 focus:ring-primary/20">
                    <option value="">All Statuses</option>
                    <option value="pending">Pending</option>
                    <option value="in_progress">In Progress</option>
                    <option value="completed">Completed</option>
                    <option value="cancelled">Cancelled</option>
                </select>
                <select id="dateFilter" class="bg-white dark:bg-slate-800 border border-slate-200 dark:border-slate-700 rounded-lg text-sm py-2 px-3 focus:ring-2 focus:ring-primary/20">
                    <option>Last 30 Days</option>
                    <option>Last Quarter</option>
                    <option>Last Year</option>
                    <option>Custom Range</option>
                </select>
                <select id="corridorFilter" class="bg-white dark:bg-slate-800 border border-slate-200 dark:border-slate-700 rounded-lg text-sm py-2 px-3 focus:ring-2 focus:ring-primary/20">
                    <option>All Corridors</option>
                    <option>India → Angola</option>
                    <option>Brazil → China</option>
                    <option>USA → UK</option>
                </select>
                <button class="p-2 text-slate-500 hover:bg-slate-100 dark:hover:bg-slate-800 rounded-lg">
                    <span class="material-icons-outlined">filter_list</span>
                </button>
            </div>
        </div>

        <!-- Table -->
        <div class="bg-white dark:bg-slate-900 rounded-xl border border-slate-200 dark:border-slate-800 overflow-hidden shadow-sm">
            <table class="w-full text-left border-collapse">
                <thead class="bg-slate-50 dark:bg-slate-800/50 border-b border-slate-200 dark:border-slate-700">
                    <tr>
                        <th class="px-6 py-4 w-10">
                            <input id="selectAll" class="rounded text-primary focus:ring-primary border-slate-300" type="checkbox" onchange="toggleSelectAll(this)"/>
                        </th>
                        <th class="px-6 py-4 text-xs font-semibold text-slate-500 dark:text-slate-400 uppercase tracking-wider">Transaction ID</th>
                        <th class="px-6 py-4 text-xs font-semibold text-slate-500 dark:text-slate-400 uppercase tracking-wider">Counterparty</th>
                        <th class="px-6 py-4 text-xs font-semibold text-slate-500 dark:text-slate-400 uppercase tracking-wider">Corridor</th>
                        <th class="px-6 py-4 text-xs font-semibold text-slate-500 dark:text-slate-400 uppercase tracking-wider">Value (USD)</th>
                        <th class="px-6 py-4 text-xs font-semibold text-slate-500 dark:text-slate-400 uppercase tracking-wider">Status</th>
                        <th class="px-6 py-4 text-xs font-semibold text-slate-500 dark:text-slate-400 uppercase tracking-wider">Risk Score</th>
                    </tr>
                </thead>
                <tbody id="transactionsTable" class="divide-y divide-slate-100 dark:divide-slate-800">
                    <tr><td colspan="7" class="px-6 py-4 text-center text-slate-500 dark:text-slate-400">Loading...</td></tr>
                </tbody>
            </table>
        </div>
    </section>

    <!-- Backdrop Overlay -->
    <div id="sidebarBackdrop" class="fixed inset-0 bg-black/50 backdrop-blur-sm z-30 hidden transition-opacity duration-300" onclick="closeSidebar()"></div>
    
    <!-- Right Sidebar Panel (Overlay, 400px) -->
    <aside id="detailSidebar" class="fixed right-0 top-0 h-full w-[400px] bg-white dark:bg-slate-900 border-l border-slate-200 dark:border-slate-800 shadow-2xl transform translate-x-full transition-transform duration-300 overflow-y-auto z-40">
        <div class="p-6">
            <div class="flex justify-between items-center mb-6">
                <h2 class="text-lg font-bold dark:text-white">Transaction Details</h2>
                <button onclick="closeSidebar()" class="p-2 hover:bg-slate-100 dark:hover:bg-slate-800 rounded-lg">
                    <span class="material-icons-outlined">close</span>
                </button>
            </div>
            
            <div id="sidebarContent" class="space-y-6">
                <p class="text-sm text-slate-500 dark:text-slate-400 text-center py-8">Select a transaction to view details</p>
            </div>
        </div>
    </aside>
</div>

<!-- Create Transaction Modal -->
<div id="createModal" class="fixed inset-0 z-50 hidden" role="dialog" aria-modal="true">
    <div class="fixed inset-0 bg-slate-900/50 backdrop-blur-sm transition-opacity"></div>
    <div class="fixed inset-0 z-10 overflow-y-auto">
        <div class="flex min-h-full items-end justify-center p-4 text-center sm:items-center sm:p-0">
            <div class="relative transform overflow-hidden rounded-lg bg-white dark:bg-surface-dark text-left shadow-xl transition-all sm:my-8 sm:w-full sm:max-w-lg border border-slate-200 dark:border-border-dark">
                <form id="createForm" onsubmit="createTransaction(event)">
                    <div class="bg-white dark:bg-surface-dark px-4 pb-4 pt-5 sm:p-6 sm:pb-4">
                        <h3 class="text-lg font-semibold leading-6 text-slate-900 dark:text-white mb-4">Create New Trade</h3>
                        <div class="grid grid-cols-1 gap-4">
                            <div class="grid grid-cols-2 gap-4">
                                <div>
                                    <label class="block text-sm font-medium text-slate-700 dark:text-slate-300 mb-1">Buyer</label>
                                    <select name="buyer_id" required class="w-full px-3 py-2 border border-slate-300 dark:border-slate-600 rounded-lg focus:ring-2 focus:ring-primary bg-white dark:bg-slate-800 dark:text-white">
                                        <option value="">Select Buyer</option>
                                    </select>
                                </div>
                                <div>
                                    <label class="block text-sm font-medium text-slate-700 dark:text-slate-300 mb-1">Supplier</label>
                                    <select name="supplier_id" required class="w-full px-3 py-2 border border-slate-300 dark:border-slate-600 rounded-lg focus:ring-2 focus:ring-primary bg-white dark:bg-slate-800 dark:text-white">
                                        <option value="">Select Supplier</option>
                                    </select>
                                </div>
                            </div>
                            <div>
                                <label class="block text-sm font-medium text-slate-700 dark:text-slate-300 mb-1">Product Category</label>
                                <input name="product_category" type="text" required class="w-full px-3 py-2 border border-slate-300 dark:border-slate-600 rounded-lg focus:ring-2 focus:ring-primary dark:bg-slate-800 dark:text-white"/>
                            </div>
                            <div class="grid grid-cols-2 gap-4">
                                <div>
                                    <label class="block text-sm font-medium text-slate-700 dark:text-slate-300 mb-1">Quantity</label>
                                    <input name="quantity" type="number" required class="w-full px-3 py-2 border border-slate-300 dark:border-slate-600 rounded-lg focus:ring-2 focus:ring-primary dark:bg-slate-800 dark:text-white"/>
                                </div>
                                <div>
                                    <label class="block text-sm font-medium text-slate-700 dark:text-slate-300 mb-1">Unit Price</label>
                                    <input name="unit_price" type="number" step="0.01" required class="w-full px-3 py-2 border border-slate-300 dark:border-slate-600 rounded-lg focus:ring-2 focus:ring-primary dark:bg-slate-800 dark:text-white"/>
                                </div>
                            </div>
                            <div>
                                <label class="block text-sm font-medium text-slate-700 dark:text-slate-300 mb-1">Payment Type</label>
                                <select name="payment_type" required class="w-full px-3 py-2 border border-slate-300 dark:border-slate-600 rounded-lg focus:ring-2 focus:ring-primary bg-white dark:bg-slate-800 dark:text-white">
                                    <option value="LC">Letter of Credit (LC)</option>
                                    <option value="TT">Telegraphic Transfer (TT)</option>
                                    <option value="CAD">Cash Against Documents</option>
                                </select>
                            </div>
                        </div>
                    </div>
                    <div class="bg-slate-50 dark:bg-slate-800/50 px-4 py-3 sm:flex sm:flex-row-reverse sm:px-6 border-t border-slate-200 dark:border-border-dark">
                        <button type="submit" class="inline-flex w-full justify-center rounded-md bg-primary px-3 py-2 text-sm font-semibold text-white shadow-sm hover:bg-blue-700 sm:ml-3 sm:w-auto">Create</button>
                        <button type="button" onclick="closeModal()" class="mt-3 inline-flex w-full justify-center rounded-md bg-white dark:bg-slate-700 px-3 py-2 text-sm font-semibold text-slate-900 dark:text-slate-200 shadow-sm ring-1 ring-inset ring-slate-300 dark:ring-slate-600 hover:bg-slate-50 dark:hover:bg-slate-600 sm:mt-0 sm:w-auto">Cancel</button>
                    </div>
                </form>
            </div>
        </div>
    </div>
</div>
@endsection

@push('scripts')
<script>
    let allTransactions = [];
    let selectedTransaction = null;

    async function loadTransactions() {
        try {
            const res = await window.api.get('/transactions');
            allTransactions = res.data.data;
            renderTransactions(allTransactions);
        } catch (e) {
            console.error('Error loading transactions:', e);
            document.getElementById('transactionsTable').innerHTML = '<tr><td colspan="7" class="px-6 py-4 text-center text-red-500">Error loading transactions</td></tr>';
        }
    }

    function renderTransactions(transactions) {
        const rows = transactions.map((tx, index) => {
            const value = (tx.quantity || 0) * (tx.unit_price || 0);
            const statusConfig = getStatusConfig(tx.status);
            const riskConfig = getRiskConfig(tx.risk_level || 'low');
            
            return `
                <tr onclick="selectTransaction('${tx.uuid}')" class="hover:bg-slate-50 dark:hover:bg-slate-800/50 transition-colors cursor-pointer ${selectedTransaction === tx.uuid ? 'bg-primary/5 border-l-4 border-l-primary' : ''}">
                    <td class="px-6 py-4">
                        <input class="rounded text-primary focus:ring-primary border-slate-300" type="checkbox" onclick="event.stopPropagation()"/>
                    </td>
                    <td class="px-6 py-4 font-mono text-sm ${selectedTransaction === tx.uuid ? 'text-primary font-medium' : 'text-slate-600 dark:text-slate-400'}">${tx.transaction_code || tx.uuid.substring(0, 13)}</td>
                    <td class="px-6 py-4">
                        <div class="flex items-center gap-3">
                            <div class="w-8 h-8 rounded ${getAvatarColor(index)} flex items-center justify-center text-white font-bold text-xs">
                                ${getInitials(tx.buyer?.company_name || 'NA')}
                            </div>
                            <span class="text-sm font-medium dark:text-white">${tx.buyer?.company_name || 'N/A'}</span>
                        </div>
                    </td>
                    <td class="px-6 py-4">
                        <div class="flex items-center gap-2 text-sm">
                            <span>${tx.buyer?.country || 'N/A'}</span>
                            <span class="material-icons-outlined text-slate-400 text-xs">arrow_forward</span>
                            <span>${tx.supplier?.country || 'N/A'}</span>
                        </div>
                    </td>
                    <td class="px-6 py-4 tabular-nums text-sm font-medium dark:text-white">$${value.toLocaleString('en-US', {minimumFractionDigits: 2, maximumFractionDigits: 2})}</td>
                    <td class="px-6 py-4">
                        <span class="inline-flex items-center px-2.5 py-0.5 rounded-full text-xs font-medium ${statusConfig.class}">
                            ${tx.status || 'Pending'}
                        </span>
                    </td>
                    <td class="px-6 py-4">
                        <div class="flex items-center gap-3">
                            <div class="flex-1 h-1.5 bg-slate-100 dark:bg-slate-800 rounded-full w-24">
                                <div class="h-1.5 ${riskConfig.barColor} rounded-full" style="width: ${riskConfig.percentage}%"></div>
                            </div>
                            <span class="text-xs font-semibold ${riskConfig.textColor}">${capitalize(tx.risk_level || 'Low')}</span>
                        </div>
                    </td>
                </tr>
            `;
        }).join('');
        
        document.getElementById('transactionsTable').innerHTML = rows || '<tr><td colspan="7" class="px-6 py-4 text-center text-slate-500 dark:text-slate-400">No transactions found.</td></tr>';
    }

    function selectTransaction(uuid) {
        selectedTransaction = uuid;
        const tx = allTransactions.find(t => t.uuid === uuid);
        if (tx) {
            openSidebar(tx);
            renderTransactions(allTransactions); // Re-render to update selection highlight
        }
    }

    function openSidebar(tx) {
        const value = (tx.quantity || 0) * (tx.unit_price || 0);
        const sidebar = document.getElementById('detailSidebar');
        const backdrop = document.getElementById('sidebarBackdrop');
        const content = document.getElementById('sidebarContent');
        
        content.innerHTML = `
            <div class="bg-slate-50 dark:bg-slate-800/50 p-4 rounded-lg">
                <p class="text-xs text-slate-500 dark:text-slate-400 mb-1">Transaction ID</p>
                <p class="font-mono text-sm font-bold text-primary">${tx.transaction_code || tx.uuid.substring(0, 13)}</p>
            </div>
            
            <div>
                <h3 class="text-sm font-semibold uppercase tracking-wider text-slate-400 mb-3">Parties</h3>
                <div class="space-y-3">
                    <div>
                        <p class="text-xs text-slate-500 dark:text-slate-400 mb-1">Buyer</p>
                        <p class="text-sm font-semibold dark:text-white">${tx.buyer?.company_name || 'N/A'}</p>
                        <p class="text-xs text-slate-500">${tx.buyer?.country || ''}</p>
                    </div>
                    <div>
                        <p class="text-xs text-slate-500 dark:text-slate-400 mb-1">Supplier</p>
                        <p class="text-sm font-semibold dark:text-white">${tx.supplier?.company_name || 'N/A'}</p>
                        <p class="text-xs text-slate-500">${tx.supplier?.country || ''}</p>
                    </div>
                </div>
            </div>
            
            <div>
                <h3 class="text-sm font-semibold uppercase tracking-wider text-slate-400 mb-3">Financial Details</h3>
                <div class="space-y-2">
                    <div class="flex justify-between">
                        <span class="text-sm text-slate-500 dark:text-slate-400">Contract Value</span>
                        <span class="text-sm font-semibold dark:text-white">$${value.toLocaleString('en-US', {minimumFractionDigits: 2})}</span>
                    </div>
                    <div class="flex justify-between">
                        <span class="text-sm text-slate-500 dark:text-slate-400">Payment Type</span>
                        <span class="text-sm font-semibold dark:text-white">${tx.payment_type || 'N/A'}</span>
                    </div>
                    <div class="flex justify-between">
                        <span class="text-sm text-slate-500 dark:text-slate-400">Status</span>
                        <span class="text-xs px-2 py-1 rounded-full ${getStatusConfig(tx.status).class}">${tx.status || 'Pending'}</span>
                    </div>
                </div>
            </div>
            
            <div class="pt-4 border-t border-slate-200 dark:border-slate-800">
                <a href="/transactions/${tx.uuid}" class="w-full px-4 py-2 bg-primary text-white rounded-lg text-sm font-medium hover:bg-primary/90 transition-colors flex items-center justify-center gap-2">
                    <span class="material-icons-outlined text-sm">open_in_new</span>
                    View Full Details
                </a>
            </div>
        `;
        
        backdrop.classList.remove('hidden');
        sidebar.classList.remove('translate-x-full');
    }

    function closeSidebar() {
        const sidebar = document.getElementById('detailSidebar');
        const backdrop = document.getElementById('sidebarBackdrop');
        sidebar.classList.add('translate-x-full');
        backdrop.classList.add('hidden');
        selectedTransaction = null;
        renderTransactions(allTransactions);
    }

    function toggleSelectAll(checkbox) {
        document.querySelectorAll('#transactionsTable input[type="checkbox"]').forEach(cb => {
            cb.checked = checkbox.checked;
        });
    }

    function getStatusConfig(status) {
        const configs = {
            pending: { class: 'bg-slate-100 text-slate-800 dark:bg-slate-800 dark:text-slate-400' },
            in_progress: { class: 'bg-blue-100 text-blue-800 dark:bg-blue-900/30 dark:text-blue-400' },
            completed: { class: 'bg-emerald-100 text-emerald-800 dark:bg-emerald-900/30 dark:text-emerald-400' },
            cancelled: { class: 'bg-red-100 text-red-800 dark:bg-red-900/30 dark:text-red-400' },
            under_review: { class: 'bg-amber-100 text-amber-800 dark:bg-amber-900/30 dark:text-amber-400' }
        };
        return configs[status] || configs.pending;
    }

    function getRiskConfig(level) {
        const configs = {
            low: { percentage: 85, barColor: 'bg-emerald-500', textColor: 'text-emerald-600' },
            medium: { percentage: 50, barColor: 'bg-amber-500', textColor: 'text-amber-600' },
            high: { percentage: 25, barColor: 'bg-red-500', textColor: 'text-red-600' }
        };
        return configs[level] || configs.low;
    }

    function getAvatarColor(index) {
        const colors = [
            'bg-orange-100 text-orange-600',
            'bg-blue-100 text-blue-600',
            'bg-purple-100 text-purple-600',
            'bg-green-100 text-green-600',
            'bg-pink-100 text-pink-600'
        ];
        return colors[index % colors.length];
    }

    function getInitials(name) {
        return name.split(' ').map(n => n[0]).join('').substring(0, 2).toUpperCase();
    }

    function capitalize(str) {
        return str.charAt(0).toUpperCase() + str.slice(1);
    }

    async function loadCounterparties() {
        try {
            const [buyers, suppliers] = await Promise.all([
                window.api.get('/buyers'),
                window.api.get('/suppliers')
            ]);
            
            const buyerSelect = document.querySelector('select[name="buyer_id"]');
            const supplierSelect = document.querySelector('select[name="supplier_id"]');
            
            buyers.data.data.forEach(b => {
                buyerSelect.innerHTML += `<option value="${b.id}">${b.company_name}</option>`;
            });
            
            suppliers.data.data.forEach(s => {
                supplierSelect.innerHTML += `<option value="${s.id}">${s.company_name}</option>`;
            });
        } catch (e) {
            console.error('Error loading counterparties:', e);
        }
    }

    function openModal() {
        document.getElementById('createModal').classList.remove('hidden');
    }

    function closeModal() {
        document.getElementById('createModal').classList.add('hidden');
        document.getElementById('createForm').reset();
    }

    async function createTransaction(event) {
        event.preventDefault();
        const formData = new FormData(event.target);
        const data = Object.fromEntries(formData.entries());

        try {
            await window.api.post('/transactions', data);
            closeModal();
            await loadTransactions();
        } catch (error) {
            console.error('Error creating transaction:', error);
            alert('Error creating transaction: ' + (error.response?.data?.message || error.message));
        }
    }

    // Initialize
    loadTransactions();
    loadCounterparties();
</script>
@endpush
