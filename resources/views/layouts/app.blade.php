<!DOCTYPE html>
<html class="light" lang="en">
<head>
    <meta charset="utf-8"/>
    <meta content="width=device-width, initial-scale=1.0" name="viewport"/>
    <title>TradeOS | @yield('title', 'Dashboard')</title>
    <script src="https://cdn.tailwindcss.com?plugins=forms,container-queries"></script>
    <script src="https://cdn.jsdelivr.net/npm/axios/dist/axios.min.js"></script>
    <link href="https://fonts.googleapis.com/css2?family=Inter:wght@300;400;500;600;700&amp;display=swap" rel="stylesheet"/>
    <link href="https://fonts.googleapis.com/icon?family=Material+Icons|Material+Icons+Outlined|Material+Icons+Round" rel="stylesheet"/>
    <script>
        tailwind.config = {
            darkMode: "class",
            theme: {
                extend: {
                    colors: {
                        "primary": "#195de6",
                        "background-light": "#f6f6f8",
                        "background-dark": "#111621",
                        "surface-dark": "#1e293b",
                        "border-dark": "#334155",
                    },
                    fontFamily: { "display": ["Inter"] }
                },
            },
        }
    </script>
</head>
<body class="bg-background-light dark:bg-background-dark text-slate-900 dark:text-slate-100 min-h-screen font-display block">
    <!-- Sidebar -->
    <aside class="w-full md:w-64 bg-white dark:bg-surface-dark border-r border-slate-200 dark:border-border-dark flex flex-col fixed h-full z-50 transition-transform -translate-x-full md:translate-x-0" id="sidebar">
        <div class="p-6 flex items-center gap-3 border-b border-slate-100 dark:border-border-dark">
            <div class="w-8 h-8 bg-primary rounded flex items-center justify-center text-white shadow-lg shadow-primary/30">
                <span class="material-icons text-xl">account_balance</span>
            </div>
            <span class="text-xl font-bold tracking-tight">Trade<span class="text-primary">OS</span></span>
        </div>
        
        <nav class="flex-1 px-4 py-6 space-y-1 overflow-y-auto">
            <a href="/dashboard" class="flex items-center gap-3 px-3 py-2.5 rounded-lg transition-colors {{ request()->is('dashboard') ? 'bg-primary/10 text-primary font-medium' : 'text-slate-600 dark:text-slate-400 hover:bg-slate-50 dark:hover:bg-slate-800' }}">
                <span class="material-icons text-[20px]">dashboard</span>
                <span class="text-sm">Dashboard</span>
            </a>
            
            <div class="pt-4 pb-2 px-3 text-xs font-semibold text-slate-400 uppercase tracking-wider">Modules</div>
            
            <a href="/transactions" class="flex items-center gap-3 px-3 py-2.5 rounded-lg transition-colors {{ request()->is('transactions*') ? 'bg-primary/10 text-primary font-medium' : 'text-slate-600 dark:text-slate-400 hover:bg-slate-50 dark:hover:bg-slate-800' }}">
                <span class="material-icons text-[20px]">swap_vert</span>
                <span class="text-sm">Transactions</span>
            </a>
            
            <a href="/shipments" class="flex items-center gap-3 px-3 py-2.5 rounded-lg transition-colors {{ request()->is('shipments*') ? 'bg-primary/10 text-primary font-medium' : 'text-slate-600 dark:text-slate-400 hover:bg-slate-50 dark:hover:bg-slate-800' }}">
                <span class="material-icons text-[20px]">local_shipping</span>
                <span class="text-sm">Shipments</span>
            </a>
            
            <a href="/lcs" class="flex items-center gap-3 px-3 py-2.5 rounded-lg transition-colors {{ request()->is('lcs*') ? 'bg-primary/10 text-primary font-medium' : 'text-slate-600 dark:text-slate-400 hover:bg-slate-50 dark:hover:bg-slate-800' }}">
                <span class="material-icons text-[20px]">description</span>
                <span class="text-sm">Letters of Credit</span>
            </a>
            
            <a href="/counterparties" class="flex items-center gap-3 px-3 py-2.5 rounded-lg transition-colors {{ request()->is('counterparties*') ? 'bg-primary/10 text-primary font-medium' : 'text-slate-600 dark:text-slate-400 hover:bg-slate-50 dark:hover:bg-slate-800' }}">
                <span class="material-icons text-[20px]">groups</span>
                <span class="text-sm">Counterparties</span>
            </a>

            <a href="/compliance" class="flex items-center gap-3 px-3 py-2.5 rounded-lg transition-colors {{ request()->is('compliance*') ? 'bg-primary/10 text-primary font-medium' : 'text-slate-600 dark:text-slate-400 hover:bg-slate-50 dark:hover:bg-slate-800' }}">
                <span class="material-icons text-[20px]">gpp_good</span>
                <span class="text-sm">Compliance & Risk</span>
            </a>
        </nav>

        <div class="p-4 border-t border-slate-200 dark:border-border-dark">
            <button onclick="logout()" class="flex items-center gap-3 w-full px-3 py-2.5 text-slate-600 dark:text-slate-400 hover:bg-red-50 hover:text-red-600 dark:hover:bg-red-900/20 dark:hover:text-red-400 rounded-lg transition-colors">
                <span class="material-icons text-[20px]">logout</span>
                <span class="text-sm">Sign Out</span>
            </button>
        </div>
    </aside>

    <!-- Mobile Header -->
    <div class="md:hidden bg-white dark:bg-surface-dark border-b border-slate-200 dark:border-border-dark p-4 flex items-center justify-between sticky top-0 z-40">
        <div class="flex items-center gap-2">
            <div class="w-8 h-8 bg-primary rounded flex items-center justify-center text-white">
                <span class="material-icons text-lg">account_balance</span>
            </div>
            <span class="font-bold text-lg">TradeOS</span>
        </div>
        <button onclick="toggleSidebar()" class="p-2 text-slate-600 dark:text-slate-400 hover:bg-slate-100 dark:hover:bg-slate-800 rounded">
            <span class="material-icons">menu</span>
        </button>
    </div>

    <!-- Main Content -->
    <main class="md:ml-64 min-h-screen">
        @yield('content')
    </main>

    <div id="mobileSidebarBackdrop" onclick="toggleSidebar()" class="fixed inset-0 bg-slate-900/50 z-40 hidden md:hidden backdrop-blur-sm transition-opacity"></div>

    <script>
        const token = localStorage.getItem('tradeos_token');
        if (!token) window.location.href = '/login';
        
        // Global Axios Config
        window.api = axios.create({ 
            baseURL: '/api', 
            headers: { 
                'Authorization': `Bearer ${token}`,
                'Accept': 'application/json',
                'Content-Type': 'application/json'
            } 
        });

        // Global Error Handler
        window.api.interceptors.response.use(
            response => response,
            error => {
                if (error.response && error.response.status === 401) {
                    logout();
                }
                return Promise.reject(error);
            }
        );

        function logout() {
            localStorage.removeItem('tradeos_token');
            window.location.href = '/login';
        }

        function toggleSidebar() {
            const sidebar = document.getElementById('sidebar');
            const backdrop = document.getElementById('mobileSidebarBackdrop');
            
            if (sidebar.classList.contains('-translate-x-full')) {
                sidebar.classList.remove('-translate-x-full');
                backdrop.classList.remove('hidden');
            } else {
                sidebar.classList.add('-translate-x-full');
                backdrop.classList.add('hidden');
            }
        }
    </script>
    @stack('scripts')
</body>
</html>
