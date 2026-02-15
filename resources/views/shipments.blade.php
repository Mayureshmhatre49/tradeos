@extends('layouts.app')

@section('title', 'Shipments')

@section('content')
<div class="flex min-h-screen relative">
    <!-- Main Content Area - Full Width -->
    <section class="flex-1 flex flex-col p-8 transition-all duration-300">
        <!-- Header -->
        <header class="flex justify-between items-center mb-8">
            <div>
                <h1 class="text-2xl font-bold tracking-tight dark:text-white">Shipments</h1>
                <p class="text-slate-500 dark:text-slate-400 text-sm mt-1">Track and manage active logistic operations.</p>
            </div>
            <div class="flex gap-3">
                <button class="px-4 py-2 border border-slate-200 dark:border-slate-700 bg-white dark:bg-slate-800 rounded-lg text-sm font-medium hover:bg-slate-50 dark:hover:bg-slate-700 transition-colors flex items-center gap-2">
                    <span class="material-icons-outlined text-sm">file_download</span> Export
                </button>
                <button onclick="openModal()" class="px-4 py-2 bg-primary text-white rounded-lg text-sm font-medium hover:bg-primary/90 transition-colors flex items-center gap-2">
                    <span class="material-icons-outlined text-sm">add</span> Add Shipment
                </button>
            </div>
        </header>

        <!-- Filters Bar -->
        <div class="bg-white dark:bg-slate-900 p-4 rounded-xl border border-slate-200 dark:border-slate-800 flex flex-wrap items-center gap-4 mb-6 shadow-sm">
            <div class="flex-1 relative">
                <span class="material-icons-outlined absolute left-3 top-1/2 -translate-y-1/2 text-slate-400 text-lg">search</span>
                <input id="searchInput" class="w-full pl-10 pr-4 py-2 bg-slate-50 dark:bg-slate-800 border-none rounded-lg text-sm focus:ring-2 focus:ring-primary/20" placeholder="Search by BL, vessel, or port..." type="text"/>
            </div>
            <div class="flex items-center gap-2">
                <select id="statusFilter" class="bg-white dark:bg-slate-800 border border-slate-200 dark:border-slate-700 rounded-lg text-sm py-2 px-3 focus:ring-2 focus:ring-primary/20">
                    <option value="">All Statuses</option>
                    <option value="in_transit">In Transit</option>
                    <option value="delivered">Delivered</option>
                    <option value="pending">Pending</option>
                    <option value="exception">Exception</option>
                </select>
                <select id="dateFilter" class="bg-white dark:bg-slate-800 border border-slate-200 dark:border-slate-700 rounded-lg text-sm py-2 px-3 focus:ring-2 focus:ring-primary/20">
                    <option>Next 30 Days (ETA)</option>
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
                        <th class="px-6 py-4 text-xs font-semibold text-slate-500 dark:text-slate-400 uppercase tracking-wider">BL Number</th>
                        <th class="px-6 py-4 text-xs font-semibold text-slate-500 dark:text-slate-400 uppercase tracking-wider">Vessel</th>
                        <th class="px-6 py-4 text-xs font-semibold text-slate-500 dark:text-slate-400 uppercase tracking-wider">Route (POL → POD)</th>
                        <th class="px-6 py-4 text-xs font-semibold text-slate-500 dark:text-slate-400 uppercase tracking-wider">ETA</th>
                        <th class="px-6 py-4 text-xs font-semibold text-slate-500 dark:text-slate-400 uppercase tracking-wider">Status</th>
                    </tr>
                </thead>
                <tbody id="shipTable" class="divide-y divide-slate-100 dark:divide-slate-800">
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
                <h2 class="text-lg font-bold dark:text-white">Shipment Details</h2>
                <button onclick="closeSidebar()" class="p-2 hover:bg-slate-100 dark:hover:bg-slate-800 rounded-lg">
                    <span class="material-icons-outlined">close</span>
                </button>
            </div>
            
            <div id="sidebarContent" class="space-y-6">
                <p class="text-sm text-slate-500 dark:text-slate-400 text-center py-8">Select a shipment to view details</p>
            </div>
        </div>
    </aside>
</div>

<!-- Create Shipment Modal -->
<div id="createModal" class="fixed inset-0 z-50 hidden" role="dialog" aria-modal="true">
    <div class="fixed inset-0 bg-slate-900/50 backdrop-blur-sm transition-opacity"></div>
    <div class="fixed inset-0 z-10 overflow-y-auto">
        <div class="flex min-h-full items-end justify-center p-4 text-center sm:items-center sm:p-0">
            <div class="relative transform overflow-hidden rounded-lg bg-white dark:bg-surface-dark text-left shadow-xl transition-all sm:my-8 sm:w-full sm:max-w-lg border border-slate-200 dark:border-border-dark">
                <form id="createForm" onsubmit="createShipment(event)">
                    <div class="bg-white dark:bg-surface-dark px-4 pb-4 pt-5 sm:p-6 sm:pb-4">
                        <h3 class="text-lg font-semibold leading-6 text-slate-900 dark:text-white mb-4">Add New Shipment</h3>
                        <div class="grid grid-cols-1 gap-4">
                            <div>
                                <label class="block text-sm font-medium text-slate-700 dark:text-slate-300 mb-1">Transaction</label>
                                <select id="transaction_id" name="transaction_id" required class="w-full px-3 py-2 border border-slate-300 dark:border-slate-600 rounded-lg focus:ring-2 focus:ring-primary bg-white dark:bg-slate-800 dark:text-white">
                                    <option value="">Select Transaction</option>
                                </select>
                            </div>
                            <div class="grid grid-cols-2 gap-4">
                                <div>
                                    <label class="block text-sm font-medium text-slate-700 dark:text-slate-300 mb-1">Vessel Name</label>
                                    <input name="vessel_name" type="text" required class="w-full px-3 py-2 border border-slate-300 dark:border-slate-600 rounded-lg focus:ring-2 focus:ring-primary dark:bg-slate-800 dark:text-white"/>
                                </div>
                                <div>
                                    <label class="block text-sm font-medium text-slate-700 dark:text-slate-300 mb-1">BL Number</label>
                                    <input name="bl_number" type="text" required class="w-full px-3 py-2 border border-slate-300 dark:border-slate-600 rounded-lg focus:ring-2 focus:ring-primary dark:bg-slate-800 dark:text-white"/>
                                </div>
                            </div>
                            <div class="grid grid-cols-2 gap-4">
                                <div>
                                    <label class="block text-sm font-medium text-slate-700 dark:text-slate-300 mb-1">Port of Loading</label>
                                    <input name="port_of_loading" type="text" required class="w-full px-3 py-2 border border-slate-300 dark:border-slate-600 rounded-lg focus:ring-2 focus:ring-primary dark:bg-slate-800 dark:text-white"/>
                                </div>
                                <div>
                                    <label class="block text-sm font-medium text-slate-700 dark:text-slate-300 mb-1">Port of Discharge</label>
                                    <input name="port_of_discharge" type="text" required class="w-full px-3 py-2 border border-slate-300 dark:border-slate-600 rounded-lg focus:ring-2 focus:ring-primary dark:bg-slate-800 dark:text-white"/>
                                </div>
                            </div>
                            <div class="grid grid-cols-2 gap-4">
                                <div>
                                    <label class="block text-sm font-medium text-slate-700 dark:text-slate-300 mb-1">ETA</label>
                                    <input name="eta" type="date" required class="w-full px-3 py-2 border border-slate-300 dark:border-slate-600 rounded-lg focus:ring-2 focus:ring-primary dark:bg-slate-800 dark:text-white"/>
                                </div>
                                <div>
                                    <label class="block text-sm font-medium text-slate-700 dark:text-slate-300 mb-1">Insurance (Opt)</label>
                                    <input name="insurance_details" type="text" class="w-full px-3 py-2 border border-slate-300 dark:border-slate-600 rounded-lg focus:ring-2 focus:ring-primary dark:bg-slate-800 dark:text-white"/>
                                </div>
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
    let allShipments = [];
    let selectedShipment = null;

    async function loadShipments() {
        try {
            const res = await window.api.get('/shipments');
            allShipments = res.data.data;
            renderShipments(allShipments);
        } catch (e) {
            console.error('Error loading shipments:', e);
            document.getElementById('shipTable').innerHTML = '<tr><td colspan="6" class="px-6 py-4 text-center text-red-500">Error loading shipments</td></tr>';
        }
    }

    function renderShipments(shipments) {
        const rows = shipments.map(s => {
            const statusConfig = getStatusConfig(s.status || 'in_transit');
            
            return `
                <tr onclick="selectShipment('${s.uuid}')" class="hover:bg-slate-50 dark:hover:bg-slate-800/50 transition-colors cursor-pointer ${selectedShipment === s.uuid ? 'bg-primary/5 border-l-4 border-l-primary' : ''}">
                    <td class="px-6 py-4">
                        <input class="rounded text-primary focus:ring-primary border-slate-300" type="checkbox" onclick="event.stopPropagation()"/>
                    </td>
                    <td class="px-6 py-4 font-mono text-sm ${selectedShipment === s.uuid ? 'text-primary font-medium' : 'text-slate-600 dark:text-slate-400'}">${s.bl_number}</td>
                    <td class="px-6 py-4">
                        <div class="flex items-center gap-3">
                            <span class="material-icons-outlined text-slate-400 text-lg">directions_boat</span>
                            <span class="text-sm font-medium dark:text-white">${s.vessel_name}</span>
                        </div>
                    </td>
                    <td class="px-6 py-4">
                        <div class="flex items-center gap-2 text-sm dark:text-slate-300">
                            <span>${s.port_of_loading}</span>
                            <span class="material-icons-outlined text-slate-400 text-xs">arrow_forward</span>
                            <span>${s.port_of_discharge}</span>
                        </div>
                    </td>
                    <td class="px-6 py-4 text-sm text-slate-500 dark:text-slate-400 font-medium">${new Date(s.eta).toLocaleDateString()}</td>
                    <td class="px-6 py-4">
                        <span class="inline-flex items-center px-2.5 py-0.5 rounded-full text-xs font-medium ${statusConfig.class}">
                            ${capitalize(statusConfig.label)}
                        </span>
                    </td>
                </tr>
            `;
        }).join('');
        
        document.getElementById('shipTable').innerHTML = rows || '<tr><td colspan="6" class="px-6 py-4 text-center text-slate-500 dark:text-slate-400">No shipments found.</td></tr>';
    }

    function selectShipment(uuid) {
        selectedShipment = uuid;
        const s = allShipments.find(x => x.uuid === uuid);
        if (s) {
            openSidebar(s);
            renderShipments(allShipments);
        }
    }

    function openSidebar(s) {
        const sidebar = document.getElementById('detailSidebar');
        const backdrop = document.getElementById('sidebarBackdrop');
        const content = document.getElementById('sidebarContent');
        
        content.innerHTML = `
            <div class="bg-slate-50 dark:bg-slate-800/50 p-4 rounded-lg">
                <p class="text-xs text-slate-500 dark:text-slate-400 mb-1">Bill of Lading</p>
                <p class="font-mono text-sm font-bold text-primary">${s.bl_number}</p>
            </div>
            
            <div>
                <h3 class="text-sm font-semibold uppercase tracking-wider text-slate-400 mb-3">Vessel & Route</h3>
                <div class="space-y-3">
                    <div>
                        <p class="text-xs text-slate-500 dark:text-slate-400 mb-1">Vessel</p>
                        <p class="text-sm font-semibold dark:text-white">${s.vessel_name}</p>
                    </div>
                    <div class="grid grid-cols-2 gap-4">
                        <div>
                            <p class="text-xs text-slate-500 dark:text-slate-400 mb-1">Loading Port</p>
                            <p class="text-sm font-medium dark:text-white">${s.port_of_loading}</p>
                        </div>
                        <div>
                            <p class="text-xs text-slate-500 dark:text-slate-400 mb-1">Discharge Port</p>
                            <p class="text-sm font-medium dark:text-white">${s.port_of_discharge}</p>
                        </div>
                    </div>
                </div>
            </div>
            
            <div>
                <h3 class="text-sm font-semibold uppercase tracking-wider text-slate-400 mb-3">Schedule</h3>
                <div class="space-y-2">
                    <div class="flex justify-between">
                        <span class="text-sm text-slate-500 dark:text-slate-400">Estimated Arrival</span>
                        <span class="text-sm font-semibold dark:text-white">${new Date(s.eta).toLocaleDateString()}</span>
                    </div>
                    <div class="flex justify-between">
                        <span class="text-sm text-slate-500 dark:text-slate-400">Status</span>
                        <span class="text-xs px-2 py-1 rounded-full ${getStatusConfig(s.status).class}">${capitalize(getStatusConfig(s.status).label)}</span>
                    </div>
                </div>
            </div>

            <div class="pt-4 border-t border-slate-200 dark:border-slate-800">
                <a href="/shipments/${s.uuid}" class="w-full px-4 py-2 bg-primary text-white rounded-lg text-sm font-medium hover:bg-primary/90 transition-colors flex items-center justify-center gap-2">
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
        selectedShipment = null;
        renderShipments(allShipments);
    }

    function getStatusConfig(status) {
        const configs = {
            in_transit: { label: 'In Transit', class: 'bg-blue-100 text-blue-800 dark:bg-blue-900/30 dark:text-blue-400' },
            delivered: { label: 'Delivered', class: 'bg-emerald-100 text-emerald-800 dark:bg-emerald-900/30 dark:text-emerald-400' },
            pending: { label: 'Pending', class: 'bg-slate-100 text-slate-800 dark:bg-slate-800 dark:text-slate-400' },
            exception: { label: 'Exception', class: 'bg-red-100 text-red-800 dark:bg-red-900/30 dark:text-red-400' }
        };
        return configs[status] || configs.in_transit;
    }

    function capitalize(str) {
        return str.split('_').map(word => word.charAt(0).toUpperCase() + word.slice(1)).join(' ');
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

    async function createShipment(e) {
        e.preventDefault();
        const formData = new FormData(e.target);
        const data = Object.fromEntries(formData.entries());

        try {
            await window.api.post('/shipments', data);
            closeModal();
            loadShipments();
            e.target.reset();
        } catch (error) {
            console.error(error);
            alert("Failed to create shipment: " + (error.response?.data?.message || error.message));
        }
    }

    function toggleSelectAll(checkbox) {
        document.querySelectorAll('#shipTable input[type="checkbox"]').forEach(cb => cb.checked = checkbox.checked);
    }

    // Search and filter
    document.getElementById('searchInput')?.addEventListener('input', filterShipments);
    document.getElementById('statusFilter')?.addEventListener('change', filterShipments);

    function filterShipments() {
        const search = document.getElementById('searchInput').value.toLowerCase();
        const status = document.getElementById('statusFilter').value;
        
        const filtered = allShipments.filter(s => {
            const matchesSearch = s.bl_number.toLowerCase().includes(search) || 
                                 s.vessel_name.toLowerCase().includes(search) ||
                                 s.port_of_loading.toLowerCase().includes(search) ||
                                 s.port_of_discharge.toLowerCase().includes(search);
            const matchesStatus = !status || s.status === status;
            return matchesSearch && matchesStatus;
        });
        
        renderShipments(filtered);
    }

    // Initialize
    loadShipments();
    loadTransactions();
</script>
@endpush
