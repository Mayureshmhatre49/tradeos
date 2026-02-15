<!DOCTYPE html>
<html class="light" lang="en">
<head>
    <meta charset="utf-8"/>
    <meta content="width=device-width, initial-scale=1.0" name="viewport"/>
    <title>TradeOS | Secure Login</title>
    <script src="https://cdn.tailwindcss.com?plugins=forms,container-queries"></script>
    <script src="https://cdn.jsdelivr.net/npm/axios/dist/axios.min.js"></script>
    <link href="https://fonts.googleapis.com/css2?family=Inter:wght@300;400;500;600;700&amp;display=swap" rel="stylesheet"/>
    <link href="https://fonts.googleapis.com/icon?family=Material+Icons|Material+Icons+Outlined|Material+Icons+Round" rel="stylesheet"/>
    <script id="tailwind-config">
        tailwind.config = {
            darkMode: "class",
            theme: {
                extend: {
                    colors: {
                        "primary": "#195de6",
                        "background-light": "#f6f6f8",
                        "background-dark": "#111621",
                    },
                    fontFamily: { "display": ["Inter", "sans-serif"] }
                },
            },
        }
    </script>
    <style>
        .login-bg {
            background: radial-gradient(circle at top right, rgba(25, 93, 230, 0.05), transparent),
                        radial-gradient(circle at bottom left, rgba(25, 93, 230, 0.05), transparent);
        }
    </style>
</head>
<body class="bg-background-light dark:bg-background-dark font-display min-h-screen flex flex-col items-center justify-center p-4 login-bg">
    
    <div class="w-full max-w-[440px]">
        <div class="mb-10 flex flex-col items-center">
            <div class="w-16 h-16 bg-primary rounded-2xl flex items-center justify-center shadow-xl shadow-primary/30 mb-4 transform -rotate-3 hover:rotate-0 transition-transform duration-300">
                <span class="material-icons text-white text-3xl">account_balance</span>
            </div>
            <h1 class="text-3xl font-extrabold text-slate-900 dark:text-white tracking-tight">Trade<span class="text-primary">OS</span></h1>
            <p class="text-slate-500 dark:text-slate-400 mt-2 font-medium">Enterprise Trade Management Platform</p>
        </div>
        
        <main class="bg-white dark:bg-slate-900 rounded-3xl shadow-2xl shadow-slate-200/50 dark:shadow-none p-8 md:p-10 border border-slate-200 dark:border-slate-800">
            <div class="mb-8">
                <h2 class="text-xl font-bold text-slate-900 dark:text-white">Secure Login</h2>
                <p class="text-xs text-slate-500 dark:text-slate-400 mt-1 uppercase tracking-widest font-bold">Authorized Personnel Only</p>
            </div>
            
            <form id="loginForm" class="space-y-6">
                <div class="space-y-2">
                    <label class="block text-[11px] font-bold text-slate-400 dark:text-slate-500 uppercase tracking-widest" for="email">Email Address</label>
                    <div class="relative group">
                        <span class="material-icons text-slate-400 text-sm absolute left-3 top-1/2 -translate-y-1/2 group-focus-within:text-primary transition-colors">mail</span>
                        <input class="block w-full pl-10 pr-3 py-3.5 border border-slate-200 dark:border-slate-700 rounded-xl bg-slate-50/50 dark:bg-slate-800 text-slate-900 dark:text-white text-sm focus:ring-2 focus:ring-primary/20 focus:border-primary outline-none transition-all placeholder:text-slate-300" 
                               id="email" name="email" type="email" placeholder="superadmin@tradeos.com" required value="superadmin@tradeos.com"/>
                    </div>
                </div>
                
                <div class="space-y-2">
                    <div class="flex justify-between items-center">
                        <label class="block text-[11px] font-bold text-slate-400 dark:text-slate-500 uppercase tracking-widest" for="password">Password</label>
                        <a href="#" class="text-[10px] font-bold text-primary hover:underline uppercase tracking-widest">Forgot?</a>
                    </div>
                    <div class="relative group">
                        <span class="material-icons text-slate-400 text-sm absolute left-3 top-1/2 -translate-y-1/2 group-focus-within:text-primary transition-colors">lock</span>
                        <input class="block w-full pl-10 pr-3 py-3.5 border border-slate-200 dark:border-slate-700 rounded-xl bg-slate-50/50 dark:bg-slate-800 text-slate-900 dark:text-white text-sm focus:ring-2 focus:ring-primary/20 focus:border-primary outline-none transition-all placeholder:text-slate-300" 
                               id="password" name="password" type="password" placeholder="••••••••" required value="password"/>
                    </div>
                </div>

                <div id="errorMessage" class="hidden px-4 py-2 bg-red-100 dark:bg-red-900/20 border border-red-200 dark:border-red-800 text-red-600 dark:text-red-400 text-[10px] font-bold uppercase rounded-lg text-center"></div>

                <button type="submit" class="w-full bg-primary text-white font-bold py-4 px-4 rounded-xl hover:bg-blue-700 transition-all shadow-xl shadow-primary/25 active:scale-[0.98] flex items-center justify-center gap-2">
                    <span>SIGN INTO DASHBOARD</span>
                    <span class="material-icons text-sm">arrow_forward</span>
                </button>
            </form>
            
            <div class="mt-8 pt-8 border-t border-slate-100 dark:border-slate-800 flex justify-between items-center">
                <span class="text-[10px] text-slate-400 font-bold uppercase tracking-widest">System Status</span>
                <span class="flex items-center gap-1.5 text-[10px] text-emerald-500 font-bold uppercase tracking-widest">
                    <div class="w-2 h-2 rounded-full bg-emerald-500 animate-pulse"></div> Operational
                </span>
            </div>
        </main>
        
        <p class="mt-8 text-center text-[10px] text-slate-400 font-bold uppercase tracking-[0.2em]">
            &copy; 2026 TradeOS Architecture • All Rights Reserved
        </p>
    </div>

    <script>
        document.getElementById('loginForm').addEventListener('submit', async (e) => {
            e.preventDefault();
            const email = document.getElementById('email').value;
            const password = document.getElementById('password').value;
            const errorDiv = document.getElementById('errorMessage');
            const submitBtn = e.target.querySelector('button[type="submit"]');
            
            errorDiv.classList.add('hidden');
            errorDiv.textContent = '';
            submitBtn.disabled = true;
            submitBtn.innerHTML = '<span class="material-icons animate-spin text-sm">refresh</span> <span>AUTHENTICATING...</span>';

            try {
                const response = await axios.post('/api/login', { email, password });
                const token = response.data.access_token;
                if (!token) throw new Error('No token received');
                
                localStorage.setItem('tradeos_token', token);
                window.location.href = '/dashboard';
            } catch (error) {
                console.error(error);
                errorDiv.textContent = error.response?.data?.message || 'Authentication failed. Check security credentials.';
                errorDiv.classList.remove('hidden');
                submitBtn.disabled = false;
                submitBtn.innerHTML = '<span>SIGN INTO DASHBOARD</span> <span class="material-icons text-sm">arrow_forward</span>';
            }
        });
        
        if (localStorage.getItem('tradeos_token')) {
            window.location.href = '/dashboard';
        }
    </script>
</body>
</html>
