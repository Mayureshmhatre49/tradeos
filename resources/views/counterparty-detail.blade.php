@extends('layouts.app')

@section('title', 'Counterparty Details')

@section('content')
<div class="min-h-screen bg-background-light dark:bg-background-dark">
    <!-- Header Section -->
    <header class="bg-white dark:bg-surface-dark border-b border-slate-200 dark:border-border-dark p-6">
        <div class="max-w-7xl mx-auto">
            <div class="flex flex-col md:flex-row md:items-center justify-between gap-4">
                <div>
                    <a href="/counterparties" class="text-slate-400 hover:text-primary flex items-center gap-1 text-sm mb-3 transition-colors">
                        <span class="material-icons text-sm">arrow_back</span>
                        Back to Counterparties
                    </a>
                    <div class="flex items-center gap-4">
                        <h1 id="companyName" class="text-3xl font-bold tracking-tight dark:text-white">Loading...</h1>
                        <span id="kycBadge" class="px-3 py-1 bg-slate-100 dark:bg-slate-800 text-slate-600 text-sm font-semibold rounded-full flex items-center gap-1.5">
                            <span class="material-icons text-xs">pending</span>
                            Loading
                        </span>
                    </div>
                </div>
                <div class="flex items-center gap-3">
                    <button id="editBtn" class="px-4 py-2 border border-slate-200 dark:border-slate-700 rounded-lg hover:bg-slate-50 dark:hover:bg-slate-800 font-medium transition-all text-sm flex items-center gap-2 dark:text-white">
                        <span class="material-icons text-sm">edit</span> Edit Entity
                    </button>
                    <button id="verifyBtn" class="px-4 py-2 bg-primary text-white rounded-lg hover:bg-primary/90 font-medium transition-all text-sm flex items-center gap-2">
                        <span class="material-icons text-sm">verified</span> Verify KYC
                    </button>
                </div>
            </div>
        </div>
    </header>

    <main class="max-w-7xl mx-auto p-6 space-y-8">
        <div class="grid grid-cols-1 lg:grid-cols-12 gap-8">
            <!-- Left Column (8 columns) -->
            <div class="lg:col-span-8 space-y-8">
                <!-- Entity Overview -->
                <section class="bg-white dark:bg-surface-dark border border-slate-200 dark:border-border-dark rounded-xl overflow-hidden shadow-sm">
                    <div class="px-6 py-4 border-b border-slate-200 dark:border-border-dark flex justify-between items-center">
                        <h2 class="font-bold flex items-center gap-2 dark:text-white uppercase tracking-wider text-xs">
                            <span class="material-icons text-primary text-lg">business</span>
                            Entity Information
                        </h2>
                    </div>
                    <div id="entityInfo" class="p-6 grid grid-cols-2 md:grid-cols-3 gap-y-6 gap-x-8">
                        <div class="col-span-3 text-center py-8 text-slate-400">Fetching records...</div>
                    </div>
                </section>

                <!-- Financial Stats & History -->
                <div class="grid grid-cols-1 md:grid-cols-2 gap-6">
                    <div class="bg-white dark:bg-surface-dark border border-slate-200 dark:border-border-dark rounded-xl p-6">
                        <h3 class="text-xs font-bold text-slate-400 uppercase tracking-widest mb-4">Trade Volume</h3>
                        <div class="flex items-baseline gap-2">
                            <span id="tradeVolume" class="text-2xl font-bold dark:text-white">$0</span>
                            <span class="text-xs text-emerald-500 font-medium">+15.2%</span>
                        </div>
                        <p class="text-xs text-slate-500 mt-1">Total value transacted with this partner</p>
                    </div>
                    <div class="bg-white dark:bg-surface-dark border border-slate-200 dark:border-border-dark rounded-xl p-6">
                        <h3 class="text-xs font-bold text-slate-400 uppercase tracking-widest mb-4">Pending Exposures</h3>
                        <div class="flex items-baseline gap-2">
                            <span id="pendingExposure" class="text-2xl font-bold dark:text-white">$0</span>
                            <span class="text-xs text-amber-500 font-medium">Active</span>
                        </div>
                        <p class="text-xs text-slate-500 mt-1">Capital currently in flight/unrealized</p>
                    </div>
                </div>

                <!-- KYC & Compliance Documents -->
                <section class="bg-white dark:bg-surface-dark border border-slate-200 dark:border-border-dark rounded-xl overflow-hidden shadow-sm">
                    <div class="px-6 py-4 border-b border-slate-200 dark:border-border-dark flex justify-between items-center">
                        <h2 class="font-bold flex items-center gap-2 dark:text-white uppercase tracking-wider text-xs">
                            <span class="material-icons text-primary text-lg">folder</span>
                            KYC Documents
                        </h2>
                        <button class="text-xs font-bold text-primary hover:underline">UPLOAD NEW</button>
                    </div>
                    <div class="divide-y divide-slate-100 dark:divide-slate-800">
                        <div class="p-4 hover:bg-slate-50 dark:hover:bg-slate-800/50 flex items-center justify-between transition-colors">
                            <div class="flex items-center gap-3">
                                <div class="w-10 h-10 rounded bg-blue-100 dark:bg-blue-900/30 flex items-center justify-center">
                                    <span class="material-icons text-blue-600">description</span>
                                </div>
                                <div>
                                    <p class="text-sm font-semibold dark:text-white">Certificate of Incorporation</p>
                                    <p class="text-[10px] text-slate-500">Verified • 2.4 MB • Updated 12 days ago</p>
                                </div>
                            </div>
                            <button class="material-icons text-slate-400 hover:text-primary transition-colors">download</button>
                        </div>
                        <div class="p-4 hover:bg-slate-50 dark:hover:bg-slate-800/50 flex items-center justify-between transition-colors">
                            <div class="flex items-center gap-3">
                                <div class="w-10 h-10 rounded bg-purple-100 dark:bg-purple-900/30 flex items-center justify-center">
                                    <span class="material-icons text-purple-600">group</span>
                                </div>
                                <div>
                                    <p class="text-sm font-semibold dark:text-white">UBO Declaration Form</p>
                                    <p class="text-[10px] text-slate-500">Awaiting Renewal • 1.1 MB • Updated 3 months ago</p>
                                </div>
                            </div>
                            <button class="material-icons text-slate-400 hover:text-primary transition-colors">download</button>
                        </div>
                    </div>
                </section>
            </div>

            <!-- Right Column (4 columns) -->
            <div class="lg:col-span-4 space-y-6">
                <!-- Risk Level Card -->
                <div class="bg-white dark:bg-surface-dark border border-slate-200 dark:border-border-dark rounded-xl p-6 shadow-sm">
                    <h3 class="text-xs font-bold text-slate-400 uppercase tracking-widest mb-6">Risk Profile</h3>
                    <div class="flex items-center justify-center mb-6">
                        <div class="relative">
                            <svg class="w-32 h-32 transform -rotate-90">
                                <circle cx="64" cy="64" r="58" stroke="currentColor" stroke-width="8" fill="transparent" class="text-slate-100 dark:text-slate-800" />
                                <circle id="riskCircle" cx="64" cy="64" r="58" stroke="currentColor" stroke-width="8" fill="transparent" stroke-dasharray="364.4" stroke-dashoffset="364.4" class="text-primary transition-all duration-1000" />
                            </svg>
                            <div class="absolute inset-0 flex flex-col items-center justify-center">
                                <span id="riskScore" class="text-2xl font-bold dark:text-white">0%</span>
                                <span class="text-[10px] font-bold text-slate-400 uppercase">Score</span>
                            </div>
                        </div>
                    </div>
                    <div id="riskLevelDesc" class="text-center p-3 rounded-lg bg-emerald-50 dark:bg-emerald-900/20 text-emerald-700 dark:text-emerald-400 text-xs font-bold uppercase tracking-widest mb-4">
                        LOW RISK
                    </div>
                    <p class="text-xs text-slate-500 text-center leading-relaxed">This partner has a clean history and all KYC documents are currently valid.</p>
                </div>

                <!-- Quick Actions -->
                <div class="bg-white dark:bg-surface-dark border border-slate-200 dark:border-border-dark rounded-xl p-6 shadow-sm">
                    <h3 class="text-xs font-bold text-slate-400 uppercase tracking-widest mb-4">Quick Actions</h3>
                    <div class="space-y-3">
                        <button class="w-full flex items-center justify-between p-3 rounded-lg border border-slate-100 dark:border-slate-800 hover:bg-slate-50 dark:hover:bg-slate-800 transition-colors group">
                            <div class="flex items-center gap-3">
                                <span class="material-icons text-slate-400 group-hover:text-primary">mail</span>
                                <span class="text-sm font-medium dark:text-slate-200">Request Document</span>
                            </div>
                            <span class="material-icons text-slate-300">chevron_right</span>
                        </button>
                        <button class="w-full flex items-center justify-between p-3 rounded-lg border border-slate-100 dark:border-slate-800 hover:bg-slate-50 dark:hover:bg-slate-800 transition-colors group">
                            <div class="flex items-center gap-3">
                                <span class="material-icons text-slate-400 group-hover:text-primary">security</span>
                                <span class="text-sm font-medium dark:text-slate-200">Compliance Check</span>
                            </div>
                            <span class="material-icons text-slate-300">chevron_right</span>
                        </button>
                    </div>
                </div>

                <!-- Recent Conversations/Notes -->
                <div class="bg-white dark:bg-surface-dark border border-slate-200 dark:border-border-dark rounded-xl p-6 shadow-sm">
                    <h3 class="text-xs font-bold text-slate-400 uppercase tracking-widest mb-4">Record Notes</h3>
                    <div class="space-y-4">
                        <div class="p-3 bg-slate-50 dark:bg-slate-800/50 rounded-lg">
                            <p class="text-xs text-slate-600 dark:text-slate-400 leading-relaxed italic">"Onboarded after vetting by trade committee. High growth potential."</p>
                            <div class="flex justify-between items-center mt-2">
                                <span class="text-[10px] text-slate-500 font-bold">Admin</span>
                                <span class="text-[10px] text-slate-400">Feb 14, 2026</span>
                            </div>
                        </div>
                    </div>
                </div>
            </div>
        </div>
    </main>
</div>
@endsection

@push('scripts')
<script>
    const uuid = "{{ $uuid }}";
    const urlParams = new URLSearchParams(window.location.search);
    const type = urlParams.get('type') || 'buyer';
    let partnerData = null;

    async function loadPartnerDetails() {
        try {
            const endpoint = type === 'buyer' ? `/buyers/${uuid}` : `/suppliers/${uuid}`;
            const response = await window.api.get(endpoint);
            partnerData = response.data;
            
            renderDetails(partnerData);
        } catch (error) {
            console.error('Error loading partner details:', error);
            document.getElementById('companyName').textContent = 'Error Loading';
        }
    }

    function renderDetails(data) {
        // Header
        document.getElementById('companyName').textContent = data.company_name;
        
        const kycBadge = document.getElementById('kycBadge');
        if (data.kyc_status === 'verified' || data.status === 'approved') {
            kycBadge.className = 'px-3 py-1 bg-emerald-100 dark:bg-emerald-900/30 text-emerald-600 text-sm font-semibold rounded-full flex items-center gap-1.5';
            kycBadge.innerHTML = '<span class="material-icons text-xs">verified</span> Verified';
        } else if (data.kyc_status === 'rejected' || data.status === 'rejected') {
            kycBadge.className = 'px-3 py-1 bg-red-100 dark:bg-red-900/30 text-red-600 text-sm font-semibold rounded-full flex items-center gap-1.5';
            kycBadge.innerHTML = '<span class="material-icons text-xs">cancel</span> Rejected';
        } else {
            kycBadge.className = 'px-3 py-1 bg-amber-100 dark:bg-amber-900/30 text-amber-600 text-sm font-semibold rounded-full flex items-center gap-1.5';
            kycBadge.innerHTML = '<span class="material-icons text-xs">pending</span> Pending';
        }

        // Risk Profile
        const riskScore = data.risk_level === 'high' ? 85 : data.risk_level === 'medium' ? 45 : 12;
        const offset = 364.4 - (364.4 * (riskScore / 100));
        document.getElementById('riskCircle').style.strokeDashoffset = offset;
        document.getElementById('riskScore').textContent = riskScore + '%';
        
        const riskDesc = document.getElementById('riskLevelDesc');
        if (data.risk_level === 'high') {
            riskDesc.className = 'text-center p-3 rounded-lg bg-red-50 dark:bg-red-900/20 text-red-700 dark:text-red-400 text-xs font-bold uppercase tracking-widest mb-4';
            riskDesc.textContent = 'HIGH RISK';
            document.getElementById('riskCircle').className.baseVal = 'text-red-500 transition-all duration-1000';
        } else if (data.risk_level === 'medium') {
            riskDesc.className = 'text-center p-3 rounded-lg bg-amber-50 dark:bg-amber-900/20 text-amber-700 dark:text-amber-400 text-xs font-bold uppercase tracking-widest mb-4';
            riskDesc.textContent = 'MEDIUM RISK';
            document.getElementById('riskCircle').className.baseVal = 'text-amber-500 transition-all duration-1000';
        }

        // Info Grid
        const infoHtml = `
            <div>
                <p class="text-[10px] font-bold text-slate-400 uppercase tracking-widest mb-1">Entity Type</p>
                <p class="text-sm font-semibold dark:text-white capitalize">${type}</p>
            </div>
            <div>
                <p class="text-[10px] font-bold text-slate-400 uppercase tracking-widest mb-1">Country</p>
                <p class="text-sm font-semibold dark:text-white">${data.country || 'Not Set'}</p>
            </div>
            <div>
                <p class="text-[10px] font-bold text-slate-400 uppercase tracking-widest mb-1">Registration No.</p>
                <p class="text-sm font-semibold dark:text-white">${data.tax_id || 'N/A'}</p>
            </div>
            <div>
                <p class="text-[10px] font-bold text-slate-400 uppercase tracking-widest mb-1">Relationship Date</p>
                <p class="text-sm font-semibold dark:text-white">${new Date(data.created_at).toLocaleDateString()}</p>
            </div>
            <div>
                <p class="text-[10px] font-bold text-slate-400 uppercase tracking-widest mb-1">Primary Contact</p>
                <p class="text-sm font-semibold dark:text-white">${data.contact_person || 'N/A'}</p>
            </div>
            <div>
                <p class="text-[10px] font-bold text-slate-400 uppercase tracking-widest mb-1">Email Address</p>
                <p class="text-sm font-semibold dark:text-white text-primary">${data.email || 'N/A'}</p>
            </div>
        `;
        document.getElementById('entityInfo').innerHTML = infoHtml;
    }

    // Initialize
    loadPartnerDetails();
</script>
@endpush
