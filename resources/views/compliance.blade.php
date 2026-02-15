@extends('layouts.app')

@section('title', 'Compliance & Risk Monitor')

@section('content')
<style>
    .custom-scrollbar::-webkit-scrollbar { width: 4px; }
    .custom-scrollbar::-webkit-scrollbar-track { background: transparent; }
    .custom-scrollbar::-webkit-scrollbar-thumb { background: #cbd5e1; border-radius: 10px; }
</style>

<div class="min-h-screen">
    <!-- Top Header with sticky -->
    <header class="h-16 border-b border-slate-200 dark:border-slate-800 bg-white/80 dark:bg-slate-900/80 backdrop-blur-md sticky top-0 z-40 px-8 flex items-center justify-between">
        <div class="flex items-center gap-4">
            <h1 class="text-xl font-semibold tracking-tight dark:text-white">Compliance & Risk Monitor</h1>
            <div class="flex gap-2">
                <span id="highAlertsBadge" class="px-2 py-0.5 rounded bg-red-100 dark:bg-red-900/30 text-red-600 dark:text-red-400 text-xs font-bold uppercase tracking-wider flex items-center gap-1">
                    <span class="material-icons text-sm">warning</span> 3 High Alerts
                </span>
                <span id="pendingBadge" class="px-2 py-0.5 rounded bg-amber-100 dark:bg-amber-900/30 text-amber-600 dark:text-amber-400 text-xs font-bold uppercase tracking-wider flex items-center gap-1">
                    <span class="material-icons text-sm">info</span> 12 Pending Review
                </span>
            </div>
        </div>
        <div class="flex items-center gap-4">
            <div class="relative">
                <span class="material-icons absolute left-3 top-1/2 -translate-y-1/2 text-slate-400 text-sm">search</span>
                <input class="pl-10 pr-4 py-2 bg-slate-100 dark:bg-slate-800 border-none rounded-lg text-sm w-64 focus:ring-2 focus:ring-primary/50 transition-all" placeholder="Search ID, Entity, or Corridor..." type="text"/>
            </div>
            <button class="flex items-center gap-2 bg-primary text-white px-4 py-2 rounded-lg text-sm font-medium hover:bg-blue-700 transition-colors">
                <span class="material-icons text-sm">download</span> Export Report
            </button>
        </div>
    </header>

    <div class="p-8 space-y-8">
        <!-- Risk Heatmap and Stats -->
        <section class="grid grid-cols-12 gap-6">
            <!-- Risk Heatmap -->
            <div class="col-span-8 bg-white dark:bg-slate-900 p-6 rounded-xl border border-slate-200 dark:border-slate-800">
                <div class="flex justify-between items-center mb-6">
                    <div>
                        <h2 class="text-sm font-semibold uppercase text-slate-400 tracking-widest">Risk Heatmap</h2>
                        <p class="text-xs text-slate-500 mt-1">Cross-border corridor risk levels by transaction volume</p>
                    </div>
                    <div class="flex gap-2 text-[10px] font-bold uppercase tracking-tighter">
                        <span class="flex items-center gap-1"><div class="w-2 h-2 rounded bg-green-500"></div> Low</span>
                        <span class="flex items-center gap-1"><div class="w-2 h-2 rounded bg-amber-500"></div> Med</span>
                        <span class="flex items-center gap-1"><div class="w-2 h-2 rounded bg-red-500"></div> High</span>
                    </div>
                </div>
                <div class="grid grid-cols-4 gap-4">
                    <!-- Header Row -->
                    <div class="text-xs font-semibold text-slate-400 px-2 py-1">Origin \ Dest</div>
                    <div class="text-xs font-semibold text-center text-slate-600 dark:text-slate-300 px-2 py-1">India (IND)</div>
                    <div class="text-xs font-semibold text-center text-slate-600 dark:text-slate-300 px-2 py-1">Angola (AGO)</div>
                    <div class="text-xs font-semibold text-center text-slate-600 dark:text-slate-300 px-2 py-1">Switzerland (CHE)</div>
                    <!-- India Row -->
                    <div class="text-xs font-semibold text-slate-600 dark:text-slate-300 flex items-center px-2">India</div>
                    <div class="h-24 bg-slate-50 dark:bg-slate-800 rounded-lg border-2 border-dashed border-slate-200 dark:border-slate-700 flex items-center justify-center text-slate-300 text-[10px]">INTERNAL</div>
                    <div class="h-24 bg-amber-500/20 border border-amber-500/30 rounded-lg p-3 flex flex-col justify-between">
                        <span class="text-xs font-bold text-amber-700 dark:text-amber-500">MODERATE</span>
                        <span class="text-[10px] text-slate-500">$2.4M Vol</span>
                    </div>
                    <div class="h-24 bg-green-500/20 border border-green-500/30 rounded-lg p-3 flex flex-col justify-between">
                        <span class="text-xs font-bold text-green-700 dark:text-green-500">LOW</span>
                        <span class="text-[10px] text-slate-500">$18.1M Vol</span>
                    </div>
                    <!-- Angola Row -->
                    <div class="text-xs font-semibold text-slate-600 dark:text-slate-300 flex items-center px-2">Angola</div>
                    <div class="h-24 bg-red-500/30 border border-red-500/40 rounded-lg p-3 flex flex-col justify-between relative overflow-hidden">
                        <div class="absolute -right-2 -top-2 opacity-10">
                            <span class="material-icons text-5xl">warning</span>
                        </div>
                        <span class="text-xs font-bold text-red-700 dark:text-red-500 uppercase">High Alert</span>
                        <span class="text-[10px] text-slate-500">$840K Vol</span>
                    </div>
                    <div class="h-24 bg-slate-50 dark:bg-slate-800 rounded-lg border-2 border-dashed border-slate-200 dark:border-slate-700 flex items-center justify-center text-slate-300 text-[10px]">INTERNAL</div>
                    <div class="h-24 bg-amber-500/20 border border-amber-500/30 rounded-lg p-3 flex flex-col justify-between">
                        <span class="text-xs font-bold text-amber-700 dark:text-amber-500">MODERATE</span>
                        <span class="text-[10px] text-slate-500">$1.1M Vol</span>
                    </div>
                </div>
            </div>

            <!-- Stats Summary -->
            <div class="col-span-4 space-y-4">
                <div class="bg-white dark:bg-slate-900 p-6 rounded-xl border border-slate-200 dark:border-slate-800">
                    <h3 class="text-xs font-semibold uppercase text-slate-400 tracking-widest mb-4">Risk Exposure Index</h3>
                    <div class="flex items-end gap-2 mb-2">
                        <span class="text-3xl font-bold tracking-tight dark:text-white">4.2</span>
                        <span class="text-red-500 text-xs font-bold pb-1">+0.8% today</span>
                    </div>
                    <div class="w-full bg-slate-100 dark:bg-slate-800 h-2 rounded-full overflow-hidden">
                        <div class="bg-primary h-full rounded-full" style="width: 42%"></div>
                    </div>
                    <p class="text-[10px] text-slate-500 mt-2">Overall platform risk remains within thresholds.</p>
                </div>
                <div class="bg-white dark:bg-slate-900 p-6 rounded-xl border border-slate-200 dark:border-slate-800">
                    <h3 class="text-xs font-semibold uppercase text-slate-400 tracking-widest mb-4">Flagged Volume</h3>
                    <div class="flex items-baseline justify-between mb-4">
                        <span class="text-2xl font-bold dark:text-white">$12.4M</span>
                        <span class="text-xs text-slate-400">of $45.2M Total</span>
                    </div>
                    <div class="flex h-4 gap-1">
                        <div class="bg-red-500 h-full rounded-sm" style="width: 15%" title="High Risk"></div>
                        <div class="bg-amber-500 h-full rounded-sm" style="width: 35%" title="Medium Risk"></div>
                        <div class="bg-slate-200 dark:bg-slate-700 h-full rounded-sm flex-grow" title="Low Risk"></div>
                    </div>
                </div>
            </div>
        </section>

        <!-- Flagged Transactions Table and Audit Trail -->
        <section class="grid grid-cols-12 gap-6 items-start">
            <!-- Flagged Transactions -->
            <div class="col-span-8 bg-white dark:bg-slate-900 rounded-xl border border-slate-200 dark:border-slate-800 overflow-hidden">
                <div class="px-6 py-4 border-b border-slate-200 dark:border-slate-800 flex justify-between items-center">
                    <h2 class="text-sm font-bold uppercase tracking-widest dark:text-white">Flagged Transactions</h2>
                    <div class="flex gap-2">
                        <button class="text-xs font-medium px-3 py-1 bg-slate-100 dark:bg-slate-800 rounded-full hover:bg-slate-200 transition-colors">Filter</button>
                        <button class="text-xs font-medium px-3 py-1 bg-slate-100 dark:bg-slate-800 rounded-full hover:bg-slate-200 transition-colors">Sort</button>
                    </div>
                </div>
                <div class="overflow-x-auto">
                    <table class="w-full text-left">
                        <thead>
                            <tr class="bg-slate-50 dark:bg-slate-900/50 text-[11px] font-bold text-slate-500 uppercase tracking-widest border-b border-slate-200 dark:border-slate-800">
                                <th class="px-6 py-4">Transaction ID</th>
                                <th class="px-6 py-4">Corridor</th>
                                <th class="px-6 py-4">Risk Reason</th>
                                <th class="px-6 py-4">Value</th>
                                <th class="px-6 py-4 text-right">Actions</th>
                            </tr>
                        </thead>
                        <tbody id="flaggedTable" class="divide-y divide-slate-100 dark:divide-slate-800 text-sm">
                            <tr><td colspan="5" class="px-6 py-4 text-center text-slate-500">Loading flagged transactions...</td></tr>
                        </tbody>
                    </table>
                </div>
            </div>

            <!-- Audit Trail -->
            <div class="col-span-4 bg-white dark:bg-slate-900 rounded-xl border border-slate-200 dark:border-slate-800 h-[500px] flex flex-col">
                <div class="px-6 py-4 border-b border-slate-200 dark:border-slate-800">
                    <h2 class="text-sm font-bold uppercase tracking-widest flex items-center gap-2 dark:text-white">
                        <span class="material-icons text-sm text-slate-400">history</span> Audit Trail
                    </h2>
                </div>
                <div id="auditTrail" class="flex-grow overflow-y-auto p-6 custom-scrollbar">
                    <div class="space-y-6 relative before:content-[''] before:absolute before:left-2 before:top-2 before:bottom-0 before:w-0.5 before:bg-slate-100 dark:before:bg-slate-800">
                        <p class="text-sm text-slate-500 dark:text-slate-400">Loading audit events...</p>
                    </div>
                </div>
                <div class="p-4 border-t border-slate-200 dark:border-slate-800">
                    <button class="w-full text-center text-xs font-bold text-primary uppercase tracking-widest hover:underline">View Full Audit Log</button>
                </div>
            </div>
        </section>
    </div>
</div>
@endsection

@push('scripts')
<script>
    let complianceStats = null;
    let flaggedTransactions = [];
    let auditEvents = [];

    async function loadComplianceData() {
        try {
            const [statsRes, flaggedRes, auditRes] = await Promise.all([
                window.api.get('/compliance/stats'),
                window.api.get('/compliance/flagged'),
                window.api.get('/compliance/audit-trail')
            ]);

            complianceStats = statsRes.data;
            flaggedTransactions = flaggedRes.data;
            auditEvents = auditRes.data;

            renderStats(complianceStats);
            renderFlaggedTransactions(flaggedTransactions);
            renderAuditTrail(auditEvents);
        } catch (error) {
            console.error('Error loading compliance data:', error);
        }
    }

    function renderStats(stats) {
        // Update Risk Index
        document.querySelector('.text-3xl.font-bold.tracking-tight').textContent = stats.risk_index;
        document.querySelector('.bg-primary.h-full.rounded-full').style.width = (stats.risk_index * 10) + '%';
        
        // Update Flagged Volume
        document.querySelector('.text-2xl.font-bold.dark\\:text-white').textContent = formatCurrency(stats.flagged_volume);
        document.querySelector('.text-xs.text-slate-400').textContent = `of ${formatCurrency(stats.total_volume)} Total`;
        
        // Update breakdown bar
        const highRiskBar = document.querySelector('[title="High Risk"]');
        const medRiskBar = document.querySelector('[title="Medium Risk"]');
        highRiskBar.style.width = (stats.flagged_percentage / 2) + '%';
        medRiskBar.style.width = (stats.flagged_percentage / 2) + '%';
    }

    function renderFlaggedTransactions(data) {
        const tableBody = document.getElementById('flaggedTable');
        if (data.length === 0) {
            tableBody.innerHTML = '<tr><td colspan="5" class="px-6 py-8 text-center text-slate-500">No flagged transactions found.</td></tr>';
            return;
        }

        const rows = data.map((tx, index) => {
            const riskClass = tx.risk_level === 'high' ? 'bg-red-100 dark:bg-red-900/30 text-red-700 dark:text-red-500' : 'bg-amber-100 dark:bg-amber-900/30 text-amber-700 dark:text-amber-500';
            const bgClass = index === 0 ? 'bg-slate-50/50 dark:bg-slate-800/20 border-l-2 border-l-primary' : '';
            const value = (parseFloat(tx.quantity) || 0) * (parseFloat(tx.unit_price) || 0);
            
            return `
                <tr class="hover:bg-slate-50 dark:hover:bg-slate-800/50 transition-colors group ${bgClass}">
                    <td class="px-6 py-4">
                        <div class="font-mono text-xs font-bold text-primary">${tx.transaction_code}</div>
                        <div class="text-[10px] text-slate-500 mt-0.5 truncate max-w-[150px]">${tx.buyer?.company_name} • ${tx.supplier?.company_name}</div>
                    </td>
                    <td class="px-6 py-4">
                        <div class="flex items-center gap-2">
                            <span class="px-1.5 py-0.5 bg-slate-100 dark:bg-slate-800 rounded text-[10px] font-bold">ANY</span>
                            <span class="material-icons text-xs text-slate-400">arrow_forward</span>
                            <span class="px-1.5 py-0.5 bg-slate-100 dark:bg-slate-800 rounded text-[10px] font-bold">ANY</span>
                        </div>
                    </td>
                    <td class="px-6 py-4">
                        <span class="px-2 py-1 rounded ${riskClass} text-[10px] font-bold uppercase">${tx.risk_reason || 'Manual Review'}</span>
                    </td>
                    <td class="px-6 py-4 font-semibold text-slate-700 dark:text-slate-300">
                        ${formatCurrency(value)}
                    </td>
                    <td class="px-6 py-4 text-right">
                        <div class="flex items-center justify-end gap-2">
                            <button onclick="freezeTransaction('${tx.uuid}')" class="px-3 py-1.5 bg-red-500 text-white text-[10px] font-bold rounded-lg uppercase hover:bg-red-600 transition-colors shadow-sm">Freeze</button>
                            <button class="px-3 py-1.5 bg-slate-100 dark:bg-slate-800 text-slate-700 dark:text-slate-300 text-[10px] font-bold rounded-lg uppercase hover:bg-slate-200 transition-colors">Request Info</button>
                        </div>
                    </td>
                </tr>
            `;
        }).join('');
        
        tableBody.innerHTML = rows;
    }

    function renderAuditTrail(data) {
        const trailContainer = document.getElementById('auditTrail');
        if (data.length === 0) {
            trailContainer.innerHTML = '<p class="text-sm text-slate-500">No recent activity.</p>';
            return;
        }

        const events = data.map(event => {
            const dotColor = event.type === 'high' ? 'bg-red-500' : event.type === 'primary' ? 'bg-primary' : 'bg-slate-300 dark:bg-slate-700';
            const time = new Date(event.created_at).toLocaleTimeString([], { hour: '2-digit', minute: '2-digit', second: '2-digit' });
            
            return `
                <div class="relative pl-8">
                    <div class="absolute left-0 top-1 w-4 h-4 rounded-full ${dotColor} ring-4 ring-white dark:ring-slate-900 z-10"></div>
                    <div class="flex justify-between items-start mb-1">
                        <span class="text-xs font-bold text-slate-800 dark:text-slate-200">${event.title}</span>
                        <span class="text-[10px] text-slate-400">${time}</span>
                    </div>
                    <p class="text-xs text-slate-600 dark:text-slate-400 leading-relaxed">${event.description}</p>
                </div>
            `;
        }).join('');
        
        trailContainer.innerHTML = `
            <div class="space-y-6 relative before:content-[''] before:absolute before:left-2 before:top-2 before:bottom-0 before:w-0.5 before:bg-slate-100 dark:before:bg-slate-800">
                ${events}
            </div>
        `;
    }

    async function freezeTransaction(uuid) {
        if (!confirm('Are you sure you want to freeze this transaction? This action will be logged.')) return;
        
        try {
            await window.api.post(`/compliance/transactions/${uuid}/freeze`);
            loadComplianceData(); // Reload
        } catch (error) {
            alert('Failed to freeze transaction.');
            console.error(error);
        }
    }

    function formatCurrency(amount) {
        return new Intl.NumberFormat('en-US', {
            style: 'currency',
            currency: 'USD',
            maximumFractionDigits: 0
        }).format(amount);
    }

    // Initialize
    loadComplianceData();
    // Refresh interval every 30 seconds
    setInterval(loadComplianceData, 30000);
</script>
@endpush
