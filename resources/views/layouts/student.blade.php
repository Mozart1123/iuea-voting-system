<!DOCTYPE html>
<html lang="en">
<head>
    <meta charset="UTF-8">
    <meta name="viewport" content="width=device-width, initial-scale=1.0">
    <title>@yield('title') - IUEA GuildVote</title>
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
            --iuea-primary-light: #b22222;
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

        .glass-card {
            background: rgba(255, 255, 255, 1);
            border: 1px solid rgba(0, 0, 0, 0.05);
            box-shadow: 0 4px 20px rgba(0, 0, 0, 0.05);
        }

        .active-nav {
            background: rgba(255, 255, 255, 0.15);
            border-left: 4px solid white;
        }

        .status-dot {
            width: 8px;
            height: 8px;
            border-radius: 50%;
            background-color: #10b981;
            position: relative;
        }

        .status-dot:after {
            content: '';
            position: absolute;
            width: 100%;
            height: 100%;
            border-radius: 50%;
            background-color: #10b981;
            animation: ping 1.5s cubic-bezier(0, 0, 0.2, 1) infinite;
        }

        @keyframes ping {
            75%, 100% { transform: scale(3); opacity: 0; }
        }

        .candidate-card:hover {
            transform: translateY(-5px);
            border-color: var(--iuea-primary);
            box-shadow: 0 10px 30px rgba(139, 0, 0, 0.1);
        }

        .primary-gradient-btn {
            background: linear-gradient(135deg, var(--iuea-primary) 0%, var(--iuea-primary-dark) 100%);
            color: white;
        }
    </style>
    @yield('styles')
</head>
<body class="main-content min-h-screen flex">
    <!-- Sidebar -->
    <aside class="w-72 sidebar-theme border-r border-black/5 hidden lg:flex flex-col shrink-0">
        <div class="p-8 text-white">
            <div class="flex items-center space-x-3 mb-12">
                <div class="w-20 h-20 rounded-2xl bg-white flex items-center justify-center shadow-lg overflow-hidden shrink-0 p-2">
                    <img src="https://iuea.ac.ug/sitepad-data/uploads/2024/07/IUEA-Logo-official-1.png" alt="IUEA Logo" class="w-full h-full object-contain">
                </div>
                <h1 class="text-xl font-black tracking-tighter text-white">IUEA <span class="opacity-60">VOTE</span></h1>
            </div>

            <nav class="space-y-2">
                <a href="{{ route('dashboard.index') }}" class="flex items-center space-x-4 px-6 py-4 rounded-xl {{ Request::routeIs('dashboard.index') ? 'active-nav' : 'text-white/70 hover:text-white hover:bg-white/10' }} transition-all">
                    <i class="fas fa-th-large"></i>
                    <span class="font-bold text-sm uppercase tracking-widest">Dashboard</span>
                </a>
                <a href="{{ route('dashboard.elections') }}" class="flex items-center space-x-4 px-6 py-4 rounded-xl {{ Request::routeIs('dashboard.elections') ? 'active-nav' : 'text-white/70 hover:text-white hover:bg-white/10' }} transition-all">
                    <i class="fas fa-box-archive"></i>
                    <span class="font-bold text-sm uppercase tracking-widest">Elections</span>
                </a>
                <a href="{{ route('dashboard.receipts') }}" class="flex items-center space-x-4 px-6 py-4 rounded-xl {{ Request::routeIs('dashboard.receipts') ? 'active-nav' : 'text-white/70 hover:text-white hover:bg-white/10' }} transition-all">
                    <i class="fas fa-receipt"></i>
                    <span class="font-bold text-sm uppercase tracking-widest">My Receipts</span>
                </a>
                @php
                    $nominationGlobal = \App\Models\SystemSetting::where('key', 'nomination_enabled')->first();
                    $isNominationActive = $nominationGlobal && $nominationGlobal->value === '1';
                @endphp
                @if($isNominationActive || \App\Models\Candidate::where('user_id', Auth::id())->exists())
                <a href="{{ route('dashboard.nomination') }}" class="flex items-center space-x-4 px-6 py-4 rounded-xl {{ Request::routeIs('dashboard.nomination') ? 'active-nav' : 'text-white/70 hover:text-white hover:bg-white/10' }} transition-all">
                    <i class="fas fa-id-card"></i>
                    <span class="font-bold text-sm uppercase tracking-widest">Nomination</span>
                </a>
                @endif
                <a href="{{ route('dashboard.security') }}" class="flex items-center space-x-4 px-6 py-4 rounded-xl {{ Request::routeIs('dashboard.security') ? 'active-nav' : 'text-white/70 hover:text-white hover:bg-white/10' }} transition-all">
                    <i class="fas fa-user-shield"></i>
                    <span class="font-bold text-sm uppercase tracking-widest">Security</span>
                </a>
            </nav>
        </div>

        <div class="mt-auto p-8 border-t border-white/10">
            <div class="bg-white/10 rounded-2xl p-4 mb-6">
                <div class="flex items-center gap-3">
                    <img src="https://ui-avatars.com/api/?name={{ urlencode(Auth::user()->name) }}&background=fff&color=8b0000" class="w-10 h-10 rounded-full border border-white/20">
                    <div>
                        <p class="text-xs font-black uppercase text-white">{{ Auth::user()->name }}</p>
                        <p class="text-[10px] text-white/60">{{ Auth::user()->student_id ?? 'ADMIN' }}</p>
                    </div>
                </div>
            </div>
            <form method="POST" action="{{ route('logout') }}">
                @csrf
                <button type="submit" class="w-full flex items-center space-x-4 px-6 py-4 rounded-xl text-white/80 hover:bg-white/10 transition-all font-black text-xs uppercase tracking-widest text-left">
                    <i class="fas fa-sign-out-alt"></i>
                    <span>Logout</span>
                </button>
            </form>
        </div>
    </aside>

    <!-- Main Content -->
    <main class="flex-1 overflow-y-auto">
        <!-- Top Bar -->
        <header class="p-8 flex justify-between items-center bg-white border-b border-gray-100">
            <div>
                <h2 class="text-2xl font-black tracking-tight text-gray-900">@yield('page-title', 'Voter Terminal')</h2>
                <div class="flex items-center gap-2 mt-1">
                    <div class="status-dot"></div>
                    <span class="text-[10px] font-black uppercase tracking-widest text-gray-400">Live Election Environment</span>
                </div>
            </div>
            
            <div class="flex items-center gap-6">
                <div class="text-right hidden sm:block">
                    <p class="text-[10px] font-black uppercase text-gray-400">Current System Time</p>
                    <p class="text-sm font-bold italic text-gray-900" id="sys-time">00:00:00 AM</p>
                </div>
                <button class="w-12 h-12 rounded-xl border border-gray-100 flex items-center justify-center hover:bg-gray-50 transition-all text-gray-500">
                    <i class="fas fa-bell"></i>
                </button>
            </div>
        </header>

        <div class="p-8 bg-gray-50/50 min-h-[calc(110vh-100px)]">
            <!-- Alerts -->
            @if(session('success'))
                <div class="mb-8 p-6 rounded-[2rem] bg-green-50 border border-green-100 flex items-center gap-4 animate-fade-in">
                    <div class="w-10 h-10 rounded-full bg-green-500 text-white flex items-center justify-center shrink-0">
                        <i class="fas fa-check"></i>
                    </div>
                    <p class="text-sm font-black text-green-700 uppercase">{{ session('success') }}</p>
                </div>
            @endif

            @if(session('error'))
                <div class="mb-8 p-6 rounded-[2rem] bg-red-50 border border-red-100 flex items-center gap-4 animate-fade-in">
                    <div class="w-10 h-10 rounded-full bg-red-500 text-white flex items-center justify-center shrink-0">
                        <i class="fas fa-exclamation-triangle"></i>
                    </div>
                    <p class="text-sm font-black text-red-700 uppercase">{{ session('error') }}</p>
                </div>
            @endif

            @if($errors->any())
                <div class="mb-8 p-6 rounded-[2rem] bg-orange-50 border border-orange-100 flex flex-col gap-2 animate-fade-in">
                    @foreach($errors->all() as $error)
                        <div class="flex items-center gap-4">
                            <i class="fas fa-circle-exclamation text-orange-500"></i>
                            <p class="text-sm font-black text-orange-700 uppercase">{{ $error }}</p>
                        </div>
                    @endforeach
                </div>
            @endif

            @yield('content')
        </div>
    </main>

    <script>
        // Update time
        function updateTime() {
            const now = new Date();
            const timeStr = now.toLocaleTimeString('en-US', { 
                hour: '2-digit', 
                minute: '2-digit', 
                second: '2-digit',
                hour12: true 
            });
            const timeEl = document.getElementById('sys-time');
            if(timeEl) timeEl.textContent = timeStr;
        }
        setInterval(updateTime, 1000);
        updateTime();
    </script>
    @yield('scripts')
</body>
</html>
