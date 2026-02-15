@extends('layouts.app')

@section('title', 'Letters of Credit')

@section('content')
<div class="flex min-h-screen relative">
    <!-- Main Content Area - Full Width -->
    <section class="flex-1 flex flex-col p-8 transition-all duration-300">
        <!-- Header -->
        <header class="flex justify-between items-center mb-8">
            <div>
                <h1 class="text-2xl font-bold tracking-tight dark:text-white">Letters of Credit</h1>
                <p class="text-slate-500 dark:text-slate-400 text-sm mt-1">Manage financial instruments and payment obligations.</p>
            </div>
            <div class="flex gap-3">
                <button class="px-4 py-2 border border-slate-200 dark:border-slate-700 bg-white dark:bg-slate-800 rounded-lg text-sm font-medium hover:bg-slate-50 dark:hover:bg-slate-700 transition-colors flex items-center gap-2">
                    <span class="material-icons-outlined text-sm">file_download</span> Export
                </button>
                <button onclick="openModal()" class="px-4 py-2 bg-primary text-white rounded-lg text-sm font-medium hover:bg-primary/90 transition-colors flex items-center gap-2">
                    <span class="material-icons-outlined text-sm">add</span> Record LC
                </button>
            </div>
        </header>

        <!-- Filters Bar -->
        <div class="bg-white dark:bg-slate-900 p-4 rounded-xl border border-slate-200 dark:border-slate-800 flex flex-wrap items-center gap-4 mb-6 shadow-sm">
            <div class="flex-1 relative">
                <span class="material-icons-outlined absolute left-3 top-1/2 -translate-y-1/2 text-slate-400 text-lg">search</span>
                <input id="searchInput" class="w-full pl-10 pr-4 py-2 bg-slate-50 dark:bg-slate-800 border-none rounded-lg text-sm focus:ring-2 focus:ring-primary/20" placeholder="Search by LC number, bank, or amount..." type="text"/>
            </div>
            <div class="flex items-center gap-2">
                <select id="statusFilter" class="bg-white dark:bg-slate-800 border border-slate-200 dark:border-slate-700 rounded-lg text-sm py-2 px-3 focus:ring-2 focus:ring-primary/20">
                    <option value="">All Statuses</option>
                    <option value="active">Active</option>
                    <option value="pending">Pending</option>
                    <option value="expired">Expired</option>
                    <option value="cancelled">Cancelled</option>
                </select>
                <select id="dateFilter" class="bg-white dark:bg-slate-800 border border-slate-200 dark:border-slate-700 rounded-lg text-sm py-2 px-3 focus:ring-2 focus:ring-primary/20">
                    <option>Expiring Soon</option>
                    <option>Last 30 Days</option>
                    <option>Custom Range</option>
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
                        <th class="px-6 py-4 text-xs font-semibold text-slate-500 dark:text-slate-400 uppercase tracking-wider">LC Number</th>
                        <th class="px-6 py-4 text-xs font-semibold text-slate-500 dark:text-slate-400 uppercase tracking-wider">Issuing Bank</th>
                        <th class="px-6 py-4 text-xs font-semibold text-slate-500 dark:text-slate-400 uppercase tracking-wider">Value (USD)</th>
                        <th class="px-6 py-4 text-xs font-semibold text-slate-500 dark:text-slate-400 uppercase tracking-wider">Expiry</th>
                        <th class="px-6 py-4 text-xs font-semibold text-slate-500 dark:text-slate-400 uppercase tracking-wider">Status</th>
                    </tr>
                </thead>
                <tbody id="lcTable" class="divide-y divide-slate-100 dark:divide-slate-800">
                    <tr><td colspan="6" class="px-6 py-4 text-center text-slate-500 dark:text-slate-400">Loading...</td></tr>
                </tbody>
            </table>
        </div>
    </section>

    <!-- Backdrop Overlay -->
    <div id="sidebarBackdrop" class="fixed inset-0 bg-black/50 backdrop-blur-sm z-30 hidden transition-opacity duration-300" onclick="closeSidebar()"></div>

    <!-- Right Sidebar Panel -->
    <aside id="detailSidebar" class="fixed right-0 top-0 h-full w-[400px] bg-white dark:bg-slate-900 border-l border-slate-200 dark:border-slate-800 shadow-2xl transform translate-x-full transition-transform duration-300 overflow-y-auto z-40">
        <div class="p-6">
            <div class="flex justify-between items-center mb-6">
                <h2 class="text-lg font-bold dark:text-white">LC Details</h2>
                <button onclick="closeSidebar()" class="p-2 hover:bg-slate-100 dark:hover:bg-slate-800 rounded-lg">
                    <span class="material-icons-outlined">close</span>
                </button>
            </div>
            
            <div id="sidebarContent" class="space-y-6">
                <p class="text-sm text-slate-500 dark:text-slate-400 text-center py-8">Select an LC record to view details</p>
            </div>
        </div>
    </aside>
</div>

<!-- Create LC Modal -->
<div id="createModal" class="fixed inset-0 z-50 hidden" role="dialog" aria-modal="true">
    <div class="fixed inset-0 bg-slate-900/50 backdrop-blur-sm transition-opacity"></div>
    <div class="fixed inset-0 z-10 overflow-y-auto">
        <div class="flex min-h-full items-end justify-center p-4 text-center sm:items-center sm:p-0">
            <div class="relative transform overflow-hidden rounded-lg bg-white dark:bg-surface-dark text-left shadow-xl transition-all sm:my-8 sm:w-full sm:max-w-lg border border-slate-200 dark:border-border-dark">
                <form id="createForm" onsubmit="createLC(event)">
                    <div class="bg-white dark:bg-surface-dark px-4 pb-4 pt-5 sm:p-6 sm:pb-4">
                        <h3 class="text-lg font-semibold leading-6 text-slate-900 dark:text-white mb-4">Record New Letter of Credit</h3>
                        <div class="grid grid-cols-1 gap-4">
                            <div>
                                <label class="block text-sm font-medium text-slate-700 dark:text-slate-300 mb-1">Transaction</label>
                                <select id="transaction_id" name="transaction_id" required class="w-full px-3 py-2 border border-slate-300 dark:border-slate-600 rounded-lg focus:ring-2 focus:ring-primary bg-white dark:bg-slate-800 dark:text-white">
                                    <option value="">Select Transaction</option>
                                </select>
                            </div>
                            <div class="grid grid-cols-2 gap-4">
                                <div>
                                    <label class="block text-sm font-medium text-slate-700 dark:text-slate-300 mb-1">LC Number</label>
                                    <input name="lc_number" type="text" required class="w-full px-3 py-2 border border-slate-300 dark:border-slate-600 rounded-lg focus:ring-2 focus:ring-primary dark:bg-slate-800 dark:text-white"/>
                                </div>
                                <div>
                                    <label class="block text-sm font-medium text-slate-700 dark:text-slate-300 mb-1">Issuing Bank</label>
                                    <input name="issuing_bank" type="text" required class="w-full px-3 py-2 border border-slate-300 dark:border-slate-600 rounded-lg focus:ring-2 focus:ring-primary dark:bg-slate-800 dark:text-white"/>
                                </div>
                            </div>
                            <div class="grid grid-cols-2 gap-4">
                                <div>
                                    <label class="block text-sm font-medium text-slate-700 dark:text-slate-300 mb-1">Expiry Date</label>
                                    <input name="expiry_date" type="date" required class="w-full px-3 py-2 border border-slate-300 dark:border-slate-600 rounded-lg focus:ring-2 focus:ring-primary dark:bg-slate-800 dark:text-white"/>
                                </div>
                                <div>
                                    <label class="block text-sm font-medium text-slate-700 dark:text-slate-300 mb-1">Amount (USD)</label>
                                    <input name="amount" type="number" step="0.01" required class="w-full px-3 py-2 border border-slate-300 dark:border-slate-600 rounded-lg focus:ring-2 focus:ring-primary dark:bg-slate-800 dark:text-white"/>
                                </div>
                            </div>
                        </div>
                    </div>
                    <div class="bg-slate-50 dark:bg-slate-800/50 px-4 py-3 sm:flex sm:flex-row-reverse sm:px-6 border-t border-slate-200 dark:border-border-dark">
                        <button type="submit" class="inline-flex w-full justify-center rounded-md bg-primary px-3 py-2 text-sm font-semibold text-white shadow-sm hover:bg-blue-700 sm:ml-3 sm:w-auto">Record</button>
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
    let allLCs = [];
    let selectedLC = null;

    async function loadLCs() {
        try {
            const res = await window.api.get('/lcs');
            allLCs = res.data.data;
            renderLCs(allLCs);
        } catch (e) {
            console.error('Error loading LCs:', e);
            document.getElementById('lcTable').innerHTML = '<tr><td colspan="6" class="px-6 py-4 text-center text-red-500">Error loading LCs</td></tr>';
        }
    }

    function renderLCs(lcs) {
        const rows = lcs.map(lc => {
            const statusConfig = getStatusConfig(lc.status || 'pending');
            
            return `
                <tr onclick="selectLC('${lc.uuid}')" class="hover:bg-slate-50 dark:hover:bg-slate-800/50 transition-colors cursor-pointer ${selectedLC === lc.uuid ? 'bg-primary/5 border-l-4 border-l-primary' : ''}">
                    <td class="px-6 py-4">
                        <input class="rounded text-primary focus:ring-primary border-slate-300" type="checkbox" onclick="event.stopPropagation()"/>
                    </td>
                    <td class="px-6 py-4 font-mono text-sm ${selectedLC === lc.uuid ? 'text-primary font-medium' : 'text-slate-600 dark:text-slate-400'}">${lc.lc_number}</td>
                    <td class="px-6 py-4">
                        <div class="flex items-center gap-3">
                            <span class="material-icons-outlined text-slate-400 text-lg">account_balance</span>
                            <span class="text-sm font-medium dark:text-white">${lc.issuing_bank}</span>
                        </div>
                    </td>
                    <td class="px-6 py-4 tabular-nums text-sm font-bold dark:text-white">$${(lc.amount || 0).toLocaleString('en-US', {minimumFractionDigits: 2})}</td>
                    <td class="px-6 py-4 text-sm text-slate-500 dark:text-slate-400 font-medium">${new Date(lc.expiry_date).toLocaleDateString()}</td>
                    <td class="px-6 py-4">
                        <span class="inline-flex items-center px-2.5 py-0.5 rounded-full text-xs font-medium ${statusConfig.class}">
                            ${capitalize(statusConfig.label)}
                        </span>
                    </td>
                </tr>
            `;
        }).join('');
        
        document.getElementById('lcTable').innerHTML = rows || '<tr><td colspan="6" class="px-6 py-4 text-center text-slate-500 dark:text-slate-400">No LC records found.</td></tr>';
    }

    function selectLC(uuid) {
        selectedLC = uuid;
        const lc = allLCs.find(x => x.uuid === uuid);
        if (lc) {
            openSidebar(lc);
            renderLCs(allLCs);
        }
    }

    function openSidebar(lc) {
        const sidebar = document.getElementById('detailSidebar');
        const backdrop = document.getElementById('sidebarBackdrop');
        const content = document.getElementById('sidebarContent');
        
        content.innerHTML = `
            <div class="bg-slate-50 dark:bg-slate-800/50 p-4 rounded-lg">
                <p class="text-xs text-slate-500 dark:text-slate-400 mb-1">LC Number</p>
                <p class="font-mono text-sm font-bold text-primary">${lc.lc_number}</p>
            </div>
            
            <div>
                <h3 class="text-sm font-semibold uppercase tracking-wider text-slate-400 mb-3">Bank & Financials</h3>
                <div class="space-y-3">
                    <div>
                        <p class="text-xs text-slate-500 dark:text-slate-400 mb-1">Issuing Bank</p>
                        <p class="text-sm font-semibold dark:text-white">${lc.issuing_bank}</p>
                    </div>
                    <div>
                        <p class="text-xs text-slate-500 dark:text-slate-400 mb-1">Total Amount</p>
                        <p class="text-lg font-bold text-slate-900 dark:text-white">$${(lc.amount || 0).toLocaleString('en-US', {minimumFractionDigits: 2})}</p>
                    </div>
                </div>
            </div>
            
            <div>
                <h3 class="text-sm font-semibold uppercase tracking-wider text-slate-400 mb-3">Schedule & Status</h3>
                <div class="space-y-2">
                    <div class="flex justify-between">
                        <span class="text-sm text-slate-500 dark:text-slate-400">Expiry Date</span>
                        <span class="text-sm font-semibold dark:text-white">${new Date(lc.expiry_date).toLocaleDateString()}</span>
                    </div>
                    <div class="flex justify-between">
                        <span class="text-sm text-slate-500 dark:text-slate-400">Status</span>
                        <span class="text-xs px-2 py-1 rounded-full ${getStatusConfig(lc.status).class}">${capitalize(getStatusConfig(lc.status).label)}</span>
                    </div>
                </div>
            </div>

            <div class="pt-4 border-t border-slate-200 dark:border-slate-800">
                <a href="/lcs/${lc.uuid}" class="w-full px-4 py-2 bg-primary text-white rounded-lg text-sm font-medium hover:bg-primary/90 transition-colors flex items-center justify-center gap-2">
                    <span class="material-icons-outlined text-sm">open_in_new</span>
                    View Full Details
                </a>
            </div>
        `;
        
        backdrop.classList.remove('hidden');
        sidebar.classList.remove('translate-x-full');
    }

    function closeSidebar() {
        document.getElementById('detailSidebar').classList.add('translate-x-full');
        document.getElementById('sidebarBackdrop').classList.add('hidden');
        selectedLC = null;
        renderLCs(allLCs);
    }

    function getStatusConfig(status) {
        const configs = {
            active: { label: 'Active', class: 'bg-emerald-100 text-emerald-800 dark:bg-emerald-900/30 dark:text-emerald-400' },
            pending: { label: 'Pending', class: 'bg-amber-100 text-amber-800 dark:bg-amber-900/30 dark:text-amber-400' },
            expired: { label: 'Expired', class: 'bg-red-100 text-red-800 dark:bg-red-900/30 dark:text-red-400' },
            cancelled: { label: 'Cancelled', class: 'bg-slate-100 text-slate-800 dark:bg-slate-800 dark:text-slate-400' }
        };
        return configs[status] || configs.pending;
    }

    function capitalize(str) {
        return str.charAt(0).toUpperCase() + str.slice(1);
    }

    async function loadTransactions() {
        try {
            const res = await window.api.get('/transactions');
            const select = document.getElementById('transaction_id');
            select.innerHTML = '<option value="">Select Transaction</option>' + 
                res.data.data.map(t => `<option value="${t.id}">${t.transaction_code || t.uuid.substring(0,8)} - ${t.product_category}</option>`).join('');
        } catch (e) { console.error("Error loading transactions", e); }
    }

    function openModal() { document.getElementById('createModal').classList.remove('hidden'); }
    function closeModal() { document.getElementById('createModal').classList.add('hidden'); }

    async function createLC(e) {
        e.preventDefault();
        const formData = new FormData(e.target);
        const data = Object.fromEntries(formData.entries());

        try {
            await window.api.post('/lcs', data);
            closeModal();
            loadLCs();
            e.target.reset();
        } catch (error) {
            console.error(error);
            alert("Failed to record LC: " + (error.response?.data?.message || error.message));
        }
    }

    function toggleSelectAll(checkbox) {
        document.querySelectorAll('#lcTable input[type="checkbox"]').forEach(cb => cb.checked = checkbox.checked);
    }

    // Search and filter
    document.getElementById('searchInput')?.addEventListener('input', filterLCs);
    document.getElementById('statusFilter')?.addEventListener('change', filterLCs);

    function filterLCs() {
        const search = document.getElementById('searchInput').value.toLowerCase();
        const status = document.getElementById('statusFilter').value;
        
        const filtered = allLCs.filter(lc => {
            const matchesSearch = lc.lc_number.toLowerCase().includes(search) || 
                                 lc.issuing_bank.toLowerCase().includes(search);
            const matchesStatus = !status || lc.status === status;
            return matchesSearch && matchesStatus;
        });
        
        renderLCs(filtered);
    }

    // Initialize
    loadLCs();
    loadTransactions();
</script>
@endpush
