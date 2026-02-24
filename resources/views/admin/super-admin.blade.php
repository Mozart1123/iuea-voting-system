<!DOCTYPE html>
<html lang="fr">
<head>
    <meta charset="UTF-8">
    <meta name="viewport" content="width=device-width, initial-scale=1.0">
    <title>IUEA GuildVote • Super Admin</title>
    <meta name="csrf-token" content="{{ csrf_token() }}">
    <script src="https://cdn.tailwindcss.com"></script>
    <link rel="stylesheet" href="https://cdnjs.cloudflare.com/ajax/libs/font-awesome/6.4.0/css/all.min.css">
    <link rel="preconnect" href="https://fonts.googleapis.com">
    <link rel="preconnect" href="https://fonts.gstatic.com" crossorigin>
    <link href="https://fonts.googleapis.com/css2?family=Inter:ital,opsz,wght@0,14..32,100..900;1,14..32,100..900&display=swap" rel="stylesheet">
    <style>
        * { font-family: 'Inter', system-ui, sans-serif; }
        :root {
            --primary: #8B0000;
            --primary-dark: #660000;
            --primary-light: #A52A2A;
            --primary-soft: rgba(139, 0, 0, 0.05);
        }
        body { background: #f9fafc; }
        
        .sidebar-admin {
            background: linear-gradient(165deg, #660000 0%, #8B0000 100%);
        }
        .sidebar-link {
            transition: all 0.2s ease;
            color: rgba(255, 255, 255, 0.85);
            display: flex;
            align-items: center;
            gap: 0.75rem;
            padding: 0.75rem 1.25rem;
            margin: 0.25rem 0.75rem;
            border-radius: 1rem;
            font-weight: 500;
            cursor: pointer;
        }
        .sidebar-link i { width: 1.5rem; color: rgba(255,255,255,0.7); }
        .sidebar-link:hover { background: rgba(255,255,255,0.1); color: white; }
        .sidebar-link:hover i { color: white; }
        .sidebar-link.active { background: white; color: var(--primary); box-shadow: 0 8px 20px -8px rgba(0,0,0,0.3); }
        .sidebar-link.active i { color: var(--primary); }

        .stat-card {
            background: white; border-radius: 1.5rem; padding: 1.5rem;
            box-shadow: 0 8px 20px -8px rgba(0,0,0,0.08); border: 1px solid #eef2f6;
            transition: all 0.2s;
        }
        .stat-card:hover { transform: translateY(-2px); box-shadow: 0 12px 24px -12px rgba(139,0,0,0.2); border-color: var(--primary); }
        .page-content { display: none; }
        .page-content.active-page { display: block; }
        .badge-admin { background: rgba(139,0,0,0.1); color: var(--primary); padding: 0.25rem 0.75rem; border-radius: 999px; font-size: 0.75rem; font-weight: 600; }
        .table-admin { width: 100%; border-collapse: separate; border-spacing: 0 0.5rem; }
        .table-admin th { text-align: left; padding: 0.75rem 1rem; color: #6b7280; font-weight: 600; font-size: 0.75rem; text-transform: uppercase; letter-spacing: 0.05em; }
        .table-admin td { background: white; padding: 1rem; border-radius: 1rem; box-shadow: 0 2px 8px rgba(0,0,0,0.02); border: 1px solid #f0f4f8; }
        
        .toast {
            position: fixed; bottom: 2rem; right: 2rem; background: white; color: #1e293b;
            padding: 1rem 1.5rem; border-radius: 999px; box-shadow: 0 20px 30px -12px rgba(0,0,0,0.25);
            border-left: 5px solid var(--primary); font-weight: 500; display: flex; align-items: center;
            gap: 0.75rem; z-index: 50; opacity: 0; transform: translateY(1rem);
            transition: opacity 0.3s ease, transform 0.3s ease; pointer-events: none;
        }
        .toast.show { opacity: 1; transform: translateY(0); }
        .form-select, .form-input, .form-textarea { 
            width: 100%; 
            padding: 0.875rem 1.25rem;
            border: 1px solid #e2e8f0; 
            border-radius: 1rem; 
            background-color: #f8fafc;
            transition: all 0.2s ease;
            outline: none;
            font-size: 0.9375rem;
        }
    </style>
</head>
<body>

    <div class="flex h-screen bg-gray-50">
        <!-- Sidebar -->
        <aside id="sidebar" class="sidebar-admin fixed md:static inset-y-0 left-0 w-72 flex flex-col transition-transform duration-300 transform -translate-x-full md:translate-x-0 z-30 shadow-xl overflow-hidden shrink-0">
            <div class="p-6 border-b border-white/20">
                <div class="flex items-center gap-3">
                    <div class="w-16 h-14 bg-white rounded-lg flex items-center justify-center shadow-md overflow-hidden p-1">
                        <img src="https://iuea.ac.ug/sitepad-data/uploads/2024/07/IUEA-Logo-official-1.png" alt="IUEA Logo" class="w-full h-full object-contain">
                    </div>
                    <div>
                        <span class="font-bold text-lg text-white block leading-tight">IUEA</span>
                        <span class="font-black text-lg text-white block leading-tight">GuildVote</span>
                    </div>
                </div>
                <div class="mt-2 text-[10px] text-white/70 font-black uppercase tracking-widest flex items-center">
                    <i class="fas fa-shield-alt mr-1 text-yellow-400"></i> Super Admin Console
                </div>
            </div>

            <nav class="flex-1 overflow-y-auto px-4 py-6 space-y-1">
                <a data-page="dashboard" class="sidebar-link active">
                    <i class="fas fa-tachometer-alt"></i>
                    <span>Dashboard</span>
                </a>
                <a data-page="admins" class="sidebar-link">
                    <i class="fas fa-user-shield"></i>
                    <span>Manage Admins</span>
                    <span class="ml-auto bg-white/20 text-white text-[10px] px-2 py-0.5 rounded-full">{{ count($admins) }}</span>
                </a>
                <a data-page="elections" class="sidebar-link">
                    <i class="fas fa-check-double"></i>
                    <span>Elections</span>
                </a>
                <a data-page="candidates" class="sidebar-link">
                    <i class="fas fa-user-tie"></i>
                    <span>Candidates</span>
                </a>
                <a data-page="users" class="sidebar-link">
                    <i class="fas fa-users"></i>
                    <span>Students</span>
                    <span class="ml-auto bg-white/20 text-white text-[10px] px-2 py-0.5 rounded-full">{{ $stats['total_students'] > 1000 ? round($stats['total_students']/1000, 1).'k' : $stats['total_students'] }}</span>
                </a>
                <a href="{{ route('admin.winners') }}" class="sidebar-link">
                    <i class="fas fa-trophy"></i>
                    <span>Winners & Certificates</span>
                </a>
                <a data-page="logs" class="sidebar-link border-t border-white/10 pt-4 mt-4">
                    <i class="fas fa-clipboard-list"></i>
                    <span>Audit logs</span>
                </a>
                <a data-page="settings" class="sidebar-link">
                    <i class="fas fa-cog"></i>
                    <span>Settings</span>
                </a>
            </nav>

            <div class="p-4 border-t border-white/20">
                <div class="flex items-center gap-3 px-2 mb-4">
                    <div class="w-10 h-10 rounded-full bg-white/20 backdrop-blur-sm flex items-center justify-center text-white font-bold border-2 border-white overflow-hidden shadow-inner">
                        <img src="{{ Auth::user()->profile_photo_url }}" class="w-full h-full object-cover">
                    </div>
                    <div class="flex-1 min-w-0">
                        <p class="text-xs font-bold text-white truncate">{{ Auth::user()->name }}</p>
                        <p class="text-[10px] text-white/60 uppercase font-black">Super Admin</p>
                    </div>
                </div>
                <form action="{{ route('logout') }}" method="POST">
                    @csrf
                    <button type="submit" class="sidebar-link w-full">
                        <i class="fas fa-power-off"></i>
                        <span>Logout</span>
                    </button>
                </form>
            </div>
        </aside>

        <!-- Mobile Backdrops -->
        <div id="sidebarBackdrop" class="fixed inset-0 bg-gray-900/60 z-20 hidden md:hidden transition-opacity"></div>

        <!-- Main content -->
        <main class="flex-1 flex flex-col min-w-0 overflow-auto bg-gray-50">
            <!-- Header -->
            <header class="bg-white border-b border-gray-200 py-4 px-6 flex items-center justify-between sticky top-0 z-10">
                <div class="flex items-center">
                    <button id="mobileMenuBtn" class="md:hidden text-gray-600 mr-4 focus:outline-none">
                        <i class="fas fa-bars text-xl"></i>
                    </button>
                    <h1 id="pageTitle" class="text-lg font-bold text-gray-800">Dashboard</h1>
                </div>
                <div class="flex items-center gap-4">
                    <!-- Notifications Bell -->
                    <div class="relative" id="notificationDropdown">
                        <button onclick="toggleNotifications()" class="relative text-gray-500 hover:text-primary transition-colors p-2">
                            <i class="fas fa-bell text-xl"></i>
                            <span id="notificationBadge" class="absolute top-1 right-1 bg-primary text-white text-[10px] font-black rounded-full w-5 h-5 flex items-center justify-center border-2 border-white hidden">0</span>
                        </button>

                        <!-- Notification Menu -->
                        <div id="notificationMenu" class="absolute right-0 mt-3 w-80 bg-white rounded-2xl shadow-2xl border border-gray-100 hidden z-50 overflow-hidden">
                            <div class="p-4 border-b border-gray-50 flex items-center justify-between">
                                <h4 class="font-bold text-gray-900">System Alerts</h4>
                                <button onclick="markAllAsRead()" class="text-[10px] font-black text-primary uppercase tracking-widest hover:underline">Mark all read</button>
                            </div>
                            <div id="notificationList" class="max-h-[400px] overflow-y-auto">
                                <div class="p-8 text-center text-gray-400">
                                    <i class="fas fa-bell-slash text-3xl mb-3 block"></i>
                                    <p class="text-xs font-medium">No new notifications</p>
                                </div>
                            </div>
                            <div class="p-3 bg-gray-50 text-center border-t border-gray-100">
                                <span class="text-[10px] font-bold text-gray-400 uppercase tracking-widest">IUEA Console Monitoring</span>
                            </div>
                        </div>
                    </div>
                    <div class="flex items-center gap-3">
                        <span class="text-sm text-gray-600 hidden md:block">Super Admin</span>
                        <div class="w-9 h-9 rounded-full bg-gradient-to-br from-primary to-primary-dark flex items-center justify-center text-white font-bold">SA</div>
                    </div>
                </div>
            </header>

            <!-- Page content container -->
            <div class="flex-1 p-8">
                <!-- DASHBOARD -->
                <div id="page-dashboard" class="page-content active-page space-y-8">
                    <div class="grid grid-cols-1 md:grid-cols-2 lg:grid-cols-4 gap-6">
                        <div class="stat-card">
                            <div class="flex justify-between items-start">
                                <div>
                                    <p class="text-gray-500 text-sm">Enrolled Students</p>
                                    <p class="text-3xl font-bold text-gray-900 mt-2">{{ number_format($stats['total_students']) }}</p>
                                </div>
                                <div class="bg-primary/10 p-3 rounded-xl">
                                    <i class="fas fa-users text-primary text-xl"></i>
                                </div>
                            </div>
                        </div>
                        <div class="stat-card">
                            <div class="flex justify-between items-start">
                                <div>
                                    <p class="text-gray-500 text-sm">Total Votes Cast</p>
                                    <p class="text-3xl font-bold text-gray-900 mt-2">{{ number_format($stats['total_votes']) }}</p>
                                </div>
                                <div class="bg-primary/10 p-3 rounded-xl">
                                    <i class="fas fa-vote-yea text-primary text-xl"></i>
                                </div>
                            </div>
                        </div>
                        <div class="stat-card">
                            <div class="flex justify-between items-start">
                                <div>
                                    <p class="text-gray-500 text-sm">Active Elections</p>
                                    <p class="text-3xl font-bold text-gray-900 mt-2">{{ $stats['active_elections'] }}</p>
                                </div>
                                <div class="bg-primary/10 p-3 rounded-xl">
                                    <i class="fas fa-check-circle text-primary text-xl"></i>
                                </div>
                            </div>
                        </div>
                        <div class="stat-card">
                            <div class="flex justify-between items-start">
                                <div>
                                    <p class="text-gray-500 text-sm">Staff Admins</p>
                                    <p class="text-3xl font-bold text-gray-900 mt-2">{{ $stats['total_admins'] }}</p>
                                </div>
                                <div class="bg-primary/10 p-3 rounded-xl">
                                    <i class="fas fa-user-shield text-primary text-xl"></i>
                                </div>
                            </div>
                        </div>
                    </div>
                </div>

                <!-- ADMIN MANAGEMENT -->
                <div id="page-admins" class="page-content space-y-6">
                    <div class="flex items-center justify-between">
                        <h2 class="text-2xl font-bold text-gray-800">Administrators</h2>
                        <button onclick="openModal('addAdminModal')" class="bg-gray-900 text-white px-6 py-3 rounded-2xl text-xs font-black uppercase tracking-widest hover:bg-black hover:-translate-y-0.5 transition-all flex items-center gap-3 shadow-xl shadow-gray-200">
                            <div class="w-6 h-6 rounded-lg bg-white/10 flex items-center justify-center">
                                <i class="fas fa-plus text-[10px]"></i>
                            </div>
                            New Admin
                        </button>
                    </div>
                    <div class="bg-white rounded-2xl border border-gray-200 overflow-hidden shadow-sm">
                        <table class="table-admin">
                            <thead class="bg-gray-50 border-b border-gray-200">
                                <tr>
                                    <th>Name</th>
                                    <th>Email</th>
                                    <th>Role</th>
                                    <th>Actions</th>
                                </tr>
                            </thead>
                            <tbody>
                                @foreach($admins as $admin)
                                <tr>
                                    <td>
                                        <div class="flex items-center gap-3">
                                            <div class="w-8 h-8 rounded-full bg-primary/10 flex items-center justify-center text-primary font-bold text-xs overflow-hidden">
                                                <img src="{{ $admin->profile_photo_url }}" class="w-full h-full object-cover">
                                            </div>
                                            <span class="font-medium text-gray-900">{{ $admin->name }}</span>
                                        </div>
                                    </td>
                                    <td>{{ $admin->email }}</td>
                                    <td><span class="badge-admin">{{ strtoupper(str_replace('_', ' ', $admin->role)) }}</span></td>
                                    <td>
                                        <div class="flex items-center gap-3">
                                            <button onclick='openEditAdminModal(@json($admin))' class="text-blue-600 hover:bg-blue-50 w-8 h-8 rounded-lg flex items-center justify-center transition">
                                                <i class="fas fa-edit"></i>
                                            </button>
                                            @if($admin->id !== Auth::id())
                                            <form action="{{ route('admin.super-admin.admins.delete', $admin->id) }}" method="POST" onsubmit="return confirm('Delete this administrator? This action cannot be undone.')">
                                                @csrf
                                                @method('DELETE')
                                                <button type="submit" class="text-red-600 hover:bg-red-50 w-8 h-8 rounded-lg flex items-center justify-center transition">
                                                    <i class="fas fa-trash"></i>
                                                </button>
                                            </form>
                                            @endif
                                        </div>
                                    </td>
                                </tr>
                                @endforeach
                            </tbody>
                        </table>
                    </div>
                </div>

                <!-- ELECTION MANAGEMENT -->
                <div id="page-elections" class="page-content space-y-6">
                    <div class="flex items-center justify-between">
                        <h2 class="text-2xl font-bold text-gray-800">Elections</h2>
                    </div>
                    <div class="grid grid-cols-1 md:grid-cols-2 lg:grid-cols-3 gap-6">
                        @foreach($elections as $election)
                        <div class="bg-white rounded-2xl border border-gray-200 p-6 shadow-sm hover:border-primary transition">
                            <h3 class="font-bold text-gray-900 text-lg">{{ $election->name }}</h3>
                            <div class="flex items-center gap-2 mt-2">
                                <span class="w-2 h-2 rounded-full {{ $election->status === 'voting' ? 'bg-green-500' : 'bg-yellow-500' }}"></span>
                                <p class="text-sm font-semibold uppercase tracking-wider {{ $election->status === 'voting' ? 'text-green-600' : 'text-yellow-600' }}">
                                    {{ $election->status }}
                                </p>
                            </div>
                            <div class="mt-4 pt-4 border-t border-gray-100 flex justify-between text-sm">
                                <span class="text-gray-500"><i class="fas fa-users mr-1"></i> {{ $election->candidates_count }} Candidates</span>
                                <span class="text-primary font-bold"><i class="fas fa-vote-yea mr-1"></i> {{ $election->total_votes }} Votes</span>
                            </div>
                        </div>
                        @endforeach
                    </div>
                </div>

                <!-- CANDIDATE MANAGEMENT -->
                <div id="page-candidates" class="page-content space-y-6">
                    <h2 class="text-2xl font-bold text-gray-800">Candidates</h2>
                    <div class="bg-white rounded-2xl border border-gray-200 overflow-hidden shadow-sm">
                        <table class="table-admin">
                            <thead class="bg-gray-50 border-b border-gray-200">
                                <tr>
                                    <th>Candidate Name</th>
                                    <th>Election Category</th>
                                    <th>Original Votes</th>
                                    <th>Manual Adjustments</th>
                                    <th>Final Tally</th>
                                </tr>
                            </thead>
                            <tbody>
                                @foreach($candidates as $candidate)
                                <tr>
                                    <td class="font-bold text-gray-900">{{ $candidate->name }}</td>
                                    <td>{{ $candidate->category?->name ?? 'N/A' }}</td>
                                    <td>{{ $candidate->votes_count }}</td>
                                    <td class="text-primary font-bold">+{{ $candidate->manual_votes }}</td>
                                    <td><span class="bg-primary text-white px-3 py-1 rounded-lg font-bold text-sm">{{ $candidate->total_votes }}</span></td>
                                </tr>
                                @endforeach
                            </tbody>
                        </table>
                    </div>
                </div>

                <!-- STUDENT MANAGEMENT -->
                <div id="page-users" class="page-content space-y-6">
                    <h2 class="text-2xl font-bold text-gray-800">Registered Students</h2>
                    <div class="bg-white rounded-2xl border border-gray-200 overflow-hidden shadow-sm">
                        <table class="table-admin">
                            <thead class="bg-gray-50 border-b border-gray-200">
                                <tr>
                                    <th>Student ID</th>
                                    <th>Full Name</th>
                                    <th>Email Address</th>
                                    <th>Status</th>
                                </tr>
                            </thead>
                            <tbody>
                                @foreach($students as $student)
                                <tr>
                                    <td class="font-mono text-xs font-bold">{{ $student->student_id }}</td>
                                    <td>{{ $student->name }}</td>
                                    <td>{{ $student->email }}</td>
                                    <td>
                                        @if($student->email_verified_at)
                                            <span class="flex items-center gap-1.5 text-xs font-bold text-green-600 bg-green-50 px-3 py-1.5 rounded-full border border-green-100">
                                                <i class="fas fa-check-circle"></i> VERIFIED
                                            </span>
                                        @else
                                            <span class="flex items-center gap-1.5 text-xs font-bold text-orange-600 bg-orange-50 px-3 py-1.5 rounded-full border border-orange-100">
                                                <i class="fas fa-clock"></i> PENDING
                                            </span>
                                        @endif
                                    </td>
                                </tr>
                                @endforeach
                            </tbody>
                        </table>
                    </div>
                </div>


                <!-- AUDIT LOGS -->
                <div id="page-logs" class="page-content space-y-6">
                    <h2 class="text-2xl font-bold text-gray-800">Security & Audit logs</h2>
                    <div class="bg-white rounded-2xl border border-gray-200 overflow-hidden shadow-sm">
                        <div class="p-6 space-y-4">
                            @foreach($logs as $log)
                            <div class="flex items-center justify-between p-4 bg-gray-50 rounded-xl border-l-4 {{ str_contains($log->action, 'adjustment') ? 'border-primary' : 'border-blue-500' }}">
                                <div class="flex-1">
                                    <p class="font-bold text-gray-900 flex items-center gap-2">
                                        {{ strtoupper(str_replace('_', ' ', $log->action)) }}
                                        @if(str_contains($log->action, 'adjustment'))
                                        <span class="bg-primary/10 text-primary text-[10px] font-black px-2 py-0.5 rounded tracking-tighter">VOTE_MOD</span>
                                        @endif
                                    </p>
                                    <p class="text-xs text-gray-500 mt-1">
                                        {{ $log->description }} • 
                                        <span class="font-bold text-gray-700">By {{ $log->user?->name ?? 'System' }}</span>
                                    </p>
                                </div>
                                <div class="text-right">
                                    <span class="text-[10px] font-black text-gray-400 block">{{ $log->created_at->format('d/m H:i:s') }}</span>
                                    <span class="text-xs font-bold text-gray-600">{{ $log->ip_address }}</span>
                                </div>
                            </div>
                            @endforeach
                        </div>
                    </div>
                </div>

                <!-- SETTINGS -->
                <div id="page-settings" class="page-content space-y-6">
                    <h2 class="text-2xl font-bold text-gray-800">System Configuration</h2>
                    <div class="bg-white rounded-2xl border border-gray-200 p-8 shadow-sm">
                        <div class="max-w-md mx-auto text-center">
                            <div class="w-20 h-20 bg-gray-100 rounded-full flex items-center justify-center mx-auto mb-6 text-gray-400">
                                <i class="fas fa-lock text-3xl"></i>
                            </div>
                            <h3 class="text-xl font-bold text-gray-900 mb-2">Security Settings</h3>
                            <p class="text-gray-500 mb-6 italic">Advanced system settings are currently locked.</p>
                        </div>
                    </div>
                </div>
            </div>
        </main>
    </div>

    <!-- ADMIN MANAGEMENT MODALS -->
    <div id="addAdminModal" class="fixed inset-0 bg-black/60 backdrop-blur-sm z-50 flex items-center justify-center p-4 hidden">
        <div class="bg-white rounded-3xl w-full max-w-md p-8 shadow-2xl transform transition-all">
            <div class="flex items-center gap-4 mb-6">
                <div class="w-12 h-12 rounded-2xl bg-primary/10 flex items-center justify-center text-primary text-xl">
                    <i class="fas fa-user-plus"></i>
                </div>
                <div>
                    <h3 class="text-2xl font-black text-gray-900 leading-none">New Admin</h3>
                    <p class="text-xs text-gray-500 mt-1 font-medium italic">Highly privileged access</p>
                </div>
                <button onclick="closeModal('addAdminModal')" class="ml-auto w-8 h-8 rounded-full hover:bg-gray-100 flex items-center justify-center text-gray-400 transition">
                    <i class="fas fa-times"></i>
                </button>
            </div>

            <form action="{{ route('admin.super-admin.admins.store') }}" method="POST" enctype="multipart/form-data" class="space-y-4">
                @csrf
                <div>
                    <label class="block text-[10px] font-black text-gray-400 uppercase tracking-widest mb-1.5 ml-1">Full Name</label>
                    <div class="relative">
                        <i class="fas fa-user absolute left-4 top-1/2 -translate-y-1/2 text-gray-300"></i>
                        <input type="text" name="name" class="form-input pl-11" placeholder="e.g. John Smith" required>
                    </div>
                </div>
                <div>
                    <label class="block text-[10px] font-black text-gray-400 uppercase tracking-widest mb-1.5 ml-1">Email Address</label>
                    <div class="relative">
                        <i class="fas fa-envelope absolute left-4 top-1/2 -translate-y-1/2 text-gray-300"></i>
                        <input type="email" name="email" class="form-input pl-11" placeholder="admin@iuea.ac.ug" required>
                    </div>
                </div>
                <div>
                    <label class="block text-[10px] font-black text-gray-400 uppercase tracking-widest mb-1.5 ml-1">Password</label>
                    <div class="relative">
                        <i class="fas fa-lock absolute left-4 top-1/2 -translate-y-1/2 text-gray-300"></i>
                        <input type="password" id="adminPassword" name="password" class="form-input px-11" placeholder="••••••••" required>
                        <button type="button" onclick="togglePassword('adminPassword')" class="absolute right-12 top-1/2 -translate-y-1/2 text-gray-400 hover:text-primary transition px-2">
                            <i class="fas fa-eye"></i>
                        </button>
                        <button type="button" onclick="generatePassword('adminPassword')" class="absolute right-4 top-1/2 -translate-y-1/2 text-gray-400 hover:text-primary transition px-2" title="Generate">
                            <i class="fas fa-dice"></i>
                        </button>
                    </div>
                    <p class="text-[10px] text-gray-400 mt-1 ml-1">Must be at least 8 characters.</p>
                </div>
                <div>
                    <label class="block text-[10px] font-black text-gray-400 uppercase tracking-widest mb-1.5 ml-1">Authority Level</label>
                    <div class="relative">
                        <i class="fas fa-shield-alt absolute left-4 top-1/2 -translate-y-1/2 text-gray-300"></i>
                        <select name="role" class="form-select pl-11" required>
                            <option value="admin">Standard Administrator</option>
                            <option value="super_admin">Super Administrator</option>
                        </select>
                    </div>
                </div>
                <div>
                    <label class="block text-[10px] font-black text-gray-400 uppercase tracking-widest mb-1.5 ml-1">Profile Photo (Optional)</label>
                    <div class="relative">
                        <i class="fas fa-image absolute left-4 top-1/2 -translate-y-1/2 text-gray-300"></i>
                        <input type="file" name="profile_photo" class="form-input pl-11 py-3" accept="image/*">
                    </div>
                </div>
                <div class="flex gap-4 pt-6">
                    <button type="button" onclick="closeModal('addAdminModal')" class="flex-1 px-4 py-4 text-gray-400 font-bold text-sm tracking-tight">Cancel</button>
                    <button type="submit" class="flex-[2] bg-gray-900 text-white font-black rounded-2xl shadow-xl shadow-gray-200 hover:bg-black hover:-translate-y-0.5 transition-all">
                        <i class="fas fa-plus-circle mr-2"></i> CREATE ACCOUNT
                    </button>
                </div>
            </form>
        </div>
    </div>

    <!-- EDIT ADMIN MODAL -->
    <div id="editAdminModal" class="fixed inset-0 bg-black/60 backdrop-blur-sm z-50 flex items-center justify-center p-4 hidden">
        <div class="bg-white rounded-3xl w-full max-w-md p-8 shadow-2xl">
            <div class="flex items-center gap-4 mb-6">
                <div class="w-12 h-12 rounded-2xl bg-blue-50 flex items-center justify-center text-blue-600 text-xl">
                    <i class="fas fa-user-edit"></i>
                </div>
                <div>
                    <h3 class="text-2xl font-black text-gray-900 leading-none">Edit Admin</h3>
                    <p class="text-xs text-gray-500 mt-1 font-medium italic">Update access privileges</p>
                </div>
                <button onclick="closeModal('editAdminModal')" class="ml-auto w-8 h-8 rounded-full hover:bg-gray-100 flex items-center justify-center text-gray-400 transition">
                    <i class="fas fa-times"></i>
                </button>
            </div>

            <form id="editAdminForm" method="POST" enctype="multipart/form-data" class="space-y-4">
                @csrf
                @method('PUT')
                <input type="hidden" id="edit_admin_id" name="admin_id">
                <div>
                    <label class="block text-[10px] font-black text-gray-400 uppercase tracking-widest mb-1.5 ml-1">Full Name</label>
                    <div class="relative">
                        <i class="fas fa-user absolute left-4 top-1/2 -translate-y-1/2 text-gray-300"></i>
                        <input type="text" id="edit_name" name="name" class="form-input pl-11" required>
                    </div>
                </div>
                <div>
                    <label class="block text-[10px] font-black text-gray-400 uppercase tracking-widest mb-1.5 ml-1">Email Address</label>
                    <div class="relative">
                        <i class="fas fa-envelope absolute left-4 top-1/2 -translate-y-1/2 text-gray-300"></i>
                        <input type="email" id="edit_email" name="email" class="form-input pl-11" required>
                    </div>
                </div>
                <div>
                    <label class="block text-[10px] font-black text-gray-400 uppercase tracking-widest mb-1.5 ml-1">New Password (Optional)</label>
                    <div class="relative">
                        <i class="fas fa-key absolute left-4 top-1/2 -translate-y-1/2 text-gray-300"></i>
                        <input type="password" id="editPassword" name="password" class="form-input px-11" placeholder="Leave empty to keep current">
                        <button type="button" onclick="togglePassword('editPassword')" class="absolute right-4 top-1/2 -translate-y-1/2 text-gray-400 hover:text-primary transition px-2">
                            <i class="fas fa-eye"></i>
                        </button>
                    </div>
                </div>
                <div>
                    <label class="block text-[10px] font-black text-gray-400 uppercase tracking-widest mb-1.5 ml-1">Authority Level</label>
                    <div class="relative">
                        <i class="fas fa-shield-alt absolute left-4 top-1/2 -translate-y-1/2 text-gray-300"></i>
                        <select id="edit_role" name="role" class="form-select pl-11" required>
                            <option value="admin">Standard Administrator</option>
                            <option value="super_admin">Super Administrator</option>
                        </select>
                    </div>
                </div>
                <div>
                    <label class="block text-[10px] font-black text-gray-400 uppercase tracking-widest mb-1.5 ml-1">Profile Photo (Optional)</label>
                    <div class="relative">
                        <i class="fas fa-image absolute left-4 top-1/2 -translate-y-1/2 text-gray-300"></i>
                        <input type="file" name="profile_photo" class="form-input pl-11 py-3" accept="image/*">
                    </div>
                </div>
                <div class="flex gap-4 pt-6">
                    <button type="button" onclick="closeModal('editAdminModal')" class="flex-1 px-4 py-4 text-gray-400 font-bold text-sm tracking-tight">Cancel</button>
                    <button type="submit" class="flex-[2] bg-gray-900 text-white font-black rounded-2xl shadow-xl hover:bg-black transition-all">
                        UPDATE ACCOUNT
                    </button>
                </div>
            </form>
        </div>
    </div>

    <div id="toast" class="toast">
        <i class="fas fa-check-circle text-primary"></i>
        <span id="toastMessage">Action réussie</span>
    </div>

    <script>
        (function() {
            window.openModal = function(id) { document.getElementById(id).classList.remove('hidden'); }
        window.closeModal = function(id) { document.getElementById(id).classList.add('hidden'); }

        window.togglePassword = function(id) {
            const el = document.getElementById(id);
            el.type = el.type === 'password' ? 'text' : 'password';
        }

        window.generatePassword = function(id) {
            const chars = "abcdefghijklmnopqrstuvwxyzABCDEFGHIJKLMNOPQRSTUVWXYZ0123456789!@#$%^&*()_+";
            let pass = "";
            for(let i=0; i<12; i++) pass += chars.charAt(Math.floor(Math.random() * chars.length));
            const el = document.getElementById(id);
            el.type = 'text';
            el.value = pass;
        }

        window.openEditAdminModal = function(admin) {
            document.getElementById('editAdminForm').action = `/admin/super-admin/admins/${admin.id}`;
            document.getElementById('edit_name').value = admin.name;
            document.getElementById('edit_email').value = admin.email;
            document.getElementById('edit_role').value = admin.role;
            openModal('editAdminModal');
        }

        function showToast(msg) {
                const toast = document.getElementById('toast');
                document.getElementById('toastMessage').textContent = msg;
                toast.classList.add('show');
                setTimeout(() => toast.classList.remove('show'), 3000);
            }

            // Mobile sidebar logic
            const sidebar = document.getElementById('sidebar');
            const backdrop = document.getElementById('sidebarBackdrop');
            const mobileBtn = document.getElementById('mobileMenuBtn');

            function openSidebar() {
                sidebar.classList.remove('-translate-x-full');
                backdrop.classList.remove('hidden');
            }

            function closeSidebar() {
                sidebar.classList.add('-translate-x-full');
                backdrop.classList.add('hidden');
            }

            if (mobileBtn) mobileBtn.addEventListener('click', openSidebar);
            if (backdrop) backdrop.addEventListener('click', closeSidebar);


            // Navigation
            const pages = document.querySelectorAll('.page-content');
            const links = document.querySelectorAll('.sidebar-link[data-page]');
            const title = document.getElementById('pageTitle');

            function showPage(pageId) {
                const targetLink = document.querySelector(`.sidebar-link[data-page="${pageId}"]`);
                if (!targetLink) return;

                pages.forEach(p => p.classList.remove('active-page'));
                const targetPage = document.getElementById(`page-${pageId}`);
                if (targetPage) targetPage.classList.add('active-page');

                links.forEach(l => l.classList.remove('active'));
                targetLink.classList.add('active');

                if (title) title.textContent = targetLink.querySelector('span').textContent;
            }

            links.forEach(link => {
                link.addEventListener('click', () => {
                    showPage(link.dataset.page);
                    if (window.innerWidth < 768) closeSidebar();
                });
            });

            // Handle URL hashes for direct navigation
            window.addEventListener('load', () => {
                const hash = window.location.hash.replace('#', '');
                if (hash) {
                    showPage(hash);
                }
            });

            @if(session('success')) showToast("{{ session('success') }}"); @endif
            @if(session('error')) showToast("{{ session('error') }}", 'error'); @endif

            // LIVE NOTIFICATIONS
            window.toggleNotifications = function() {
                const menu = document.getElementById('notificationMenu');
                menu.classList.toggle('hidden');
                if(!menu.classList.contains('hidden')) {
                    fetchNotifications();
                }
            }

            window.fetchNotifications = async function() {
                try {
                    const res = await fetch('/admin/notifications');
                    const data = await res.json();
                    const badge = document.getElementById('notificationBadge');
                    const list = document.getElementById('notificationList');
                    
                    if(data.unread_count > 0) {
                        badge.textContent = data.unread_count;
                        badge.classList.remove('hidden');
                    } else {
                        badge.classList.add('hidden');
                    }

                    if(data.notifications.length > 0) {
                        list.innerHTML = data.notifications.map(n => `
                            <div class="p-4 border-b border-gray-50 hover:bg-gray-50 transition cursor-default ${!n.read_at ? 'bg-primary/5' : ''}">
                                <div class="flex gap-3">
                                    <div class="w-10 h-10 rounded-xl bg-white shadow-sm flex items-center justify-center text-primary border border-gray-100 flex-shrink-0">
                                        <i class="${n.data.icon || 'fas fa-info-circle'}"></i>
                                    </div>
                                    <div class="flex-1">
                                        <p class="text-sm font-bold text-gray-900">${n.data.title}</p>
                                        <p class="text-[11px] text-gray-500 leading-tight mt-0.5">${n.data.message}</p>
                                        <span class="text-[9px] font-bold text-gray-400 mt-2 block">${new Date(n.created_at).toLocaleString('en-US')}</span>
                                    </div>
                                </div>
                            </div>
                        `).join('');
                    }
                } catch(e) { console.error(e); }
            }

            window.markAllAsRead = async function() {
                try {
                    const res = await fetch('/admin/notifications/read', {
                        method: 'POST',
                        headers: { 'X-CSRF-TOKEN': document.querySelector('meta[name="csrf-token"]').content }
                    });
                    const data = await res.json();
                    if(data.success) {
                        document.getElementById('notificationBadge').classList.add('hidden');
                        fetchNotifications();
                    }
                } catch(e) { console.error(e); }
            }

            // Close notification menu on click outside
            document.addEventListener('click', (e) => {
                if(!document.getElementById('notificationDropdown').contains(e.target)) {
                    document.getElementById('notificationMenu').classList.add('hidden');
                }
            });

            // Initial load and periodic refresh
            fetchNotifications();
            setInterval(fetchNotifications, 30000);
        })();
    </script>
</body>
</html>
