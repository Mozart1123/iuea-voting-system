<!DOCTYPE html>
<html lang="en">
<head>
    <meta charset="UTF-8">
    <meta name="viewport" content="width=device-width, initial-scale=1.0">
    <title>@yield('title') - Admin Command Center - IUEA GuildVote</title>
    <script src="https://cdn.tailwindcss.com"></script>
    <link rel="preconnect" href="https://fonts.googleapis.com">
    <link rel="preconnect" href="https://fonts.gstatic.com" crossorigin>
    <link href="https://fonts.googleapis.com/css2?family=Inter:wght@300;400;500;600;700;800&display=swap" rel="stylesheet">
    <link rel="stylesheet" href="https://cdnjs.cloudflare.com/ajax/libs/font-awesome/6.4.0/css/all.min.css">
    <link rel="icon" type="image/png" href="https://iuea.ac.ug/sitepad-data/uploads/2024/07/IUEA-Logo-official-1.png">
    <style>
        :root {
            --iuea-primary: #8b0000;
            --iuea-primary-dark: #660000;
        }
        
        * {
            font-family: 'Inter', sans-serif;
        }

        .sidebar-theme {
            background-color: var(--iuea-primary);
            color: white;
        }

        .main-content {
            background-color: #ffffff;
            color: #111111;
        }

        .active-nav {
            background: rgba(255, 255, 255, 0.15);
            border-right: 4px solid white;
        }

        .pulse-red {
            box-shadow: 0 0 0 0 rgba(139, 0, 0, 0.7);
            animation: pulse-red 2s infinite;
        }

        @keyframes pulse-red {
            0% { transform: scale(0.95); box-shadow: 0 0 0 0 rgba(139, 0, 0, 0.7); }
            70% { transform: scale(1); box-shadow: 0 0 0 10px rgba(139, 0, 0, 0); }
            100% { transform: scale(0.95); box-shadow: 0 0 0 0 rgba(139, 0, 0, 0); }
        }

        .bg-gray-custom {
            background-color: #f8f9fa;
        }

        .nav-item {
            transition: all 0.3s ease;
        }

        .nav-item:hover {
            background: rgba(255, 255, 255, 0.05);
        }

        .primary-gradient-btn {
            background: linear-gradient(135deg, var(--iuea-primary) 0%, var(--iuea-primary-dark) 100%);
            color: white;
        }
    </style>
    @yield('styles')
</head>
<body class="main-content min-h-screen flex">
    <!-- Admin Sidebar -->
    <aside class="w-20 lg:w-72 sidebar-theme flex flex-col transition-all duration-300 overflow-hidden shrink-0">
        <div class="p-6 flex items-center justify-center lg:justify-start gap-4 mb-10 text-white">
            <div class="w-20 h-20 rounded-2xl bg-white flex items-center justify-center shadow-lg shrink-0 overflow-hidden p-2">
                <img src="https://iuea.ac.ug/sitepad-data/uploads/2024/07/IUEA-Logo-official-1.png" alt="IUEA Logo" class="w-full h-full object-contain">
            </div>
            <div class="hidden lg:block">
                <h1 class="text-lg font-black tracking-tighter uppercase">Admin <span class="opacity-60">Portal</span></h1>
                <p class="text-[8px] font-black tracking-[0.3em] text-white/70 italic mt-0.5 uppercase">Security Tier 1</p>
            </div>
        </div>

        <nav class="flex-1 px-4 space-y-8">
            <!-- Analytics Section -->
            <div class="space-y-2">
                <p class="px-4 text-[9px] font-black uppercase text-white/30 tracking-[0.3em] italic mb-4">Neural Analytics</p>
                <a href="{{ route('admin.index') }}" class="nav-item flex items-center justify-center lg:justify-start gap-4 px-6 py-4 rounded-2xl {{ Request::routeIs('admin.index') ? 'active-nav' : 'text-white/70 hover:bg-white/5' }} transition-all">
                    <i class="fas fa-chart-line w-5 text-center"></i>
                    <span class="hidden lg:block font-black text-[10px] uppercase tracking-widest">Live Pulse</span>
                </a>
            </div>

            <!-- Management Section -->
            <div class="space-y-2">
                <p class="px-4 text-[9px] font-black uppercase text-white/30 tracking-[0.3em] italic mb-4">Manifest Management</p>
                <a href="{{ route('admin.elections') }}" class="nav-item flex items-center justify-center lg:justify-start gap-4 px-6 py-4 rounded-2xl {{ Request::routeIs('admin.elections') ? 'active-nav' : 'text-white/70 hover:bg-white/5' }} transition-all">
                    <i class="fas fa-boxes-stacked w-5 text-center"></i>
                    <span class="hidden lg:block font-black text-[10px] uppercase tracking-widest">Elections</span>
                </a>
                <a href="{{ route('admin.candidates') }}" class="nav-item flex items-center justify-center lg:justify-start gap-4 px-6 py-4 rounded-2xl {{ Request::routeIs('admin.candidates') ? 'active-nav' : 'text-white/70 hover:bg-white/5' }} transition-all">
                    <i class="fas fa-id-card-clip w-5 text-center"></i>
                    <span class="hidden lg:block font-black text-[10px] uppercase tracking-widest">Vetted Entrants</span>
                </a>
            </div>

            <!-- Registry Section -->
            <div class="space-y-2">
                <p class="px-4 text-[9px] font-black uppercase text-white/30 tracking-[0.3em] italic mb-4">Human Registry</p>
                <a href="{{ route('admin.voters') }}" class="nav-item flex items-center justify-center lg:justify-start gap-4 px-6 py-4 rounded-2xl {{ Request::routeIs('admin.voters') ? 'active-nav' : 'text-white/70 hover:bg-white/5' }} transition-all">
                    <i class="fas fa-fingerprint w-5 text-center"></i>
                    <span class="hidden lg:block font-black text-[10px] uppercase tracking-widest">Voter Base</span>
                </a>
            </div>

            <!-- Configuration Section -->
            <div class="space-y-2">
                <p class="px-4 text-[9px] font-black uppercase text-white/30 tracking-[0.3em] italic mb-4">Kernel Parameters</p>
                <a href="{{ route('admin.settings') }}" class="nav-item flex items-center justify-center lg:justify-start gap-4 px-6 py-4 rounded-2xl {{ Request::routeIs('admin.settings') ? 'active-nav' : 'text-white/70 hover:bg-white/5' }} transition-all">
                    <i class="fas fa-microchip w-5 text-center"></i>
                    <span class="hidden lg:block font-black text-[10px] uppercase tracking-widest">System Config</span>
                </a>
            </div>
        </nav>

        <div class="p-6 mt-auto border-t border-white/10 text-white">
            <div class="hidden lg:block bg-white/10 rounded-2xl p-4 mb-6">
                <p class="text-[9px] font-black uppercase text-white mb-2 italic">Session Active</p>
                <p class="text-[10px] text-white/70 font-medium">Logged in as: {{ Auth::user()->name }}</p>
            </div>
            <form method="POST" action="{{ route('logout') }}">
                @csrf
                <button type="submit" class="w-full flex items-center justify-center lg:justify-start gap-4 px-6 py-4 text-white/80 hover:bg-white/10 transition-all font-black text-[10px] uppercase tracking-widest italic">
                    <i class="fas fa-power-off w-5 text-center"></i>
                    <span class="hidden lg:block">Terminate Session</span>
                </button>
            </form>
        </div>
    </aside>

    <!-- Main Content -->
    <main class="flex-1 overflow-y-auto px-6 lg:px-12 py-10 bg-gray-custom font-bold">
        <!-- Alerts -->
        @if(session('success'))
            <div class="mb-8 p-6 rounded-[2rem] bg-green-50 border border-green-100 flex items-center gap-4 animate-fade-in shadow-sm">
                <div class="w-10 h-10 rounded-full bg-green-500 text-white flex items-center justify-center shrink-0">
                    <i class="fas fa-check"></i>
                </div>
                <p class="text-sm font-black text-green-700 uppercase">{{ session('success') }}</p>
            </div>
        @endif

        @if(session('error'))
            <div class="mb-8 p-6 rounded-[2rem] bg-red-50 border border-red-100 flex items-center gap-4 animate-fade-in shadow-sm">
                <div class="w-10 h-10 rounded-full bg-red-500 text-white flex items-center justify-center shrink-0">
                    <i class="fas fa-exclamation-triangle"></i>
                </div>
                <p class="text-sm font-black text-red-700 uppercase">{{ session('error') }}</p>
            </div>
        @endif

        @if($errors->any())
            <div class="mb-8 p-6 rounded-[2rem] bg-orange-50 border border-orange-100 flex flex-col gap-2 animate-fade-in shadow-sm">
                @foreach($errors->all() as $error)
                    <div class="flex items-center gap-4">
                        <i class="fas fa-circle-exclamation text-orange-500"></i>
                        <p class="text-sm font-black text-orange-700 uppercase">{{ $error }}</p>
                    </div>
                @endforeach
            </div>
        @endif

        @yield('content')
    </main>

    @yield('scripts')
</body>
</html>
