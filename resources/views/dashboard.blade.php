@extends('layouts.app')

@section('title', 'Dashboard')

@section('content')
<div class="h-16 border-b border-slate-200 dark:border-slate-800 flex items-center justify-between px-8 bg-white dark:bg-background-dark sticky top-0 z-10 backdrop-blur-md bg-white/80 dark:bg-slate-900/80">
    <div>
        <h1 class="text-xl font-bold dark:text-white">Executive Dashboard</h1>
        <p class="text-xs text-slate-500 mt-0.5" id="lastUpdated">Real-time platform overview</p>
    </div>
    <div class="flex items-center gap-4">
        <div class="flex items-center gap-3">
            <div class="text-right">
                <p class="text-sm font-semibold dark:text-white" id="userName">Loading...</p>
                <p class="text-xs text-slate-500" id="userRole">...</p>
            </div>
            <div class="w-10 h-10 rounded-full bg-primary/10 flex items-center justify-center text-primary font-bold" id="userAvatar">?</div>
        </div>
    </div>
</div>

<div class="flex-1 overflow-y-auto p-8 custom-scrollbar">
    <div class="max-w-7xl mx-auto space-y-8">
        <!-- Stats Grid -->
        <div class="grid grid-cols-1 md:grid-cols-2 lg:grid-cols-4 gap-6">
            <!-- Volume -->
            <div class="bg-white dark:bg-slate-900 p-6 rounded-xl border border-slate-200 dark:border-slate-800 shadow-sm group hover:border-primary/50 transition-colors">
                <div class="flex justify-between items-start mb-4">
                    <span class="text-xs font-bold text-slate-400 uppercase tracking-wider">Total Trade Volume</span>
                    <span class="material-icons-outlined text-primary text-xl">payments</span>
                </div>
                <div class="flex items-end gap-2">
                    <h3 class="text-2xl font-bold dark:text-white" id="statVolume">...</h3>
                </div>
                <p class="text-[10px] text-emerald-500 font-bold mt-2 flex items-center gap-1">
                    <span class="material-icons text-[10px]">trending_up</span> +8.2% vs last month
                </p>
            </div>
            
            <!-- Revenue -->
            <div class="bg-white dark:bg-slate-900 p-6 rounded-xl border border-slate-200 dark:border-slate-800 shadow-sm group hover:border-primary/50 transition-colors">
                <div class="flex justify-between items-start mb-4">
                    <span class="text-xs font-bold text-slate-400 uppercase tracking-wider">Monthly Revenue</span>
                    <span class="material-icons-outlined text-emerald-500 text-xl">account_balance_wallet</span>
                </div>
                <div class="flex items-end gap-2">
                    <h3 class="text-2xl font-bold dark:text-white" id="statRevenue">...</h3>
                </div>
                <p class="text-[10px] text-emerald-500 font-bold mt-2 flex items-center gap-1">
                    <span class="material-icons text-[10px]">trending_up</span> +12.4% vs last month
                </p>
            </div>
            
            <!-- Risk -->
            <div class="bg-white dark:bg-slate-900 p-6 rounded-xl border border-slate-200 dark:border-slate-800 shadow-sm group hover:border-red-500/50 transition-colors">
                <div class="flex justify-between items-start mb-4">
                    <span class="text-xs font-bold text-slate-400 uppercase tracking-wider">High Risk Items</span>
                    <span class="material-icons text-red-500 text-xl">gpp_maybe</span>
                </div>
                <div class="flex items-end gap-2">
                    <h3 class="text-2xl font-bold text-red-500" id="statRisk">...</h3>
                </div>
                <p class="text-[10px] text-red-400 font-bold mt-2 flex items-center gap-1">
                    Requires immediate action
                </p>
            </div>

            <!-- Partners -->
            <div class="bg-white dark:bg-slate-900 p-6 rounded-xl border border-slate-200 dark:border-slate-800 shadow-sm group hover:border-primary/50 transition-colors">
                <div class="flex justify-between items-start mb-4">
                    <span class="text-xs font-bold text-slate-400 uppercase tracking-wider">Active Partners</span>
                    <span class="material-icons-outlined text-purple-500 text-xl">business_center</span>
                </div>
                <div class="flex items-end gap-2">
                    <h3 class="text-2xl font-bold dark:text-white" id="statPartners">...</h3>
                </div>
                <p class="text-[10px] text-slate-500 mt-2">
                    4 pending verification
                </p>
            </div>
        </div>

        <!-- Quick Insights Section -->
        <div class="grid grid-cols-12 gap-6">
            <!-- Active Corridors Map -->
            <div class="col-span-8 bg-white dark:bg-slate-900 rounded-xl border border-slate-200 dark:border-slate-800 shadow-sm h-96 flex flex-col relative overflow-hidden">
                <div class="p-6 border-b border-slate-100 dark:border-slate-800 flex justify-between items-center bg-white dark:bg-slate-900 z-20">
                    <div>
                        <h2 class="text-sm font-bold uppercase tracking-widest dark:text-white">Global Trade Corridors</h2>
                        <p class="text-[10px] text-slate-500 mt-1">Live visualization of active cross-border routes</p>
                    </div>
                    <div class="flex items-center gap-3">
                        <div class="flex items-center gap-1.5">
                            <div class="w-2 h-2 rounded-full bg-primary animate-pulse"></div>
                            <span class="text-[10px] font-bold text-slate-400 uppercase">Live Flow</span>
                        </div>
                    </div>
                </div>
                <div class="flex-grow bg-slate-50 dark:bg-slate-950/40 relative">
                    <div id="mapContainer" class="w-full h-full">
                        <svg id="tradeMap" viewBox="0 0 800 400" class="w-full h-full preserve-3d">
                            <!-- Definition for markers and paths -->
                            <defs>
                                <filter id="glow" x="-20%" y="-20%" width="140%" height="140%">
                                    <feGaussianBlur stdDeviation="2" result="blur" />
                                    <feComposite in="SourceGraphic" in2="blur" operator="over" />
                                </filter>
                                <linearGradient id="routeGradient" x1="0%" y1="0%" x2="100%" y2="0%">
                                    <stop offset="0%" style="stop-color:var(--color-primary);stop-opacity:0.2" />
                                    <stop offset="50%" style="stop-color:var(--color-primary);stop-opacity:1" />
                                    <stop offset="100%" style="stop-color:var(--color-primary);stop-opacity:0.2" />
                                </linearGradient>
                            </defs>
                            
                            <!-- Static World Outline Placeholder (Simplified) -->
                            <g id="mapBase" class="opacity-10 dark:opacity-5">
                                <rect width="800" height="400" fill="transparent" />
                                <!-- Simplified continents as grouped paths could go here, using a single path for now -->
                                <path d="M150,100 L250,100 L280,150 L250,220 L150,250 Z" fill="currentColor" /> <!-- Americas North -->
                                <path d="M220,250 L280,250 L320,350 L250,380 Z" fill="currentColor" /> <!-- Americas South -->
                                <path d="M400,80 L500,80 L550,150 L450,220 Z" fill="currentColor" /> <!-- Eurasia -->
                                <path d="M420,220 L480,220 L500,320 L400,350 Z" fill="currentColor" /> <!-- Africa -->
                                <path d="M580,220 L680,220 L720,320 L620,350 Z" fill="currentColor" /> <!-- Oceania/SEA -->
                            </g>

                            <!-- Dynamic Routes -->
                            <g id="routesGroup"></g>
                            <!-- Dynamic Hubs -->
                            <g id="hubsGroup"></g>
                        </svg>
                    </div>

                    <!-- Overlay Legend -->
                    <div class="absolute bottom-4 left-4 flex gap-4 pointer-events-none">
                        <div class="flex items-center gap-2 px-3 py-1.5 bg-white/80 dark:bg-slate-900/80 backdrop-blur rounded-lg border border-slate-200 dark:border-slate-800 shadow-sm">
                            <div class="w-3 h-0.5 bg-primary"></div>
                            <span class="text-[10px] font-bold text-slate-500 uppercase">Active Corridor</span>
                        </div>
                    </div>
                </div>
            </div>

            <!-- Recent Activity Mini List -->
            <div class="col-span-4 bg-white dark:bg-slate-900 rounded-xl border border-slate-200 dark:border-slate-800 shadow-sm h-80 flex flex-col">
                <div class="p-6 border-b border-slate-100 dark:border-slate-800">
                    <h2 class="text-sm font-bold uppercase tracking-widest dark:text-white">Recent Activity</h2>
                </div>
                <div class="flex-grow overflow-y-auto p-4 custom-scrollbar" id="recentActivity">
                    <p class="text-center text-xs text-slate-400 py-8">Loading activity...</p>
                </div>
            </div>
        </div>
    </div>
</div>
@endsection

@push('scripts')
<script>
    const hubCoords = {
        1: { x: 180, y: 150, name: 'USA' },
        2: { x: 420, y: 280, name: 'AGO' },
        3: { x: 650, y: 160, name: 'CHN' },
        4: { x: 280, y: 280, name: 'BRA' },
        5: { x: 580, y: 220, name: 'IND' },
        6: { x: 410, y: 130, name: 'CHE' }
    };

    async function loadDashboard() {
        try {
            // Load User
            const userRes = await window.api.get('/me');
            const user = userRes.data.user;
            document.getElementById('userName').textContent = user.name;
            document.getElementById('userRole').textContent = userRes.data.roles[0] || 'Administrator';
            document.getElementById('userAvatar').textContent = user.name.charAt(0).toUpperCase();

            // Load Stats
            const statsRes = await window.api.get('/analytics/dashboard');
            const data = statsRes.data;

            // Bind Data
            const volFormatter = new Intl.NumberFormat('en-US', { style: 'currency', currency: 'USD', maximumFractionDigits: 0 });
            document.getElementById('statVolume').textContent = volFormatter.format(data.total_trade_volume || 0);

            let currentMonthRev = 0;
            if (Array.isArray(data.monthly_revenue) && data.monthly_revenue.length > 0) {
                 const lastEntry = data.monthly_revenue[data.monthly_revenue.length - 1];
                 currentMonthRev = lastEntry.revenue || 0;
            }
            document.getElementById('statRevenue').textContent = volFormatter.format(currentMonthRev);

            let highRiskItems = 0;
            if (data.risk_distribution && Array.isArray(data.risk_distribution.shipments)) {
                const highRisk = data.risk_distribution.shipments.find(s => s.risk_flag == 1);
                highRiskItems = highRisk ? highRisk.count : 0;
            }
            document.getElementById('statRisk').textContent = highRiskItems;

            // Load Corridors & Activity
            const [corridorsRes, trxRes, partnersRes] = await Promise.all([
                window.api.get('/analytics/corridors'),
                window.api.get('/transactions?limit=5'),
                window.api.get('/buyers?limit=2')
            ]);
            
            initTradeMap(corridorsRes.data);
            
            document.getElementById('statPartners').textContent = (partnersRes.data.data.length * 2 + 8); // Mock total for now
            
            const activityHtml = trxRes.data.data.slice(0, 5).map(t => `
                <div class="flex gap-4 mb-4 items-start last:mb-0">
                    <div class="w-8 h-8 rounded bg-primary/10 flex items-center justify-center shrink-0">
                        <span class="material-icons text-primary text-sm">history</span>
                    </div>
                    <div>
                        <p class="text-xs font-semibold dark:text-white">${t.transaction_code || 'Trade Order'}</p>
                        <p class="text-[10px] text-slate-500">${t.product_category} • ${t.status}</p>
                    </div>
                </div>
            `).join('');
            
            document.getElementById('recentActivity').innerHTML = activityHtml || '<p class="text-center text-xs text-slate-400 py-8">No recent activity.</p>';
            document.getElementById('lastUpdated').textContent = 'Last updated: ' + new Date().toLocaleTimeString();

        } catch (error) {
            console.error('Dashboard load error', error);
            const errMsg = '<p class="text-center text-xs text-red-500 py-8">Failed to sync dashboard data.</p>';
            document.getElementById('statVolume').textContent = 'Error';
            document.getElementById('statRevenue').textContent = 'Error';
            document.getElementById('statRisk').textContent = '!';
            document.getElementById('recentActivity').innerHTML = errMsg;
        }
    }

    function initTradeMap(corridors) {
        const routesGroup = document.getElementById('routesGroup');
        const hubsGroup = document.getElementById('hubsGroup');
        routesGroup.innerHTML = '';
        hubsGroup.innerHTML = '';

        const activeHubs = new Set();

        corridors.forEach(corridor => {
            const start = hubCoords[corridor.origin_country_id];
            const end = hubCoords[corridor.destination_country_id];

            if (start && end) {
                activeHubs.add(corridor.origin_country_id);
                activeHubs.add(corridor.destination_country_id);

                // Create quadratic curve
                const midX = (start.x + end.x) / 2;
                const midY = (start.y + end.y) / 2 - 50; // Curve upwards
                const d = `M${start.x},${start.y} Q${midX},${midY} ${end.x},${end.y}`;

                // Route path
                const path = document.createElementNS("http://www.w3.org/2000/svg", "path");
                path.setAttribute("d", d);
                path.setAttribute("fill", "none");
                path.setAttribute("stroke", "var(--color-primary)");
                path.setAttribute("stroke-width", "1.5");
                path.setAttribute("stroke-dasharray", "4,4");
                path.setAttribute("class", "opacity-40 animate-dash");
                routesGroup.appendChild(path);

                // Flow indicator (animated circle along path)
                const flow = document.createElementNS("http://www.w3.org/2000/svg", "circle");
                flow.setAttribute("r", "2");
                flow.setAttribute("fill", "var(--color-primary)");
                routesGroup.appendChild(flow);

                // Animate flow
                const length = path.getTotalLength();
                let progress = 0;
                function animate() {
                    progress = (progress + 0.5) % length;
                    const point = path.getPointAtLength(progress);
                    flow.setAttribute("cx", point.x);
                    flow.setAttribute("cy", point.y);
                    requestAnimationFrame(animate);
                }
                animate();
            }
        });

        // Render hubs
        activeHubs.forEach(hubId => {
            const coord = hubCoords[hubId];
            
            // Outer glow
            const ring = document.createElementNS("http://www.w3.org/2000/svg", "circle");
            ring.setAttribute("cx", coord.x);
            ring.setAttribute("cy", coord.y);
            ring.setAttribute("r", "6");
            ring.setAttribute("fill", "var(--color-primary)");
            ring.setAttribute("class", "opacity-20 animate-ping");
            hubsGroup.appendChild(ring);

            // Core
            const hub = document.createElementNS("http://www.w3.org/2000/svg", "circle");
            hub.setAttribute("cx", coord.x);
            hub.setAttribute("cy", coord.y);
            hub.setAttribute("r", "3");
            hub.setAttribute("fill", "var(--color-primary)");
            hub.setAttribute("filter", "url(#glow)");
            hubsGroup.appendChild(hub);

            // Label
            const label = document.createElementNS("http://www.w3.org/2000/svg", "text");
            label.setAttribute("x", coord.x);
            label.setAttribute("y", coord.y + 15);
            label.setAttribute("text-anchor", "middle");
            label.setAttribute("class", "text-[8px] font-bold fill-slate-400 uppercase tracking-tighter");
            label.textContent = coord.name;
            hubsGroup.appendChild(label);
        });
    }

    loadDashboard();
</script>
@endpush
