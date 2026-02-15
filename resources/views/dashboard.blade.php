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
        <div class="grid grid-cols-1 lg:grid-cols-12 gap-6">
            <div class="lg:col-span-8 bg-white dark:bg-slate-900 rounded-xl border border-slate-200 dark:border-slate-800 shadow-sm h-96 flex flex-col relative overflow-hidden group">
                <div class="p-4 px-6 flex justify-between items-center z-20 relative border-b border-slate-100 dark:border-slate-800">
                    <div>
                        <h2 class="text-sm font-bold uppercase tracking-widest dark:text-white flex items-center gap-2">
                            Global Trade Corridors
                            <span class="flex h-2 w-2 relative">
                                <span class="animate-ping absolute inline-flex h-full w-full rounded-full bg-primary opacity-75"></span>
                                <span class="relative inline-flex rounded-full h-2 w-2 bg-primary"></span>
                            </span>
                        </h2>
                        <p class="text-[10px] text-slate-500 mt-0.5">Real-time cross-border logistics flow</p>
                    </div>
                    
                    <div class="flex gap-2">
                        <div class="px-2 py-1 bg-slate-100 dark:bg-slate-800 rounded text-[10px] font-medium text-slate-500 dark:text-slate-400 border border-slate-200 dark:border-slate-700">
                            Live
                        </div>
                    </div>
                </div>

                <div class="flex-grow relative z-0">
                    <div id="tradeMapLeaflet" class="w-full h-full"></div>
                </div>
            </div>

            <!-- Recent Activity Mini List -->
            <div class="lg:col-span-4 bg-white dark:bg-slate-900 rounded-xl border border-slate-200 dark:border-slate-800 shadow-sm h-96 flex flex-col">
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
<!-- Leaflet.js CDN -->
<link rel="stylesheet" href="https://unpkg.com/leaflet@1.9.4/dist/leaflet.css" />
<script src="https://unpkg.com/leaflet@1.9.4/dist/leaflet.js"></script>

<style>
    /* Remove Leaflet default chrome for a clean embed */
    #tradeMapLeaflet .leaflet-control-attribution { display: none !important; }
    #tradeMapLeaflet .leaflet-control-zoom { display: none !important; }
    
    /* Pulsing marker animation */
    @keyframes pulse-ring {
        0% { transform: scale(0.5); opacity: 1; }
        100% { transform: scale(2.5); opacity: 0; }
    }
    .pulse-marker {
        position: relative;
    }
    .pulse-marker .pulse-ring {
        position: absolute;
        width: 100%;
        height: 100%;
        border-radius: 50%;
        background: rgba(25, 93, 230, 0.3);
        animation: pulse-ring 2s cubic-bezier(0.215, 0.61, 0.355, 1) infinite;
        top: 0; left: 0;
    }
    .pulse-marker .pulse-core {
        position: absolute;
        width: 10px;
        height: 10px;
        border-radius: 50%;
        background: #195de6;
        border: 2px solid white;
        box-shadow: 0 0 12px rgba(25, 93, 230, 0.6);
        top: 50%;
        left: 50%;
        transform: translate(-50%, -50%);
    }
    .hub-label {
        background: none !important;
        border: none !important;
        box-shadow: none !important;
        font-size: 9px;
        font-weight: 700;
        color: #64748b;
        text-transform: uppercase;
        letter-spacing: 0.1em;
        white-space: nowrap;
    }
    
    /* Animated dashes on route lines */
    @keyframes dash-flow {
        to { stroke-dashoffset: -20; }
    }
    .animated-route {
        animation: dash-flow 1s linear infinite;
    }
</style>

<script>
    // Hub coordinates using real lat/lng
    const hubCoords = {
        1: { lat: 38.9, lng: -98.5, name: 'USA' },
        2: { lat: -8.8, lng: 17.5, name: 'AGO' },
        3: { lat: 35.0, lng: 105.0, name: 'CHN' },
        4: { lat: -14.2, lng: -51.9, name: 'BRA' },
        5: { lat: 20.6, lng: 78.9, name: 'IND' },
        6: { lat: 46.8, lng: 8.2, name: 'CHE' }
    };

    let tradeMap = null;
    const routeLayers = [];

    function initLeafletMap() {
        // Detect dark mode
        const isDark = document.documentElement.classList.contains('dark');
        
        const tileUrl = isDark
            ? 'https://{s}.basemaps.cartocdn.com/dark_nolabels/{z}/{x}/{y}{r}.png'
            : 'https://{s}.basemaps.cartocdn.com/light_nolabels/{z}/{x}/{y}{r}.png';
        
        tradeMap = L.map('tradeMapLeaflet', {
            center: [20, 15],
            zoom: 2,
            minZoom: 2,
            maxZoom: 5,
            zoomControl: false,
            attributionControl: false,
            scrollWheelZoom: false,
            dragging: true,
            doubleClickZoom: false,
            touchZoom: false,
            boxZoom: false,
            keyboard: false,
            worldCopyJump: true
        });

        L.tileLayer(tileUrl, {
            subdomains: 'abcd',
            maxZoom: 19
        }).addTo(tradeMap);
    }

    function initTradeMap(corridors) {
        if (!tradeMap) return;

        // Clear previous routes
        routeLayers.forEach(l => tradeMap.removeLayer(l));
        routeLayers.length = 0;

        const activeHubs = new Set();

        corridors.forEach(corridor => {
            const start = hubCoords[corridor.origin_country_id];
            const end = hubCoords[corridor.destination_country_id];

            if (start && end) {
                activeHubs.add(corridor.origin_country_id);
                activeHubs.add(corridor.destination_country_id);

                // Create curved path between two points
                const latlngs = getCurvedPoints(
                    [start.lat, start.lng],
                    [end.lat, end.lng]
                );

                // Background route (faint)
                const bgRoute = L.polyline(latlngs, {
                    color: '#195de6',
                    weight: 1.5,
                    opacity: 0.15,
                    smoothFactor: 1
                }).addTo(tradeMap);
                routeLayers.push(bgRoute);

                // Animated foreground route
                const fgRoute = L.polyline(latlngs, {
                    color: '#195de6',
                    weight: 2,
                    opacity: 0.6,
                    dashArray: '6, 8',
                    smoothFactor: 1,
                    className: 'animated-route'
                }).addTo(tradeMap);
                routeLayers.push(fgRoute);
            }
        });

        // Draw hub markers
        activeHubs.forEach(hubId => {
            const coord = hubCoords[hubId];

            // Pulsing circle marker
            const pulseIcon = L.divIcon({
                className: 'pulse-marker',
                html: '<div class="pulse-ring"></div><div class="pulse-core"></div>',
                iconSize: [24, 24],
                iconAnchor: [12, 12]
            });

            const marker = L.marker([coord.lat, coord.lng], { icon: pulseIcon, interactive: false })
                .addTo(tradeMap);
            routeLayers.push(marker);

            // Text label below hub
            const labelIcon = L.divIcon({
                className: 'hub-label',
                html: `<span>${coord.name}</span>`,
                iconSize: [40, 12],
                iconAnchor: [20, -8]
            });

            const label = L.marker([coord.lat, coord.lng], { icon: labelIcon, interactive: false })
                .addTo(tradeMap);
            routeLayers.push(label);
        });
    }

    // Generate curved points between two lat/lng coordinates
    function getCurvedPoints(start, end, numPoints = 30) {
        const points = [];
        const midLat = (start[0] + end[0]) / 2;
        const midLng = (start[1] + end[1]) / 2;

        // Calculate offset for the curve (perpendicular to the line)
        const dLat = end[0] - start[0];
        const dLng = end[1] - start[1];
        const dist = Math.sqrt(dLat * dLat + dLng * dLng);
        
        // Curve offset: bigger for longer distances
        const offset = Math.min(dist * 0.25, 25);
        
        // Control point: offset perpendicular to the midpoint (upward on map)
        const cpLat = midLat + offset;
        const cpLng = midLng;

        for (let i = 0; i <= numPoints; i++) {
            const t = i / numPoints;
            // Quadratic Bezier
            const lat = (1 - t) * (1 - t) * start[0] + 2 * (1 - t) * t * cpLat + t * t * end[0];
            const lng = (1 - t) * (1 - t) * start[1] + 2 * (1 - t) * t * cpLng + t * t * end[1];
            points.push([lat, lng]);
        }

        return points;
    }

    async function loadDashboard() {
        try {
            // Load User
            const userRes = await window.api.get('/me');
            if (userRes && userRes.data) {
                const user = userRes.data.user;
                if (user) {
                    document.getElementById('userName').textContent = user.name || 'User';
                    document.getElementById('userAvatar').textContent = (user.name || '?').charAt(0).toUpperCase();
                }
                const roles = userRes.data.roles;
                document.getElementById('userRole').textContent = roles && roles.length ? roles[0] : 'Administrator';
            }

            // Load Stats
            const statsRes = await window.api.get('/analytics/dashboard');
            const data = statsRes.data || {};

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
            try {
                const [corridorsRes, trxRes, partnersRes] = await Promise.all([
                    window.api.get('/analytics/corridors'),
                    window.api.get('/transactions?limit=5'),
                    window.api.get('/buyers?limit=2')
                ]);
                
                if (corridorsRes.data) {
                    initTradeMap(corridorsRes.data);
                }
                
                if (partnersRes.data && partnersRes.data.data) {
                   document.getElementById('statPartners').textContent = (partnersRes.data.data.length * 2 + 8); 
                }
                
                if (trxRes.data && trxRes.data.data) {
                    const activityHtml = trxRes.data.data.slice(0, 5).map(t => `
                        <div class="flex gap-4 mb-4 items-start last:mb-0 group/item">
                            <div class="w-8 h-8 rounded bg-primary/10 flex items-center justify-center shrink-0 group-hover/item:bg-primary/20 transition-colors">
                                <span class="material-icons text-primary text-sm">history</span>
                            </div>
                            <div>
                                <p class="text-xs font-semibold dark:text-white group-hover/item:text-primary transition-colors">${t.transaction_code || 'Trade Order'}</p>
                                <p class="text-[10px] text-slate-500">${t.product_category} • ${t.status}</p>
                            </div>
                        </div>
                    `).join('');
                    document.getElementById('recentActivity').innerHTML = activityHtml || '<p class="text-center text-xs text-slate-400 py-8">No recent activity.</p>';
                }

            } catch (innerErr) {
                console.warn("Partial data load failure", innerErr);
            }
            
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

    // Initialize map first, then load data
    initLeafletMap();
    loadDashboard();
</script>
@endpush
