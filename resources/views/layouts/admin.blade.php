<!DOCTYPE html>
<html lang="en">
<head>
    <meta charset="UTF-8">
    <meta name="viewport" content="width=device-width, initial-scale=1.0">
    <title>IUEA GuildVote – @yield('title', 'Admin Console')</title>
    <script src="https://cdn.tailwindcss.com"></script>
    <link rel="preconnect" href="https://fonts.googleapis.com">
    <link rel="preconnect" href="https://fonts.gstatic.com" crossorigin>
    <link href="https://fonts.googleapis.com/css2?family=Inter:wght@300;400;500;600;700;800&display=swap" rel="stylesheet">
    <link rel="stylesheet" href="https://cdnjs.cloudflare.com/ajax/libs/font-awesome/6.4.0/css/all.min.css">
    <script src="https://cdnjs.cloudflare.com/ajax/libs/gsap/3.12.2/gsap.min.js"></script>
    <style>
        :root {
            --primary: #8B0000;
            --primary-dark: #660000;
            --secondary: #1a1a1a;
        }
        * { font-family: 'Inter', sans-serif; }
        .sidebar { background: linear-gradient(135deg, #1a1a1a 0%, #330000 100%); }
        .stat-card { transition: all 0.3s cubic-bezier(0.4, 0, 0.2, 1); }
        .stat-card:hover { transform: translateY(-5px); }
        .active-link { background: rgba(139, 0, 0, 0.15); border-left: 4px solid var(--primary); }
    </style>
</head>
<body class="bg-[#f8f9fa] flex h-screen overflow-hidden">

    <!-- Sidebar -->
    <aside class="w-72 sidebar text-white flex flex-col flex-shrink-0">
        <div class="p-8 border-b border-white/5">
            <div class="flex items-center gap-3">
                <div class="w-12 h-12 bg-white rounded-xl flex items-center justify-center p-2">
                    <img src="https://iuea.ac.ug/sitepad-data/uploads/2024/07/IUEA-Logo-official-1.png" alt="IUEA Logo" class="w-full h-full object-contain">
                </div>
                <div>
                    <h1 class="font-black text-xl tracking-tighter">GUILD VOTE</h1>
                    <p class="text-[10px] text-white/50 uppercase tracking-widest font-bold">Admin Tier @if(auth()->user()->hasRole('super_admin')) I @else II @endif</p>
                </div>
            </div>
        </div>

        <nav class="flex-1 px-4 py-8 space-y-2 overflow-y-auto">
            <a href="{{ route('admin.index') }}" class="flex items-center gap-4 px-6 py-4 rounded-2xl transition-all {{ Request::routeIs('admin.index') ? 'active-link font-black text-white' : 'text-white/60 hover:text-white hover:bg-white/5' }}">
                <i class="fas fa-grid-2 text-lg"></i>
                <span class="text-sm uppercase tracking-widest">Overview</span>
            </a>
            
            <a href="{{ route('admin.feed') }}" class="flex items-center gap-4 px-6 py-4 rounded-2xl transition-all {{ Request::routeIs('admin.feed') ? 'active-link font-black text-white' : 'text-white/60 hover:text-white hover:bg-white/5' }}">
                <i class="fas fa-bolt text-lg"></i>
                <span class="text-sm uppercase tracking-widest">Live Monitor</span>
            </a>
            <a href="{{ route('admin.ballot.preview') }}" class="flex items-center gap-4 px-6 py-4 rounded-2xl transition-all {{ Request::routeIs('admin.ballot.preview') ? 'active-link font-black text-white' : 'text-white/60 hover:text-white hover:bg-white/5' }}">
                <i class="fas fa-eye text-lg"></i>
                <span class="text-sm uppercase tracking-widest">Ballot Design</span>
            </a>

            @if(auth()->user()->hasRole('super_admin'))
            <div class="pt-8 pb-4">
                <p class="px-6 text-[9px] font-black text-white/30 uppercase tracking-[0.3em]">Management Console</p>
            </div>
            <a href="{{ route('admin.manage.categories') }}" class="flex items-center gap-4 px-6 py-4 rounded-2xl transition-all {{ Request::routeIs('admin.manage.categories') ? 'active-link font-black text-white' : 'text-white/60 hover:text-white hover:bg-white/5' }}">
                <i class="fas fa-scroll-old text-lg"></i>
                <span class="text-sm uppercase tracking-widest">Categories</span>
            </a>
            <a href="{{ route('admin.manage.candidates') }}" class="flex items-center gap-4 px-6 py-4 rounded-2xl transition-all {{ Request::routeIs('admin.manage.candidates') ? 'active-link font-black text-white' : 'text-white/60 hover:text-white hover:bg-white/5' }}">
                <i class="fas fa-user-shield text-lg"></i>
                <span class="text-sm uppercase tracking-widest">Candidates</span>
            </a>
            <a href="{{ route('admin.manage.users') }}" class="flex items-center gap-4 px-6 py-4 rounded-2xl transition-all {{ Request::routeIs('admin.manage.users') ? 'active-link font-black text-white' : 'text-white/60 hover:text-white hover:bg-white/5' }}">
                <i class="fas fa-users-gear text-lg"></i>
                <span class="text-sm uppercase tracking-widest">Manage Admins</span>
            </a>
            <a href="{{ route('admin.manage.audit') }}" class="flex items-center gap-4 px-6 py-4 rounded-2xl transition-all {{ Request::routeIs('admin.manage.audit') ? 'active-link font-black text-white' : 'text-white/60 hover:text-white hover:bg-white/5' }}">
                <i class="fas fa-fingerprint text-lg"></i>
                <span class="text-sm uppercase tracking-widest">Audit Logs</span>
            </a>
            <a href="{{ route('admin.manage.settings') }}" class="flex items-center gap-4 px-6 py-4 rounded-2xl transition-all {{ Request::routeIs('admin.manage.settings') ? 'active-link font-black text-white' : 'text-white/60 hover:text-white hover:bg-white/5' }}">
                <i class="fas fa-gears text-lg"></i>
                <span class="text-sm uppercase tracking-widest">Settings</span>
            </a>
            <a href="{{ route('public.results') }}" target="_blank" class="flex items-center gap-4 px-6 py-4 rounded-2xl transition-all text-white/60 hover:text-white hover:bg-white/5">
                <i class="fas fa-display text-lg"></i>
                <span class="text-sm uppercase tracking-widest">Live Projection</span>
            </a>
            @endif
        </nav>

        <div class="p-8 border-t border-white/5 bg-black/20">
            <div class="flex items-center gap-4 mb-6">
                <div class="w-10 h-10 rounded-full bg-primary flex items-center justify-center text-sm font-black border-2 border-white/20">
                    {{ substr(auth()->user()->name, 0, 1) }}
                </div>
                <div>
                    <p class="text-xs font-black uppercase tracking-tight">{{ auth()->user()->name }}</p>
                    <p class="text-[10px] text-white/40">{{ auth()->user()->role->display_name }}</p>
                </div>
            </div>
            <form action="{{ route('logout') }}" method="POST">
                @csrf
                <button type="submit" class="w-full py-4 rounded-xl border-2 border-white/10 text-white/60 text-[10px] font-black uppercase tracking-widest hover:border-primary hover:text-white transition-all">
                    Secure Exit
                </button>
            </form>
        </div>
    </aside>

    <!-- Main Content -->
    <main class="flex-1 flex flex-col overflow-hidden">
        <header class="h-24 bg-white border-b border-gray-100 flex items-center justify-between px-12 z-10">
            <h2 class="text-2xl font-black text-gray-900 uppercase tracking-tight">@yield('header', 'Dashboard')</h2>
            <div class="flex items-center gap-6">
                <div class="flex items-center gap-2 px-4 py-2 bg-green-50 rounded-full border border-green-100">
                    <span class="w-2 h-2 bg-green-500 rounded-full animate-pulse"></span>
                    <span class="text-[10px] font-black text-green-700 uppercase tracking-widest">System Online</span>
                </div>
                <div class="w-px h-8 bg-gray-100"></div>
                <p class="text-[10px] font-black text-gray-400 uppercase tracking-widest">{{ now()->format('D, M d, Y') }}</p>
            </div>
        </header>

        <div class="flex-1 overflow-y-auto p-12 bg-[#f4f7f6]">
            @if(session('success'))
                <div class="mb-8 p-6 bg-green-50 border-l-8 border-green-500 rounded-2xl flex items-center gap-4 text-green-800 animate-slide-in">
                    <i class="fas fa-circle-check text-2xl"></i>
                    <p class="font-bold text-sm">{{ session('success') }}</p>
                </div>
            @endif

            @if(session('error'))
                <div class="mb-8 p-6 bg-red-50 border-l-8 border-primary rounded-2xl flex items-center gap-4 text-red-800 animate-slide-in">
                    <i class="fas fa-triangle-exclamation text-2xl"></i>
                    <p class="font-bold text-sm">{{ session('error') }}</p>
                </div>
            @endif

            @yield('content')
        </div>
    </main>

    <script>
        gsap.from("main", { opacity: 0, x: 20, duration: 1 });
        gsap.from(".sidebar", { x: -100, duration: 0.8, ease: "power4.out" });
    </script>
</body>
</html>
