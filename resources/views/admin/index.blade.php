<!DOCTYPE html>
<html lang="en">
<head>
    <meta charset="UTF-8">
    <meta name="viewport" content="width=device-width, initial-scale=1.0">
    <title>IUEA GuildVote – Admin Console</title>
    <script src="https://cdn.tailwindcss.com"></script>
    <link rel="preconnect" href="https://fonts.googleapis.com">
    <link rel="preconnect" href="https://fonts.gstatic.com" crossorigin>
    <link href="https://fonts.googleapis.com/css2?family=Inter:wght@300;400;500;600;700;800&display=swap" rel="stylesheet">
    <link rel="stylesheet" href="https://cdnjs.cloudflare.com/ajax/libs/font-awesome/6.4.0/css/all.min.css">
    <style>
        :root {
            --primary: #8B0000;
            --primary-dark: #660000;
            --primary-light: #A52A2A;
        }
        * {
            font-family: 'Inter', sans-serif;
        }
        body {
            background-color: #f9fafb;
        }
        .text-primary { color: var(--primary); }
        .bg-primary { background-color: var(--primary); }
        .bg-primary-dark { background-color: var(--primary-dark); }
        .border-primary { border-color: var(--primary); }
        .hover\:bg-primary:hover { background-color: var(--primary); }
        .hover\:bg-primary-dark:hover { background-color: var(--primary-dark); }
        .hover\:text-primary:hover { color: var(--primary); }
        
        /* Admin Sidebar – Dark Red Gradient */
        .sidebar-admin {
            background: linear-gradient(165deg, #660000 0%, #8B0000 100%);
        }
        .sidebar-link {
            transition: all 0.2s ease;
            cursor: pointer;
            color: rgba(255, 255, 255, 0.85);
        }
        .sidebar-link:hover {
            background-color: rgba(255, 255, 255, 0.12);
            color: white;
        }
        .sidebar-link.active {
            background-color: white;
            color: var(--primary);
        }
        .sidebar-link.active i {
            color: var(--primary);
        }
        .sidebar-link i {
            color: rgba(255, 255, 255, 0.9);
        }
        .page-content {
            display: none;
        }
        .page-content.active-page {
            display: block;
        }
        .stat-card {
            transition: all 0.2s ease;
        }
        .stat-card:hover {
            transform: translateY(-2px);
            box-shadow: 0 12px 20px -8px rgba(178,34,34,0.15);
        }
    </style>
</head>
<body class="antialiased">

    <div class="flex h-screen bg-gray-50">
        
        <!-- ========== ADMIN SIDEBAR – DARK RED GRADIENT ========== -->
        <aside id="sidebar" class="fixed md:static inset-y-0 left-0 w-72 sidebar-admin flex flex-col transition-transform duration-300 transform -translate-x-full md:translate-x-0 z-30 shadow-xl">
            <!-- header with logo -->
            <div class="p-6 border-b border-white/20">
                <a href="{{ url('/') }}" class="flex items-center space-x-3">
                    <div class="w-20 h-16 bg-white rounded-lg flex items-center justify-center shadow-md overflow-hidden p-1">
                        <img src="https://iuea.ac.ug/sitepad-data/uploads/2024/07/IUEA-Logo-official-1.png" alt="IUEA Logo" class="w-full h-full object-contain">
                    </div>
                    <span class="font-bold text-xl tracking-tight text-white">IUEA <span class="font-extrabold">GuildVote</span></span>
                </a>
                <div class="mt-2 text-xs text-white/70 font-medium flex items-center">
                    <i class="fas fa-shield-alt mr-1"></i> Admin Console
                </div>
            </div>
            
            <!-- navigation links -->
            <nav class="flex-1 px-4 py-6 space-y-1 overflow-y-auto">
                <a data-page="dashboard" class="sidebar-link active flex items-center px-4 py-3 text-sm font-medium rounded-xl">
                    <i class="fas fa-tachometer-alt w-6"></i>
                    <span class="ml-3">Dashboard</span>
                </a>
                <a data-page="elections" class="sidebar-link flex items-center px-4 py-3 text-sm font-medium rounded-xl">
                    <i class="fas fa-check-double w-6"></i>
                    <span class="ml-3">Elections</span>
                    <span class="ml-auto bg-white/20 text-white text-xs px-2 py-1 rounded-full">{{ $activeElections }} active</span>
                </a>
                <a data-page="candidates" class="sidebar-link flex items-center px-4 py-3 text-sm font-medium rounded-xl">
                    <i class="fas fa-user-tie w-6"></i>
                    <span class="ml-3">Candidates</span>
                    <span class="ml-auto bg-white/20 text-white text-xs px-2 py-1 rounded-full">{{ $candidates->count() }}</span>
                </a>
                <a data-page="voters" class="sidebar-link flex items-center px-4 py-3 text-sm font-medium rounded-xl">
                    <i class="fas fa-users w-6"></i>
                    <span class="ml-3">Voters</span>
                    <span class="ml-auto bg-white/20 text-white text-xs px-2 py-1 rounded-full">{{ number_format($totalVoters/1000, 1) }}k</span>
                </a>
                <a data-page="audit" class="sidebar-link flex items-center px-4 py-3 text-sm font-medium rounded-xl">
                    <i class="fas fa-clipboard-list w-6"></i>
                    <span class="ml-3">Audit Logs</span>
                </a>
                <a data-page="reports" class="sidebar-link flex items-center px-4 py-3 text-sm font-medium rounded-xl">
                    <i class="fas fa-chart-pie w-6"></i>
                    <span class="ml-3">Results & Reports</span>
                </a>
                <a data-page="settings" class="sidebar-link flex items-center px-4 py-3 text-sm font-medium rounded-xl">
                    <i class="fas fa-cog w-6"></i>
                    <span class="ml-3">Settings</span>
                </a>
            </nav>
            
            <!-- admin user & logout -->
            <div class="p-4 border-t border-white/20">
                <div class="flex items-center mb-4 px-2">
                    <div class="w-10 h-10 rounded-full bg-white/30 backdrop-blur-sm flex items-center justify-center text-white font-bold border-2 border-white">
                        EC
                    </div>
                    <div class="ml-3">
                        <p class="text-sm font-semibold text-white">Election Commission</p>
                        <p class="text-xs text-white/80">{{ Auth::user()->email }}</p>
                    </div>
                </div>
                <form action="{{ route('logout') }}" method="POST" id="logoutForm">
                    @csrf
                    <a onclick="document.getElementById('logoutForm').submit()" class="sidebar-link flex items-center px-4 py-3 text-sm font-medium rounded-xl cursor-pointer">
                        <i class="fas fa-sign-out-alt w-6"></i>
                        <span class="ml-3">Logout</span>
                    </a>
                </form>
            </div>
        </aside>

        <!-- Mobile sidebar backdrop -->
        <div id="sidebarBackdrop" class="fixed inset-0 bg-gray-900/60 z-20 hidden md:hidden transition-opacity"></div>

        <!-- ========== MAIN CONTENT ========== -->
        <main class="flex-1 flex flex-col min-w-0 overflow-auto bg-gray-50">
            
            <!-- header -->
            <header class="bg-white border-b border-gray-200 py-4 px-6 flex items-center justify-between sticky top-0 z-10">
                <div class="flex items-center">
                    <button id="mobileMenuBtn" class="md:hidden text-gray-600 mr-4 focus:outline-none">
                        <i class="fas fa-bars text-xl"></i>
                    </button>
                    <h2 id="pageTitle" class="text-lg font-semibold text-gray-800">Admin Dashboard</h2>
                </div>
                <div class="flex items-center space-x-4">
                    <!-- Notifications Bell -->
                    <div class="relative" id="notificationDropdown">
                        <button onclick="toggleNotifications()" class="relative text-gray-500 hover:text-primary transition-colors p-2">
                            <i class="fas fa-bell text-xl"></i>
                            <span id="notificationBadge" class="absolute top-1 right-1 bg-primary text-white text-[10px] font-black rounded-full w-5 h-5 flex items-center justify-center border-2 border-white hidden">0</span>
                        </button>

                        <!-- Notification Menu -->
                        <div id="notificationMenu" class="absolute right-0 mt-3 w-80 bg-white rounded-2xl shadow-2xl border border-gray-100 hidden z-50 overflow-hidden">
                            <div class="p-4 border-b border-gray-50 flex items-center justify-between">
                                <h4 class="font-bold text-gray-900">Notifications</h4>
                                <button onclick="markAllAsRead()" class="text-[10px] font-black text-primary uppercase tracking-widest hover:underline">Mark all read</button>
                            </div>
                            <div id="notificationList" class="max-h-[400px] overflow-y-auto">
                                <div class="p-8 text-center text-gray-400">
                                    <i class="fas fa-bell-slash text-3xl mb-3 block"></i>
                                    <p class="text-xs font-medium">No alerts today</p>
                                </div>
                            </div>
                        </div>
                    </div>
                    <div class="hidden md:flex items-center space-x-3">
                        <span class="text-sm text-gray-600">Election Commission</span>
                        <div class="w-9 h-9 rounded-full bg-gradient-to-br from-primary to-primary-dark flex items-center justify-center text-white font-bold">
                            EC
                        </div>
                    </div>
                </div>
            </header>

            @if(session('success'))
                <div class="mx-6 mt-4 p-4 bg-green-50 border border-green-200 text-green-700 rounded-xl flex items-center gap-3">
                    <i class="fas fa-check-circle"></i>
                    {{ session('success') }}
                </div>
            @endif

            <!-- ========== ADMIN PAGE CONTENT (7 sections) ========== -->
            <div class="flex-1 p-6 lg:p-8">
                
                <!-- -------------------- DASHBOARD -------------------- -->
                <div id="page-dashboard" class="page-content active-page space-y-6">
                    <!-- welcome banner -->
                    <div class="bg-white rounded-2xl border border-gray-200 p-6 shadow-sm">
                        <div class="flex items-start justify-between">
                            <div>
                                <span class="text-xs font-semibold uppercase tracking-wider text-primary">Good afternoon</span>
                                <h1 class="text-2xl lg:text-3xl font-bold text-gray-900 mt-1">Election Commission</h1>
                                <p class="text-gray-500 mt-1">2025 Guild Elections • Presidential, Faculty Reps, Referendum</p>
                            </div>
                            <div class="bg-primary/10 p-3 rounded-xl">
                                <i class="fas fa-gavel text-primary text-2xl"></i>
                            </div>
                        </div>
                    </div>

                    <!-- key metrics -->
                    <div class="grid grid-cols-2 lg:grid-cols-4 gap-5">
                        <div class="stat-card bg-white p-5 rounded-xl border border-gray-200 shadow-sm">
                            <div class="flex items-center justify-between">
                                <span class="text-gray-500 text-sm">Registered voters</span>
                                <i class="fas fa-user-check text-primary/60"></i>
                            </div>
                            <p class="text-2xl font-bold text-gray-900 mt-2">{{ number_format($totalVoters) }}</p>
                            <p class="text-xs text-green-600 mt-1">↑ Verified Students</p>
                        </div>
                        <div class="stat-card bg-white p-5 rounded-xl border border-gray-200 shadow-sm">
                            <div class="flex items-center justify-between">
                                <span class="text-gray-500 text-sm">Votes cast</span>
                                <i class="fas fa-vote-yea text-primary/60"></i>
                            </div>
                            <p class="text-2xl font-bold text-gray-900 mt-2">{{ number_format($votesCount) }}</p>
                            <p class="text-xs text-gray-500 mt-1">{{ $turnout }}% turnout</p>
                        </div>
                        <div class="stat-card bg-white p-5 rounded-xl border border-gray-200 shadow-sm">
                            <div class="flex items-center justify-between">
                                <span class="text-gray-500 text-sm">Active elections</span>
                                <i class="fas fa-check-circle text-primary/60"></i>
                            </div>
                            <p class="text-2xl font-bold text-gray-900 mt-2">{{ $activeElections }}</p>
                            <p class="text-xs text-gray-500 mt-1">Live categories</p>
                        </div>
                        <div class="stat-card bg-white p-5 rounded-xl border border-gray-200 shadow-sm">
                            <div class="flex items-center justify-between">
                                <span class="text-gray-500 text-sm">Manual Audits</span>
                                <i class="fas fa-shield-alt text-primary/60"></i>
                            </div>
                            <p class="text-2xl font-bold text-gray-900 mt-2">0</p>
                            <p class="text-xs text-green-600 mt-1">All clear</p>
                        </div>
                    </div>

                    <!-- real-time progress & alerts -->
                    <div class="grid lg:grid-cols-3 gap-6">
                        <div class="lg:col-span-2 bg-white rounded-2xl border border-gray-200 p-6 shadow-sm">
                            <div class="flex items-center justify-between mb-5">
                                <h3 class="font-bold text-gray-900 flex items-center gap-2">
                                    <i class="fas fa-chart-line text-primary"></i> 
                                    Live election progress
                                </h3>
                                <span class="text-xs bg-green-100 text-green-700 px-3 py-1 rounded-full flex items-center">
                                    <span class="w-1.5 h-1.5 bg-green-600 rounded-full mr-1.5"></span> real‑time
                                </span>
                            </div>
                            <div class="space-y-5">
                                @forelse($categoriesStats->take(3) as $stat)
                                    @php 
                                        $perc = $totalVoters > 0 ? round(($stat->votes_count / $totalVoters) * 100, 1) : 0;
                                    @endphp
                                    <div>
                                        <div class="flex justify-between text-sm mb-1">
                                            <span class="font-medium">{{ $stat->name }}</span>
                                            <span class="font-bold text-gray-900">{{ number_format($stat->votes_count) }} / {{ number_format($totalVoters) }} ({{ $perc }}%)</span>
                                        </div>
                                        <div class="w-full bg-gray-200 rounded-full h-2.5">
                                            <div class="bg-primary h-2.5 rounded-full" style="width: {{ $perc }}%"></div>
                                        </div>
                                    </div>
                                @empty
                                    <p class="text-sm text-gray-500">No active categories found.</p>
                                @endforelse
                            </div>
                            <div class="mt-6 pt-4 border-t border-gray-100 text-xs text-gray-400 flex justify-between">
                                <span>Updated: just now</span>
                                <a data-page="reports" class="text-primary hover:underline cursor-pointer">View detailed</a>
                            </div>
                        </div>
                        
                        <div class="bg-white rounded-2xl border border-gray-200 p-6 shadow-sm">
                            <h3 class="font-bold text-gray-900 flex items-center gap-2 mb-4">
                                <i class="fas fa-exclamation-triangle text-primary"></i> 
                                Alerts & notices
                            </h3>
                            <div class="space-y-4">
                                @php $pendingCandidates = $candidates->where('status', 'pending')->count(); @endphp
                                @if($pendingCandidates > 0)
                                    <div class="flex gap-3 p-3 bg-yellow-50 rounded-xl border border-yellow-200">
                                        <i class="fas fa-clock text-yellow-600 mt-0.5"></i>
                                        <div>
                                            <p class="font-semibold text-gray-900 text-sm">Pending candidate approval</p>
                                            <p class="text-xs text-gray-600">{{ $pendingCandidates }} candidates awaiting verification</p>
                                        </div>
                                    </div>
                                @endif
                                <div class="flex gap-3 p-3 bg-blue-50 rounded-xl border border-blue-200">
                                    <i class="fas fa-info-circle text-blue-600 mt-0.5"></i>
                                    <div>
                                        <p class="font-semibold text-gray-900 text-sm">System backup</p>
                                        <p class="text-xs text-gray-600">Last backup: 2 hours ago · successful</p>
                                    </div>
                                </div>
                                <div class="flex gap-3 p-3 bg-green-50 rounded-xl border border-green-200">
                                    <i class="fas fa-check-circle text-green-600 mt-0.5"></i>
                                    <div>
                                        <p class="font-semibold text-gray-900 text-sm">Audit ready</p>
                                        <p class="text-xs text-gray-600">No discrepancies found</p>
                                    </div>
                                </div>
                            </div>
                        </div>
                    </div>

                    <!-- quick actions -->
                    <div class="bg-white rounded-2xl border border-gray-200 p-6 shadow-sm">
                        <h3 class="font-bold text-gray-900 mb-4 flex items-center gap-2">
                            <i class="fas fa-bolt text-primary"></i> Quick actions
                        </h3>
                        <div class="grid grid-cols-2 md:grid-cols-4 gap-4">
                            <a onclick="openModal('newElectionModal')" class="flex flex-col items-center p-4 bg-gray-50 rounded-xl hover:bg-red-50 transition cursor-pointer">
                                <i class="fas fa-plus-circle text-primary text-xl mb-2"></i>
                                <span class="text-sm font-medium text-gray-700">New election</span>
                            </a>
                            <a onclick="openModal('newCandidateModal')" class="flex flex-col items-center p-4 bg-gray-50 rounded-xl hover:bg-red-50 transition cursor-pointer">
                                <i class="fas fa-user-plus text-primary text-xl mb-2"></i>
                                <span class="text-sm font-medium text-gray-700">Add candidate</span>
                            </a>
                            <a data-page="voters" class="flex flex-col items-center p-4 bg-gray-50 rounded-xl hover:bg-red-50 transition cursor-pointer">
                                <i class="fas fa-file-import text-primary text-xl mb-2"></i>
                                <span class="text-sm font-medium text-gray-700">Import voters</span>
                            </a>
                            <a data-page="reports" class="flex flex-col items-center p-4 bg-gray-50 rounded-xl hover:bg-red-50 transition cursor-pointer">
                                <i class="fas fa-download text-primary text-xl mb-2"></i>
                                <span class="text-sm font-medium text-gray-700">Export results</span>
                            </a>
                        </div>
                    </div>
                </div>

                <!-- -------------------- ELECTIONS MANAGEMENT -------------------- -->
                <div id="page-elections" class="page-content space-y-6">
                    <div class="bg-white rounded-2xl border border-gray-200 p-6 md:p-8 shadow-sm">
                        <div class="flex flex-col sm:flex-row sm:items-center justify-between gap-4 mb-6">
                            <h2 class="text-2xl font-bold text-gray-900">Election management</h2>
                            <button onclick="openModal('newElectionModal')" class="bg-primary text-white px-5 py-2.5 rounded-xl text-sm font-medium hover:bg-primary-dark transition flex items-center gap-2 shadow-sm">
                                <i class="fas fa-plus"></i> Create new election
                            </button>
                        </div>
                        
                        <!-- elections list -->
                        <div class="space-y-5">
                            @foreach($categories as $category)
                                <div class="border border-gray-200 rounded-xl p-5 hover:border-primary transition">
                                    <div class="flex flex-col lg:flex-row lg:items-center justify-between gap-4">
                                        <div class="flex items-start gap-4">
                                            <div class="w-12 h-12 bg-primary/10 rounded-xl flex items-center justify-center flex-shrink-0">
                                                <i class="fas fa-vote-yea text-primary text-xl"></i>
                                            </div>
                                            <div>
                                                <h4 class="font-bold text-gray-900 text-lg">{{ $category->name }}</h4>
                                                @php 
                                                    $statusClasses = [
                                                        'nomination' => 'bg-blue-100 text-blue-700',
                                                        'voting' => 'bg-green-100 text-green-700',
                                                        'closed' => 'bg-gray-100 text-gray-700'
                                                    ];
                                                @endphp
                                                <p class="text-sm text-gray-500 mt-1">Status: <span class="{{ $statusClasses[$category->status] ?? 'bg-gray-100' }} px-3 py-1 rounded-full text-xs font-medium ml-1">{{ ucfirst($category->status) }}</span></p>
                                                <p class="text-xs text-gray-500 mt-2">{{ $category->candidates_count }} candidates • {{ $category->votes_count ?? 0 }} votes cast</p>
                                            </div>
                                        </div>
                                        <div class="flex items-center gap-3">
                                            <form action="{{ route('admin.categories.status', $category->id) }}" method="POST" class="inline-flex gap-2">
                                                @csrf
                                                <select name="status" class="text-xs border border-gray-300 rounded-lg px-2 py-1">
                                                    <option value="nomination" {{ $category->status == 'nomination' ? 'selected' : '' }}>Nomination</option>
                                                    <option value="voting" {{ $category->status == 'voting' ? 'selected' : '' }}>Voting</option>
                                                    <option value="closed" {{ $category->status == 'closed' ? 'selected' : '' }}>Closed</option>
                                                </select>
                                                <button type="submit" class="text-xs bg-gray-100 px-3 py-1 rounded-lg hover:bg-gray-200">Update</button>
                                            </form>
                                        </div>
                                    </div>
                                </div>
                            @endforeach
                        </div>
                    </div>
                </div>

                <!-- -------------------- CANDIDATES MANAGEMENT -------------------- -->
                <div id="page-candidates" class="page-content space-y-6">
                    <div class="bg-white rounded-2xl border border-gray-200 p-6 md:p-8 shadow-sm">
                        <div class="flex flex-col sm:flex-row sm:items-center justify-between gap-4 mb-6">
                            <h2 class="text-2xl font-bold text-gray-900">Candidate management</h2>
                            <button onclick="openModal('newCandidateModal')" class="bg-primary text-white px-5 py-2.5 rounded-xl text-sm font-medium hover:bg-primary-dark transition flex items-center gap-2 shadow-sm">
                                <i class="fas fa-user-plus"></i> Add candidate
                            </button>
                        </div>

                        <!-- candidates table -->
                        <div class="overflow-x-auto">
                            <table class="w-full text-sm">
                                <thead class="bg-gray-50 text-gray-600">
                                    <tr>
                                        <th class="px-4 py-3 text-left font-medium">Candidate</th>
                                        <th class="px-4 py-3 text-left font-medium">Election</th>
                                        <th class="px-4 py-3 text-left font-medium">Faculty</th>
                                        <th class="px-4 py-3 text-left font-medium">Status</th>
                                        <th class="px-4 py-3 text-left font-medium">Actions</th>
                                    </tr>
                                </thead>
                                <tbody class="divide-y divide-gray-200">
                                    @foreach($candidates as $candidate)
                                    <tr>
                                        <td class="px-4 py-3">
                                            <div class="flex items-center gap-3">
                                                <div class="w-8 h-8 bg-red-100 rounded-full flex items-center justify-center text-primary font-bold text-sm">
                                                    {{ substr($candidate->name, 0, 1) }}{{ substr(strrchr($candidate->name, " "), 1, 1) }}
                                                </div>
                                                <span class="font-medium text-gray-900">{{ $candidate->name }}</span>
                                            </div>
                                        </td>
                                        <td class="px-4 py-3 text-gray-700">{{ $candidate->category?->name ?? 'No Category' }}</td>
                                        <td class="px-4 py-3 text-gray-700">{{ $candidate->faculty }}</td>
                                        <td class="px-4 py-3">
                                            <span class="px-3 py-1 rounded-full text-xs {{ $candidate->status == 'approved' ? 'bg-green-100 text-green-700' : ($candidate->status == 'pending' ? 'bg-yellow-100 text-yellow-700' : 'bg-red-100 text-red-700') }}">
                                                {{ ucfirst($candidate->status) }}
                                            </span>
                                        </td>
                                        <td class="px-4 py-3">
                                            @if($candidate->status == 'pending')
                                                <form action="{{ route('admin.candidates.status', $candidate->id) }}" method="POST" class="inline">
                                                    @csrf
                                                    <input type="hidden" name="status" value="approved">
                                                    <button type="submit" class="text-green-600 hover:underline text-xs font-medium">Approve</button>
                                                </form>
                                                <span class="mx-2 text-gray-300">|</span>
                                                <form action="{{ route('admin.candidates.status', $candidate->id) }}" method="POST" class="inline">
                                                    @csrf
                                                    <input type="hidden" name="status" value="rejected">
                                                    <button type="submit" class="text-red-600 hover:underline text-xs">Reject</button>
                                                </form>
                                            @else
                                                <span class="text-gray-400 italic text-xs">No active actions</span>
                                            @endif
                                        </td>
                                    </tr>
                                    @endforeach
                                </tbody>
                            </table>
                        </div>
                    </div>
                </div>

                <!-- -------------------- VOTER MANAGEMENT -------------------- -->
                <div id="page-voters" class="page-content space-y-6">
                    <div class="bg-white rounded-2xl border border-gray-200 p-6 md:p-8 shadow-sm">
                        <div class="flex flex-col sm:flex-row sm:items-center justify-between gap-4 mb-6">
                            <h2 class="text-2xl font-bold text-gray-900">Voter management</h2>
                        </div>
                        
                        <div class="overflow-x-auto">
                            <table class="w-full text-sm">
                                <thead class="bg-gray-50 text-gray-600">
                                    <tr>
                                        <th class="px-4 py-3 text-left font-medium">Student ID</th>
                                        <th class="px-4 py-3 text-left font-medium">Name</th>
                                        <th class="px-4 py-3 text-left font-medium">Email</th>
                                        <th class="px-4 py-3 text-left font-medium">Role</th>
                                        <th class="px-4 py-3 text-left font-medium">Joined</th>
                                    </tr>
                                </thead>
                                <tbody class="divide-y divide-gray-200">
                                    @foreach($voters->take(50) as $voter)
                                    <tr>
                                        <td class="px-4 py-3 font-mono text-xs">{{ $voter->student_id }}</td>
                                        <td class="px-4 py-3">{{ $voter->name }}</td>
                                        <td class="px-4 py-3">{{ $voter->email }}</td>
                                        <td class="px-4 py-3"><span class="bg-green-100 text-green-700 px-3 py-1 rounded-full text-xs">{{ $voter->role }}</span></td>
                                        <td class="px-4 py-3 text-gray-500">{{ $voter->created_at->format('d M Y') }}</td>
                                    </tr>
                                    @endforeach
                                </tbody>
                            </table>
                        </div>
                    </div>
                </div>

                <!-- -------------------- AUDIT LOGS -------------------- -->
                <div id="page-audit" class="page-content space-y-6">
                    <div class="bg-white rounded-2xl border border-gray-200 p-6 md:p-8 shadow-sm">
                        <h2 class="text-2xl font-bold text-gray-900 mb-6">Recent voting activity</h2>
                        <div class="overflow-x-auto">
                            <table class="w-full text-sm">
                                <thead class="bg-gray-50 text-gray-600">
                                    <tr>
                                        <th class="px-4 py-3 text-left font-medium">Timestamp</th>
                                        <th class="px-4 py-3 text-left font-medium">Voter</th>
                                        <th class="px-4 py-3 text-left font-medium">Category</th>
                                        <th class="px-4 py-3 text-left font-medium">Candidate Selected</th>
                                    </tr>
                                </thead>
                                <tbody class="divide-y divide-gray-200">
                                    @foreach($recentVotes as $vote)
                                    <tr>
                                        <td class="px-4 py-3 text-gray-500">{{ $vote->created_at->format('Y-m-d H:i:s') }}</td>
                                        <td class="px-4 py-3">{{ $vote->user?->name ?? 'Unknown User' }}</td>
                                        <td class="px-4 py-3">{{ $vote->category?->name ?? 'Unknown Category' }}</td>
                                        <td class="px-4 py-3"><span class="bg-blue-50 text-blue-700 px-3 py-1 rounded-full text-xs">{{ $vote->candidate?->name ?? 'Unknown Candidate' }}</span></td>
                                    </tr>
                                    @endforeach
                                </tbody>
                            </table>
                        </div>
                    </div>
                </div>

                <!-- -------------------- RESULTS & REPORTS -------------------- -->
                <div id="page-reports" class="page-content space-y-6">
                    <div class="bg-white rounded-2xl border border-gray-200 p-6 md:p-8 shadow-sm">
                        <h2 class="text-2xl font-bold text-gray-900 mb-6">Election analytics</h2>
                        <div class="grid lg:grid-cols-2 gap-6">
                            @foreach($categoriesStats as $stat)
                            <div class="bg-gray-50 p-5 rounded-xl border border-gray-200">
                                <h4 class="font-bold text-gray-800 mb-4">{{ $stat->name }}</h4>
                                <p class="text-xs text-gray-500 uppercase tracking-wider mb-2">Total votes cast: {{ $stat->total_votes }}</p>
                                <div class="space-y-3">
                                    @foreach($stat->candidates->sortByDesc('total_votes') as $cand)
                                        @php $perc = $stat->total_votes > 0 ? round(($cand->total_votes / $stat->total_votes) * 100, 1) : 0; @endphp
                                        <div>
                                            <div class="flex justify-between text-xs">
                                                <span>{{ $cand->name }}</span>
                                                <span class="font-bold">{{ $cand->total_votes }} ({{ $perc }}%)</span>
                                            </div>
                                            <div class="w-full bg-gray-200 h-2 rounded-full mt-1">
                                                <div class="bg-primary h-2 rounded-full" style="width: {{ $perc }}%"></div>
                                            </div>
                                        </div>
                                    @endforeach
                                </div>
                            </div>
                            @endforeach
                        </div>
                    </div>
                </div>

                <!-- -------------------- SETTINGS -------------------- -->
                <div id="page-settings" class="page-content space-y-6">
                    <div class="bg-white rounded-2xl border border-gray-200 p-6 md:p-8 shadow-sm">
                        <h2 class="text-2xl font-bold text-gray-900 mb-6">System settings</h2>
                        <form action="{{ route('admin.settings.update') }}" method="POST" class="space-y-8">
                            @csrf
                            <div>
                                <h3 class="font-semibold text-gray-800 mb-4 flex items-center gap-2"><i class="fas fa-shield-alt text-primary"></i> Application Settings</h3>
                                <div class="grid md:grid-cols-2 gap-6">
                                    <div class="border border-gray-200 rounded-xl p-5">
                                        <div class="flex items-center justify-between">
                                            <div>
                                                <p class="font-medium text-gray-900">Nomination Module</p>
                                                <p class="text-xs text-gray-500 mt-1">Enable students to submit their own nominations</p>
                                            </div>
                                            <label class="relative inline-flex items-center cursor-pointer">
                                                <input type="hidden" name="settings[nomination_enabled]" value="0">
                                                <input type="checkbox" name="settings[nomination_enabled]" value="1" class="sr-only peer" {{ (\App\Models\SystemSetting::where('key', 'nomination_enabled')->first()->value ?? '0') == '1' ? 'checked' : '' }}>
                                                <div class="w-11 h-6 bg-gray-200 peer-focus:ring-2 peer-focus:ring-primary/20 rounded-full peer peer-checked:after:translate-x-full after:content-[''] after:absolute after:top-[2px] after:left-[2px] after:bg-white after:border-gray-300 after:border after:rounded-full after:h-5 after:w-5 after:transition-all peer-checked:bg-primary"></div>
                                            </label>
                                        </div>
                                    </div>
                                </div>
                            </div>
                            <div class="pt-4 border-t border-gray-200 flex justify-end">
                                <button type="submit" class="bg-primary text-white px-6 py-2.5 rounded-xl text-sm font-medium hover:bg-primary-dark transition">Save system settings</button>
                            </div>
                        </form>
                    </div>
                </div>
            </div>
        </main>
    </div>

    <!-- Modals -->
    <div id="newElectionModal" class="fixed inset-0 bg-black/50 z-50 flex items-center justify-center p-4 hidden">
        <div class="bg-white rounded-2xl w-full max-w-lg p-6 max-h-[90vh] overflow-y-auto">
            <div class="flex items-center justify-between mb-4">
                <h3 class="text-xl font-bold">Create New Category</h3>
                <button onclick="closeModal('newElectionModal')" class="text-gray-400 hover:text-gray-600">
                    <i class="fas fa-times"></i>
                </button>
            </div>

            <!-- Form to create category -->
            <form id="createCategoryForm" class="space-y-4">
                <div>
                    <label class="block text-sm font-medium text-gray-700 mb-1">Category Name *</label>
                    <input type="text" name="name" id="categoryName" class="w-full border border-gray-300 rounded-lg px-4 py-2 focus:ring-2 focus:ring-primary/20 focus:border-primary outline-none" placeholder="e.g. Guild President, Vice President" required>
                </div>

                <div>
                    <label class="block text-sm font-medium text-gray-700 mb-1">Faculty Restriction (Optional)</label>
                    <select id="facultyRestriction" name="faculty_restriction" class="w-full border border-gray-300 rounded-lg px-4 py-2 focus:ring-2 focus:ring-primary/20 focus:border-primary outline-none">
                        <option value="">Open to All Faculties</option>
                        <option value="Science & Technology">Science & Technology</option>
                        <option value="Business & Management">Business & Management</option>
                        <option value="Law">Law</option>
                        <option value="Social Sciences">Social Sciences</option>
                        <option value="Engineering">Engineering</option>
                    </select>
                    <p class="text-[10px] text-gray-400 mt-1 italic">If selection is empty, all students can vote.</p>
                </div>

                <div>
                    <label class="block text-sm font-medium text-gray-700 mb-1">Description *</label>
                    <textarea name="description" id="categoryDescription" class="w-full border border-gray-300 rounded-lg px-4 py-2 focus:ring-2 focus:ring-primary/20 focus:border-primary outline-none" placeholder="Enter category details and requirements..." rows="3" required></textarea>
                </div>

                <div class="grid grid-cols-2 gap-4">
                    <div>
                        <label class="block text-sm font-medium text-gray-700 mb-1">Icon Class *</label>
                        <input type="text" name="icon" id="categoryIcon" class="w-full border border-gray-300 rounded-lg px-4 py-2 focus:ring-2 focus:ring-primary/20 focus:border-primary outline-none" placeholder="e.g. fa-user-tie" value="fa-vote-yea" required>
                        <p class="text-xs text-gray-500 mt-1">FontAwesome class (without 'fas' or 'far')</p>
                    </div>
                    <div>
                        <label class="block text-sm font-medium text-gray-700 mb-1">Preview</label>
                        <div class="w-full border border-gray-300 rounded-lg px-4 py-2 bg-gray-50 flex items-center justify-center h-10">
                            <i id="iconPreview" class="fas fa-vote-yea text-primary text-lg"></i>
                        </div>
                    </div>
                </div>

                <div>
                    <label class="block text-sm font-medium text-gray-700 mb-1">Application Deadline *</label>
                    <input type="datetime-local" name="application_deadline" id="applicationDeadline" class="w-full border border-gray-300 rounded-lg px-4 py-2 focus:ring-2 focus:ring-primary/20 focus:border-primary outline-none" required>
                </div>

                <div class="flex items-center gap-3 p-3 bg-gray-50 rounded-lg">
                    <input type="checkbox" name="is_active" id="isActive" class="w-4 h-4 text-primary rounded" checked>
                    <label for="isActive" class="text-sm text-gray-700">Active category (students can see and apply)</label>
                </div>

                <!-- Status Messages -->
                <div id="categoryMessage" class="hidden p-3 rounded-lg text-sm"></div>

                <div class="flex justify-end gap-3 pt-4 border-t border-gray-200">
                    <button type="button" onclick="closeModal('newElectionModal')" class="px-4 py-2 text-gray-600 hover:bg-gray-100 rounded-lg">Cancel</button>
                    <button type="submit" id="submitCategoryBtn" class="bg-primary text-white px-6 py-2 rounded-lg hover:bg-primary-dark transition flex items-center gap-2">
                        <i class="fas fa-plus"></i>
                        <span>Create Category</span>
                    </button>
                </div>
            </form>
        </div>
    </div>

    <div id="newCandidateModal" class="fixed inset-0 bg-black/50 z-50 flex items-center justify-center p-4 hidden">
        <div class="bg-white rounded-2xl w-full max-w-lg p-6 max-h-[90vh] overflow-y-auto">
            <h3 class="text-xl font-bold mb-4">Nominate New Candidate</h3>
            <form action="{{ route('admin.candidates.store') }}" method="POST" enctype="multipart/form-data" class="space-y-4">
                @csrf
                <div>
                    <label class="block text-sm font-medium text-gray-700">Full Name</label>
                    <input type="text" name="name" class="w-full mt-1 border border-gray-300 rounded-lg px-4 py-2" required>
                </div>
                <div>
                    <label class="block text-sm font-medium text-gray-700">Election Category</label>
                    <select name="category_id" class="w-full mt-1 border border-gray-300 rounded-lg px-4 py-2" required>
                        @foreach($categories as $category)
                            <option value="{{ $category->id }}">{{ $category->name }}</option>
                        @endforeach
                    </select>
                </div>
                <div class="grid grid-cols-2 gap-4">
                    <div>
                        <label class="block text-sm font-medium text-gray-700">Faculty</label>
                        <input type="text" name="faculty" class="w-full mt-1 border border-gray-300 rounded-lg px-4 py-2" required>
                    </div>
                    <div>
                        <label class="block text-sm font-medium text-gray-700">Class/Year</label>
                        <input type="text" name="student_class" class="w-full mt-1 border border-gray-300 rounded-lg px-4 py-2" required>
                    </div>
                </div>
                <div>
                    <label class="block text-sm font-medium text-gray-700">Biography/Manifesto</label>
                    <textarea name="biography" class="w-full mt-1 border border-gray-300 rounded-lg px-4 py-2" required></textarea>
                </div>
                <div>
                    <label class="block text-sm font-medium text-gray-700">Photo</label>
                    <input type="file" name="photo" class="w-full mt-1">
                </div>
                <div class="flex justify-end gap-3 mt-6">
                    <button type="button" onclick="closeModal('newCandidateModal')" class="px-4 py-2 text-gray-600">Cancel</button>
                    <button type="submit" class="bg-primary text-white px-6 py-2 rounded-lg">nominate</button>
                </div>
            </form>
        </div>
    </div>

    <!-- JavaScript: page switching, mobile sidebar, no alerts -->
    <script>
        (function() {
            // Mobile sidebar toggle
            const sidebar = document.getElementById('sidebar');
            const backdrop = document.getElementById('sidebarBackdrop');
            const mobileBtn = document.getElementById('mobileMenuBtn');
            function openSidebar() { sidebar.classList.remove('-translate-x-full'); backdrop.classList.remove('hidden'); document.body.classList.add('overflow-hidden', 'md:overflow-auto'); }
            function closeSidebar() { sidebar.classList.add('-translate-x-full'); backdrop.classList.add('hidden'); document.body.classList.remove('overflow-hidden', 'md:overflow-auto'); }
            if (mobileBtn) mobileBtn.addEventListener('click', openSidebar);
            if (backdrop) backdrop.addEventListener('click', closeSidebar);
            window.addEventListener('resize', function() {
                if (window.innerWidth >= 768) { sidebar.classList.remove('-translate-x-full'); if (backdrop) backdrop.classList.add('hidden'); document.body.classList.remove('overflow-hidden', 'md:overflow-auto'); }
                else { sidebar.classList.add('-translate-x-full'); }
            });

            // Page switching
            const pages = {
                dashboard: document.getElementById('page-dashboard'),
                elections: document.getElementById('page-elections'),
                candidates: document.getElementById('page-candidates'),
                voters: document.getElementById('page-voters'),
                audit: document.getElementById('page-audit'),
                reports: document.getElementById('page-reports'),
                settings: document.getElementById('page-settings')
            };
            const sidebarLinks = document.querySelectorAll('.sidebar-link[data-page]');
            const pageTitle = document.getElementById('pageTitle');
            function activatePage(pageId) {
                Object.values(pages).forEach(p => p?.classList.remove('active-page'));
                if (pages[pageId]) pages[pageId].classList.add('active-page');
                sidebarLinks.forEach(link => {
                    link.classList.remove('active');
                    if (link.dataset.page === pageId) link.classList.add('active');
                });
                const titles = {
                    dashboard: 'Admin Dashboard',
                    elections: 'Election Management',
                    candidates: 'Candidate Management',
                    voters: 'Voter Management',
                    audit: 'Audit Logs',
                    reports: 'Results & Reports',
                    settings: 'System Settings'
                };
                if (pageTitle) pageTitle.textContent = titles[pageId] || 'Admin Console';
                if (window.innerWidth < 768) closeSidebar();
                localStorage.setItem('admin_last_page', pageId);
            }
            sidebarLinks.forEach(link => {
                link.addEventListener('click', function(e) {
                    e.preventDefault();
                    const page = this.dataset.page;
                    if (page) activatePage(page);
                });
            });
            // also handle quick action links
            document.querySelectorAll('[data-page]').forEach(el => {
                if (el.classList.contains('sidebar-link')) return;
                el.addEventListener('click', function(e) {
                    e.preventDefault();
                    const page = this.dataset.page;
                    if (page && pages[page]) activatePage(page);
                });
            });

            // Default to dashboard
            const lastPage = localStorage.getItem('admin_last_page') || 'dashboard';
            activatePage(lastPage);

            // Modal helpers
            window.openModal = function(id) { document.getElementById(id).classList.remove('hidden'); }
            window.closeModal = function(id) { 
                document.getElementById(id).classList.add('hidden');
                // Clear form if closing category modal
                if (id === 'newElectionModal') {
                    document.getElementById('createCategoryForm').reset();
                    document.getElementById('categoryMessage').classList.add('hidden');
                }
            }

            // Prevent default on empty href links
            document.querySelectorAll('a[href="#"]').forEach(link => {
                link.addEventListener('click', function(e) { e.preventDefault(); });
            });

            // ========== CATEGORY CREATION VIA API ==========
            const categoryIconInput = document.getElementById('categoryIcon');
            const iconPreview = document.getElementById('iconPreview');
            const createCategoryForm = document.getElementById('createCategoryForm');
            const categoryMessage = document.getElementById('categoryMessage');
            const submitCategoryBtn = document.getElementById('submitCategoryBtn');

            // Update icon preview
            if (categoryIconInput) {
                categoryIconInput.addEventListener('input', function() {
                    iconPreview.className = `fas ${this.value || 'fa-vote-yea'} text-primary text-lg`;
                });
            }

            // Handle category form submission
            if (createCategoryForm) {
                createCategoryForm.addEventListener('submit', async function(e) {
                    e.preventDefault();

                    const name = document.getElementById('categoryName').value;
                    const description = document.getElementById('categoryDescription').value;
                    const icon = document.getElementById('categoryIcon').value;
                    const deadline = document.getElementById('applicationDeadline').value;
                    const facultyRestriction = document.getElementById('facultyRestriction').value;
                    const isActive = document.getElementById('isActive').checked;

                    // Show loading state
                    submitCategoryBtn.disabled = true;
                    submitCategoryBtn.innerHTML = '<i class="fas fa-spinner fa-spin"></i> <span>Creating...</span>';
                    categoryMessage.classList.add('hidden');

                    try {
                        const response = await fetch('/api/admin/categories', {
                            method: 'POST',
                            headers: {
                                'Content-Type': 'application/json',
                                'Authorization': 'Bearer ' + (localStorage.getItem('auth_token') || ''),
                                'X-CSRF-Token': document.querySelector('meta[name="csrf-token"]')?.content || ''
                            },
                            body: JSON.stringify({
                                name: name,
                                description: description,
                                faculty_restriction: facultyRestriction,
                                icon: icon,
                                application_deadline: new Date(deadline).toISOString(),
                                is_active: isActive
                            })
                        });

                        const data = await response.json();

                        if (response.ok && data.success) {
                            // Show success message
                            categoryMessage.className = 'p-3 rounded-lg text-sm bg-green-100 text-green-700 flex items-center gap-2';
                            categoryMessage.innerHTML = '<i class="fas fa-check-circle"></i> Category created successfully!';
                            categoryMessage.classList.remove('hidden');

                            // Close modal after 1.5 seconds
                            setTimeout(() => {
                                window.closeModal('newElectionModal');
                                // Refresh the page or reload elections
                                setTimeout(() => location.reload(), 300);
                            }, 1500);
                        } else {
                            throw new Error(data.message || 'Failed to create category');
                        }
                    } catch (error) {
                        categoryMessage.className = 'p-3 rounded-lg text-sm bg-red-100 text-red-700 flex items-center gap-2';
                        categoryMessage.innerHTML = `<i class="fas fa-exclamation-circle"></i> ${error.message || 'An error occurred'}`;
                        categoryMessage.classList.remove('hidden');
                    } finally {
                        submitCategoryBtn.disabled = false;
                        submitCategoryBtn.innerHTML = '<i class="fas fa-plus"></i> <span>Create Category</span>';
                    }
                });
            }

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
                            <div class="p-4 border-b border-gray-100 hover:bg-gray-50 transition cursor-default ${!n.read_at ? 'bg-primary/5' : ''}">
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
                    } else {
                        list.innerHTML = `
                            <div class="p-8 text-center text-gray-400">
                                <i class="fas fa-bell-slash text-3xl mb-3 block"></i>
                                <p class="text-xs font-medium">No alerts today</p>
                            </div>`;
                    }
                } catch(e) { console.error(e); }
            }

            window.markAllAsRead = async function() {
                try {
                    const res = await fetch('/admin/notifications/read', {
                        method: 'POST',
                        headers: { 
                            'X-CSRF-TOKEN': document.querySelector('meta[name="csrf-token"]')?.content || '',
                            'Content-Type': 'application/json'
                        }
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
                const dropdown = document.getElementById('notificationDropdown');
                if(dropdown && !dropdown.contains(e.target)) {
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
