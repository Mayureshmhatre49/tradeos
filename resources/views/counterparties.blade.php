@extends('layouts.app')

@section('title', 'Counterparty & KYC Directory')

@section('content')
<div class="flex-1 p-8">
    <!-- Top Bar -->
    <header class="flex justify-between items-center mb-8">
        <div>
            <h1 class="text-2xl font-bold text-slate-900 dark:text-white">Counterparty & KYC Directory</h1>
            <p class="text-slate-500 dark:text-slate-400 text-sm mt-1">Manage and monitor your B2B partner compliance statuses.</p>
        </div>
        <button onclick="openModal()" class="bg-primary hover:bg-primary/90 text-white px-5 py-2.5 rounded flex items-center gap-2 font-medium transition-all shadow-sm shadow-primary/20">
            <span class="material-icons text-lg">add</span>
            Add Counterparty
        </button>
    </header>

    <!-- Stats Overview -->
    <div class="grid grid-cols-4 gap-6 mb-8">
        <div class="bg-white dark:bg-slate-800/50 p-6 rounded-xl border border-slate-200 dark:border-slate-800">
            <p class="text-slate-500 dark:text-slate-400 text-xs font-semibold uppercase tracking-wider mb-2">Total Partners</p>
            <div class="flex items-end justify-between">
                <span id="totalPartners" class="text-3xl font-bold dark:text-white">0</span>
                <span class="text-xs text-emerald-600 dark:text-emerald-400 font-semibold flex items-center gap-1">
                    <span class="material-icons text-sm">trending_up</span> +12%
                </span>
            </div>
        </div>
        <div class="bg-white dark:bg-slate-800/50 p-6 rounded-xl border border-slate-200 dark:border-slate-800">
            <p class="text-slate-500 dark:text-slate-400 text-xs font-semibold uppercase tracking-wider mb-2">Active Verified</p>
            <div class="flex items-end justify-between">
                <span id="activeVerified" class="text-3xl font-bold text-emerald-600 dark:text-emerald-400">0</span>
                <div class="w-12 h-12 rounded-full bg-emerald-100 dark:bg-emerald-900/30 flex items-center justify-center">
                    <span class="material-icons text-emerald-600 dark:text-emerald-400">verified</span>
                </div>
            </div>
        </div>
        <div class="bg-white dark:bg-slate-800/50 p-6 rounded-xl border border-slate-200 dark:border-slate-800">
            <p class="text-slate-500 dark:text-slate-400 text-xs font-semibold uppercase tracking-wider mb-2">Pending KYC</p>
            <div class="flex items-end justify-between">
                <span id="pendingKyc" class="text-3xl font-bold text-amber-600 dark:text-amber-400">0</span>
                <div class="w-12 h-12 rounded-full bg-amber-100 dark:bg-amber-900/30 flex items-center justify-center">
                    <span class="material-icons text-amber-600 dark:text-amber-400">pending</span>
                </div>
            </div>
        </div>
        <div class="bg-white dark:bg-slate-800/50 p-6 rounded-xl border border-slate-200 dark:border-slate-800">
            <p class="text-slate-500 dark:text-slate-400 text-xs font-semibold uppercase tracking-wider mb-2">High Risk Flags</p>
            <div class="flex items-end justify-between">
                <span id="highRisk" class="text-3xl font-bold text-red-600 dark:text-red-400">0</span>
                <div class="w-12 h-12 rounded-full bg-red-100 dark:bg-red-900/30 flex items-center justify-center">
                    <span class="material-icons text-red-600 dark:text-red-400">warning</span>
                </div>
            </div>
        </div>
    </div>

    <!-- Filters & Search -->
    <div class="flex items-center gap-4 mb-6">
        <div class="flex-1 relative">
            <span class="material-icons absolute left-3 top-1/2 -translate-y-1/2 text-slate-400">search</span>
            <input id="searchInput" class="w-full pl-10 pr-4 py-2 bg-white dark:bg-slate-800 border border-slate-200 dark:border-slate-700 rounded-lg text-sm focus:ring-2 focus:ring-primary/20" placeholder="Search partners..." type="text"/>
        </div>
        <select id="typeFilter" class="bg-white dark:bg-slate-800 border border-slate-200 dark:border-slate-700 rounded-lg text-sm py-2 px-3 focus:ring-2 focus:ring-primary/20">
            <option value="">All Types</option>
            <option value="buyer">Buyers</option>
            <option value="supplier">Suppliers</option>
        </select>
        <select id="statusFilter" class="bg-white dark:bg-slate-800 border border-slate-200 dark:border-slate-700 rounded-lg text-sm py-2 px-3 focus:ring-2 focus:ring-primary/20">
            <option value="">All Statuses</option>
            <option value="verified">Verified</option>
            <option value="pending">Pending</option>
            <option value="rejected">Rejected</option>
        </select>
    </div>

    <!-- Card Grid -->
    <div id="partnersGrid" class="grid grid-cols-1 md:grid-cols-2 lg:grid-cols-3 gap-6">
        <div class="col-span-full text-center py-8 text-slate-500 dark:text-slate-400">Loading...</div>
    </div>
</div>

<!-- Create Counterparty Modal -->
<div id="createModal" class="fixed inset-0 z-50 hidden" role="dialog" aria-modal="true">
    <div class="fixed inset-0 bg-slate-900/50 backdrop-blur-sm transition-opacity"></div>
    <div class="fixed inset-0 z-10 overflow-y-auto">
        <div class="flex min-h-full items-end justify-center p-4 text-center sm:items-center sm:p-0">
            <div class="relative transform overflow-hidden rounded-lg bg-white dark:bg-surface-dark text-left shadow-xl transition-all sm:my-8 sm:w-full sm:max-w-lg border border-slate-200 dark:border-border-dark">
                <form id="createForm" onsubmit="createCounterparty(event)">
                    <div class="bg-white dark:bg-surface-dark px-4 pb-4 pt-5 sm:p-6 sm:pb-4">
                        <h3 class="text-lg font-semibold leading-6 text-slate-900 dark:text-white mb-4">Add Counterparty</h3>
                        <div class="grid grid-cols-1 gap-4">
                            <div>
                                <label class="block text-sm font-medium text-slate-700 dark:text-slate-300 mb-1">Type</label>
                                <select id="typeSelect" name="type" required class="w-full px-3 py-2 border border-slate-300 dark:border-slate-600 rounded-lg focus:ring-2 focus:ring-primary bg-white dark:bg-slate-800 dark:text-white">
                                    <option value="buyer">Buyer</option>
                                    <option value="supplier">Supplier</option>
                                </select>
                            </div>
                            <div>
                                <label class="block text-sm font-medium text-slate-700 dark:text-slate-300 mb-1">Company Name</label>
                                <input name="company_name" type="text" required class="w-full px-3 py-2 border border-slate-300 dark:border-slate-600 rounded-lg focus:ring-2 focus:ring-primary dark:bg-slate-800 dark:text-white"/>
                            </div>
                            <div>
                                <label class="block text-sm font-medium text-slate-700 dark:text-slate-300 mb-1">Country</label>
                                <input name="country" type="text" required class="w-full px-3 py-2 border border-slate-300 dark:border-slate-600 rounded-lg focus:ring-2 focus:ring-primary dark:bg-slate-800 dark:text-white"/>
                            </div>
                            <div>
                                <label class="block text-sm font-medium text-slate-700 dark:text-slate-300 mb-1">Contact Person</label>
                                <input name="contact_person" type="text" required class="w-full px-3 py-2 border border-slate-300 dark:border-slate-600 rounded-lg focus:ring-2 focus:ring-primary dark:bg-slate-800 dark:text-white"/>
                            </div>
                            <div>
                                <label class="block text-sm font-medium text-slate-700 dark:text-slate-300 mb-1">Email</label>
                                <input name="email" type="email" required class="w-full px-3 py-2 border border-slate-300 dark:border-slate-600 rounded-lg focus:ring-2 focus:ring-primary dark:bg-slate-800 dark:text-white"/>
                            </div>
                        </div>
                    </div>
                    <div class="bg-slate-50 dark:bg-slate-800/50 px-4 py-3 sm:flex sm:flex-row-reverse sm:px-6 border-t border-slate-200 dark:border-border-dark">
                        <button type="submit" class="inline-flex w-full justify-center rounded-md bg-primary px-3 py-2 text-sm font-semibold text-white shadow-sm hover:bg-blue-700 sm:ml-3 sm:w-auto">Add</button>
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
    let allPartners = [];

    async function loadCounterparties() {
        try {
            const [buyersRes, suppliersRes] = await Promise.all([
                window.api.get('/buyers'),
                window.api.get('/suppliers')
            ]);
            
            const buyers = (buyersRes.data.data || []).map(b => ({...b, type: 'buyer'}));
            const suppliers = (suppliersRes.data.data || []).map(s => ({...s, type: 'supplier'}));
            
            allPartners = [...buyers, ...suppliers];
            
            updateStats();
            renderPartners(allPartners);
        } catch (e) {
            console.error('Error loading counterparties:', e);
            document.getElementById('partnersGrid').innerHTML = '<div class="col-span-full text-center py-8 text-red-500">Error loading partners</div>';
        }
    }

    function updateStats() {
        const total = allPartners.length;
        const verified = allPartners.filter(p => p.kyc_status === 'verified').length;
        const pending = allPartners.filter(p => p.kyc_status === 'pending').length;
        const highRisk = allPartners.filter(p => p.risk_flag === 'high').length;
        
        document.getElementById('totalPartners').textContent = total;
        document.getElementById('activeVerified').textContent = verified;
        document.getElementById('pendingKyc').textContent = pending;
        document.getElementById('highRisk').textContent = highRisk;
    }

    function renderPartners(partners) {
        if (!partners.length) {
            document.getElementById('partnersGrid').innerHTML = '<div class="col-span-full text-center py-8 text-slate-500 dark:text-slate-400">No partners found.</div>';
            return;
        }
        
        const cards = partners.map((partner, index) => {
            const kycBadge = getKYCBadge(partner.kyc_status);
            const initials = getInitials(partner.company_name);
            const color = getAvatarColor(index);
            const lastActivity = new Date(partner.updated_at || partner.created_at).toLocaleDateString();
            
            return `
                <div class="bg-white dark:bg-slate-800 rounded-xl border border-slate-200 dark:border-slate-800 p-6 hover:shadow-lg transition-shadow">
                    <div class="flex items-start justify-between mb-4">
                        <div class="flex items-center gap-3">
                            <div class="w-12 h-12 rounded-lg ${color} flex items-center justify-center text-white font-bold text-lg">
                                ${initials}
                            </div>
                            <div>
                                <h3 class="font-semibold dark:text-white">${partner.company_name}</h3>
                                <p class="text-xs text-slate-500 dark:text-slate-400">${partner.country || 'N/A'}</p>
                            </div>
                        </div>
                        ${kycBadge}
                    </div>
                    
                    <div class="space-y-2 mb-4">
                        <div class="flex items-center justify-between text-sm">
                            <span class="text-slate-500 dark:text-slate-400">Type</span>
                            <span class="font-medium capitalize dark:text-white">${partner.type}</span>
                        </div>
                        <div class="flex items-center justify-between text-sm">
                            <span class="text-slate-500 dark:text-slate-400">Last Activity</span>
                            <span class="font-medium dark:text-white">${lastActivity}</span>
                        </div>
                    </div>
                    
                    <div class="flex gap-2 pt-4 border-t border-slate-100 dark:border-slate-700">
                        <button onclick="viewPartner('${partner.uuid}', '${partner.type}')" class="flex-1 px-3 py-2 bg-primary text-white rounded-lg text-sm font-medium hover:bg-primary/90 transition-colors">
                            View
                        </button>
                        <button class="px-3 py-2 border border-slate-200 dark:border-slate-700 rounded-lg text-sm font-medium hover:bg-slate-50 dark:hover:bg-slate-700 transition-colors dark:text-white">
                            Edit
                        </button>
                    </div>
                </div>
            `;
        }).join('');
        
        document.getElementById('partnersGrid').innerHTML = cards;
    }

    function getKYCBadge(status) {
        const badges = {
            verified: '<span class="px-2 py-1 bg-emerald-100 dark:bg-emerald-900/30 text-emerald-700 dark:text-emerald-400 text-xs font-semibold rounded-full flex items-center gap-1"><span class="material-icons text-xs">verified</span> Verified</span>',
            pending: '<span class="px-2 py-1 bg-amber-100 dark:bg-amber-900/30 text-amber-700 dark:text-amber-400 text-xs font-semibold rounded-full flex items-center gap-1"><span class="material-icons text-xs">pending</span> Pending</span>',
            rejected: '<span class="px-2 py-1 bg-red-100 dark:bg-red-900/30 text-red-700 dark:text-red-400 text-xs font-semibold rounded-full flex items-center gap-1"><span class="material-icons text-xs">cancel</span> Rejected</span>'
        };
        return badges[status] || badges.pending;
    }

    function getInitials(name) {
        return name.split(' ').map(n => n[0]).join('').substring(0, 2).toUpperCase();
    }

    function getAvatarColor(index) {
        const colors = [
            'bg-blue-500',
            'bg-purple-500',
            'bg-pink-500',
            'bg-orange-500',
            'bg-green-500',
            'bg-indigo-500'
        ];
        return colors[index % colors.length];
    }

    function viewPartner(uuid, type) {
        window.location.href = `/counterparties/${uuid}?type=${type}`;
    }

    function openModal() {
        document.getElementById('createModal').classList.remove('hidden');
    }

    function closeModal() {
        document.getElementById('createModal').classList.add('hidden');
        document.getElementById('createForm').reset();
    }

    async function createCounterparty(event) {
        event.preventDefault();
        const formData = new FormData(event.target);
        const data = Object.fromEntries(formData.entries());
        const type = data.type;
        delete data.type;

        try {
            const endpoint = type === 'buyer' ? '/buyers' : '/suppliers';
            await window.api.post(endpoint, data);
            closeModal();
            await loadCounterparties();
        } catch (error) {
            console.error('Error creating counterparty:', error);
            alert('Error creating counterparty: ' + (error.response?.data?.message || error.message));
        }
    }

    // Search and filter
    document.getElementById('searchInput')?.addEventListener('input', filterPartners);
    document.getElementById('typeFilter')?.addEventListener('change', filterPartners);
    document.getElementById('statusFilter')?.addEventListener('change', filterPartners);

    function filterPartners() {
        const search = document.getElementById('searchInput').value.toLowerCase();
        const typeFilter = document.getElementById('typeFilter').value;
        const statusFilter = document.getElementById('statusFilter').value;
        
        const filtered = allPartners.filter(p => {
            const matchesSearch = p.company_name.toLowerCase().includes(search) || 
                                 p.country?.toLowerCase().includes(search);
            const matchesType = !typeFilter || p.type === typeFilter;
            const matchesStatus = !statusFilter || p.kyc_status === statusFilter;
            
            return matchesSearch && matchesType && matchesStatus;
        });
        
        renderPartners(filtered);
    }

    // Initialize
    loadCounterparties();
</script>
@endpush
