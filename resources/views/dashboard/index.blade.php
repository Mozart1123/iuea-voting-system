<!DOCTYPE html>
<html lang="en">
<head>
    <meta charset="UTF-8">
    <meta name="viewport" content="width=device-width, initial-scale=1.0">
    <title>IUEA GuildVote – Student Portal</title>
    <script src="https://cdn.tailwindcss.com"></script>
    <link rel="preconnect" href="https://fonts.googleapis.com">
    <link rel="preconnect" href="https://fonts.gstatic.com" crossorigin>
    <link href="https://fonts.googleapis.com/css2?family=Inter:wght@300;400;500;600;700;800&display=swap" rel="stylesheet">
    <link rel="stylesheet" href="https://cdnjs.cloudflare.com/ajax/libs/font-awesome/6.4.0/css/all.min.css">
    <style>
        :root {
            --primary: #8B0000;
            --primary-dark: #700000;
            --primary-light: #d13a3a;
        }
        * {
            font-family: 'Inter', sans-serif;
        }
        body {
            background-color: #f9fafb;
        }
        .text-primary { color: var(--primary); }
        .bg-primary { background-color: var(--primary); }
        .border-primary { border-color: var(--primary); }
        .hover\:bg-primary:hover { background-color: var(--primary); }
        .hover\:text-primary:hover { color: var(--primary); }
        
        .sidebar-link {
            transition: all 0.2s ease;
            cursor: pointer;
            color: rgba(255, 255, 255, 0.7);
        }
        .sidebar-link i {
            color: rgba(255, 255, 255, 0.5);
        }
        .sidebar-link:hover {
            background-color: rgba(255, 255, 255, 0.1);
            color: white;
        }
        .sidebar-link:hover i {
            color: white;
        }
        .sidebar-link.active {
            background-color: rgba(255, 255, 255, 0.15);
            color: white;
            border-left: 4px solid white;
            border-radius: 0 12px 12px 0;
            margin-left: -16px;
            padding-left: 28px;
        }
        .sidebar-link.active i {
            color: white;
        }

        .page-content {
            display: none;
        }
        /* ... rest of existing styles ... */
        .page-content.active-page {
            display: block;
        }
        .candidate-card {
            transition: all 0.2s ease;
        }
        .candidate-card:hover {
            border-color: var(--primary);
            box-shadow: 0 8px 16px -4px rgba(139,0,0,0.1);
        }
        /* FAQ accordion */
        .faq-question {
            cursor: pointer;
        }
        .faq-answer {
            display: none;
            padding: 1rem 1.5rem;
            background-color: #f9fafb;
            border-top: 1px solid #e5e7eb;
        }
        .faq-item.active .faq-answer {
            display: block;
        }
        .faq-item.active .fa-chevron-down {
            transform: rotate(180deg);
        }
    </style>
</head>
<body class="antialiased">

    <div class="flex h-screen bg-gray-50">
        
        <!-- ========== SIDEBAR ========== -->
        <aside id="sidebar" class="fixed md:static inset-y-0 left-0 w-72 bg-primary flex flex-col transition-transform duration-300 transform -translate-x-full md:translate-x-0 z-30 shadow-2xl">
            <div class="p-6 border-b border-white/10">
                <a href="{{ url('/') }}" class="flex items-center space-x-3">
                    <div class="w-20 h-16 bg-white rounded-lg flex items-center justify-center overflow-hidden p-1">
                        <img src="https://iuea.ac.ug/sitepad-data/uploads/2024/07/IUEA-Logo-official-1.png" alt="IUEA Logo" class="w-full h-full object-contain">
                    </div>
                    <span class="font-bold text-xl tracking-tight text-white">IUEA <span class="text-white/80">GuildVote</span></span>
                </a>
            </div>
            
            <nav class="flex-1 px-4 py-8 space-y-2 overflow-y-auto">
                <a data-page="dashboard" class="sidebar-link active flex items-center px-4 py-3 text-sm font-semibold">
                    <i class="fas fa-home w-6"></i>
                    <span class="ml-3">Dashboard</span>
                </a>
                <a data-page="register" class="sidebar-link flex items-center px-4 py-3 text-sm font-semibold">
                    <i class="fas fa-pen-to-square w-6"></i>
                    <span class="ml-3">Voter Registration</span>
                    <span class="ml-auto bg-white/20 text-white text-[10px] uppercase font-black px-2 py-0.5 rounded-full">Status</span>
                </a>
                <a data-page="vote" class="sidebar-link flex items-center px-4 py-3 text-sm font-semibold">
                    <i class="fas fa-check-circle w-6"></i>
                    <span class="ml-3">Vote now</span>
                    @if($pendingVotes > 0)
                        <span class="ml-auto bg-white/20 text-white text-[10px] font-black px-2 py-0.5 rounded-full">{{ $pendingVotes }}</span>
                    @endif
                </a>
                <a data-page="results" class="sidebar-link flex items-center px-4 py-3 text-sm font-semibold">
                    <i class="fas fa-chart-bar w-6"></i>
                    <span class="ml-3">Results</span>
                </a>
                <a data-page="profile" class="sidebar-link flex items-center px-4 py-3 text-sm font-semibold">
                    <i class="fas fa-user-circle w-6"></i>
                    <span class="ml-3">Profile</span>
                </a>
                <a data-page="help" class="sidebar-link flex items-center px-4 py-3 text-sm font-semibold">
                    <i class="fas fa-question-circle w-6"></i>
                    <span class="ml-3">Help Center</span>
                </a>
            </nav>
            
            <div class="p-4 border-t border-white/10 bg-black/10">
                <div class="flex items-center mb-4 px-2">
                    <div class="w-10 h-10 rounded-full bg-white/20 flex items-center justify-center text-white font-black border border-white/30 text-xs">
                        {{ substr($user->name, 0, 1) }}{{ substr(strrchr($user->name, " "), 1, 1) }}
                    </div>
                    <div class="ml-3 overflow-hidden">
                        <p class="text-xs font-black text-white truncate">{{ $user->name }}</p>
                        <p class="text-[10px] text-white/50 font-bold uppercase tracking-widest">{{ $user->student_id }}</p>
                    </div>
                </div>
                <form action="{{ route('logout') }}" method="POST" id="logoutForm">
                    @csrf
                    <a onclick="document.getElementById('logoutForm').submit()" class="flex items-center px-4 py-2.5 text-xs font-black text-white/60 rounded-xl hover:bg-white/10 hover:text-white transition duration-200 cursor-pointer group">
                        <i class="fas fa-power-off w-6 group-hover:text-white transition"></i>
                        <span>End Session</span>
                    </a>
                </form>
            </div>
        </aside>

        <!-- Mobile sidebar backdrop -->
        <div id="sidebarBackdrop" class="fixed inset-0 bg-gray-900/40 z-20 hidden md:hidden transition-opacity"></div>

        <!-- ========== MAIN CONTENT ========== -->
        <main class="flex-1 flex flex-col min-w-0 overflow-auto bg-gray-50">
            
            <header class="bg-white border-b border-gray-200 py-4 px-6 flex items-center justify-between sticky top-0 z-10">
                <div class="flex items-center">
                    <button id="mobileMenuBtn" class="md:hidden text-gray-600 mr-4 focus:outline-none">
                        <i class="fas fa-bars text-xl"></i>
                    </button>
                    <h2 id="pageTitle" class="text-lg font-semibold text-gray-800">Dashboard</h2>
                </div>
                <div class="flex items-center space-x-4">
                    <button class="relative text-gray-500 hover:text-primary transition">
                        <i class="fas fa-bell text-xl"></i>
                        @if($pendingVotes > 0)
                            <span class="absolute -top-1 -right-1 bg-primary text-white text-xs rounded-full w-4 h-4 flex items-center justify-center">{{ $pendingVotes }}</span>
                        @endif
                    </button>
                    <div class="hidden md:flex items-center space-x-3">
                        <span class="text-sm text-gray-600">{{ $user->name }}</span>
                        <div class="w-9 h-9 rounded-full bg-gradient-to-br from-primary to-primary-dark flex items-center justify-center text-white font-bold text-xs">
                            {{ substr($user->name, 0, 1) }}{{ substr(strrchr($user->name, " "), 1, 1) }}
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

            @if(session('error'))
                <div class="mx-6 mt-4 p-4 bg-red-50 border border-red-200 text-red-700 rounded-xl flex items-center gap-3">
                    <i class="fas fa-exclamation-circle"></i>
                    {{ session('error') }}
                </div>
            @endif

            <!-- ========== PAGE CONTENT CONTAINER ========== -->
            <div class="flex-1 p-6 lg:p-8">
                
                <!-- DASHBOARD PAGE -->
                <div id="page-dashboard" class="page-content active-page space-y-8">
                    <div class="flex flex-col lg:flex-row gap-6">
                        <div class="bg-white rounded-2xl border border-gray-200 p-6 shadow-sm flex-1">
                            <div class="flex items-start justify-between">
                                <div>
                                    <span class="text-xs font-semibold uppercase tracking-wider text-primary">Welcome back</span>
                                    <h1 class="text-2xl lg:text-3xl font-bold text-gray-900 mt-1">{{ $user->name }}</h1>
                                    <p class="text-gray-500 mt-1">Faculty of Computing & Engineering • Student Portal</p>
                                </div>
                                <div class="bg-primary/10 p-3 rounded-xl">
                                    <i class="fas fa-graduation-cap text-primary text-2xl"></i>
                                </div>
                            </div>
                        </div>
                        
                        <div class="bg-white rounded-2xl border border-gray-200 p-6 shadow-sm lg:w-80">
                            @php $firstActive = $categories->first(); @endphp
                            <div class="flex items-center justify-between mb-3">
                                <span class="text-sm font-semibold text-gray-700">Current election</span>
                                @if($firstActive)
                                    <span class="bg-green-100 text-green-700 text-xs px-3 py-1 rounded-full flex items-center">
                                        <span class="w-1.5 h-1.5 bg-green-600 rounded-full mr-1.5"></span> open
                                    </span>
                                @else
                                    <span class="bg-gray-100 text-gray-700 text-xs px-3 py-1 rounded-full flex items-center">
                                        closed
                                    </span>
                                @endif
                            </div>
                            <h3 class="text-lg font-bold text-gray-900 mb-1">{{ $firstActive->name ?? 'No active elections' }}</h3>
                            <p class="text-sm text-gray-500 mb-4">Official Guild Polls 2025</p>
                            
                            @if($pendingVotes > 0)
                                <div class="bg-red-50 border border-red-200 rounded-xl p-4 flex items-center gap-3">
                                    <div class="w-10 h-10 bg-primary/20 rounded-full flex items-center justify-center">
                                        <i class="fas fa-vote-yea text-primary"></i>
                                    </div>
                                    <div class="flex-1">
                                        <p class="text-sm font-semibold text-gray-900">Pending votes</p>
                                        <p class="text-xs text-gray-600">You have {{ $pendingVotes }} votes to cast.</p>
                                    </div>
                                    <a data-page="vote" class="bg-primary text-white px-4 py-2 rounded-lg text-sm font-medium hover:bg-primary-dark transition shadow-sm cursor-pointer">
                                        Vote
                                    </a>
                                </div>
                            @elseif($totalCategories > 0)
                                <div class="bg-green-50 border border-green-200 rounded-xl p-4 flex items-center gap-3">
                                    <div class="w-10 h-10 bg-green-100 rounded-full flex items-center justify-center">
                                        <i class="fas fa-check-circle text-green-600"></i>
                                    </div>
                                    <div class="flex-1">
                                        <p class="text-sm font-semibold text-gray-900">All voted!</p>
                                        <p class="text-xs text-gray-600">Thank you for participating.</p>
                                    </div>
                                </div>
                            @endif
                        </div>
                    </div>

                    <div class="grid grid-cols-2 lg:grid-cols-4 gap-5">
                        <div class="bg-white p-5 rounded-xl border border-gray-200 shadow-sm">
                            <div class="flex items-center justify-between">
                                <span class="text-gray-500 text-sm">Active elections</span>
                                <i class="fas fa-list text-primary/60"></i>
                            </div>
                            <p class="text-2xl font-bold text-gray-900 mt-2">{{ $totalCategories }}</p>
                            <p class="text-xs text-gray-500 mt-1">Live categories</p>
                        </div>
                        <div class="bg-white p-5 rounded-xl border border-gray-200 shadow-sm">
                            <div class="flex items-center justify-between">
                                <span class="text-gray-500 text-sm">Your votes</span>
                                <i class="fas fa-check-circle text-primary/60"></i>
                            </div>
                            <p class="text-2xl font-bold text-gray-900 mt-2">{{ $votesCount }}</p>
                            <p class="text-xs text-gray-500 mt-1">Cast so far</p>
                        </div>
                        <div class="bg-white p-5 rounded-xl border border-gray-200 shadow-sm">
                            <div class="flex items-center justify-between">
                                <span class="text-gray-500 text-sm">Pending</span>
                                <i class="fas fa-clock text-primary/60"></i>
                            </div>
                            <p class="text-2xl font-bold text-gray-900 mt-2">{{ $pendingVotes }}</p>
                            <p class="text-xs text-gray-500 mt-1">Require action</p>
                        </div>
                        <div class="bg-white p-5 rounded-xl border border-gray-200 shadow-sm">
                            <div class="flex items-center justify-between">
                                <span class="text-gray-500 text-sm">Status</span>
                                <i class="fas fa-user-shield text-primary/60"></i>
                            </div>
                            <p class="text-2xl font-bold text-green-600 mt-2">Verified</p>
                            <p class="text-xs text-gray-500 mt-1">Identity secure</p>
                        </div>
                    </div>

                    <div class="grid lg:grid-cols-3 gap-6">
                        <div class="lg:col-span-2 bg-white rounded-2xl border border-gray-200 p-6 shadow-sm">
                            <div class="flex items-center justify-between mb-5">
                                <h3 class="font-bold text-gray-900 flex items-center gap-2">
                                    <i class="fas fa-chart-line text-primary"></i> 
                                    Real-time results (Current leader)
                                </h3>
                                <span class="text-xs bg-blue-100 text-blue-700 px-3 py-1 rounded-full">live</span>
                            </div>
                            
                            @if($results->count() > 0)
                                <div class="space-y-6">
                                    @foreach($results->take(2) as $category)
                                        <div>
                                            <p class="text-xs font-bold text-gray-400 uppercase tracking-widest mb-2">{{ $category->name }}</p>
                                            <div class="space-y-4">
                                                @foreach($category->candidates->take(2) as $candidate)
                                                    @php 
                                                        $percentage = $category->votes_count > 0 ? round(($candidate->votes_count / $category->votes_count) * 100) : 0;
                                                    @endphp
                                                    <div>
                                                        <div class="flex justify-between text-sm mb-1">
                                                            <span class="font-medium">{{ $candidate->name }}</span>
                                                            <span class="font-bold text-gray-900">{{ $candidate->votes_count }} votes</span>
                                                        </div>
                                                        <div class="w-full bg-gray-200 rounded-full h-2.5">
                                                            <div class="bg-primary h-2.5 rounded-full" style="width: {{ $percentage }}%"></div>
                                                        </div>
                                                        <p class="text-xs text-gray-500 mt-1">{{ $percentage }}% of total category votes</p>
                                                    </div>
                                                @endforeach
                                            </div>
                                        </div>
                                    @endforeach
                                </div>
                            @else
                                <p class="text-gray-500 text-center py-8">No results available yet.</p>
                            @endif

                            <div class="mt-6 pt-4 border-t border-gray-100 flex justify-between items-center">
                                <span class="text-xs text-gray-400">Last updated: just now</span>
                                <a data-page="results" class="text-primary hover:text-primary-dark text-sm font-medium flex items-center gap-1 cursor-pointer">
                                    View full results <i class="fas fa-arrow-right text-xs"></i>
                                </a>
                            </div>
                        </div>
                        
                        <div class="bg-white rounded-2xl border border-gray-200 p-6 shadow-sm">
                            <h3 class="font-bold text-gray-900 flex items-center gap-2 mb-5">
                                <i class="fas fa-calendar-alt text-primary"></i> 
                                Upcoming Events
                            </h3>
                            <div class="space-y-5">
                                <div class="flex gap-3">
                                    <div class="w-10 h-10 bg-red-50 rounded-lg flex items-center justify-center flex-shrink-0">
                                        <i class="fas fa-users text-primary"></i>
                                    </div>
                                    <div>
                                        <p class="font-semibold text-gray-900">Faculty representative</p>
                                        <p class="text-xs text-gray-500">Voting starts May 12, 2025</p>
                                    </div>
                                </div>
                                <div class="flex gap-3">
                                    <div class="w-10 h-10 bg-red-50 rounded-lg flex items-center justify-center flex-shrink-0">
                                        <i class="fas fa-gavel text-primary"></i>
                                    </div>
                                    <div>
                                        <p class="font-semibold text-gray-900">Guild constitutional amendments</p>
                                        <p class="text-xs text-gray-500">Referendum • May 20</p>
                                    </div>
                                </div>
                            </div>
                            <div class="mt-8">
                                <h4 class="text-sm font-semibold text-gray-800 mb-3">Notice Board</h4>
                                <div class="bg-gray-50 p-4 rounded-xl border border-gray-100">
                                    <p class="text-sm text-gray-700">
                                        <i class="fas fa-info-circle text-primary mr-1"></i>
                                        Student ID required to vote. Visit the IT desk if you need verification or password reset assistance.
                                    </p>
                                </div>
                            </div>
                        </div>
                    </div>
                </div>

                <!-- ========== VOTER REGISTRATION PAGE ========== -->
                <div id="page-register" class="page-content space-y-6">
                    <!-- Page Header -->
                    <div class="bg-white rounded-2xl border border-gray-200 p-6 md:p-8 shadow-sm">
                        <div class="flex items-center justify-between mb-2">
                            <div>
                                <h2 class="text-2xl font-bold text-gray-900">Voter Registration</h2>
                                <p class="text-gray-500 mt-1">Apply for election categories and track your applications.</p>
                            </div>
                            <div class="bg-red-50 px-4 py-2 rounded-lg border border-red-200 text-sm">
                                <i class="fas fa-id-card text-primary mr-2"></i>
                                <span class="font-medium text-gray-700">IUEA Student</span>
                            </div>
                        </div>
                    </div>

                    <!-- Student Status Card -->
                    <div class="bg-white rounded-2xl border border-gray-200 p-6 shadow-sm">
                        <div class="flex items-start justify-between gap-6">
                            <div class="flex-1">
                                <h3 class="text-lg font-bold text-gray-900">{{ $user->name }}</h3>
                                <p class="text-gray-500 text-sm mt-1">{{ $user->student_id }}</p>
                                <p class="text-gray-600 text-sm mt-1">Faculty of Computing & Engineering · Year 3</p>
                                <p class="text-gray-700 font-medium mt-3">You are currently <span class="text-green-600 font-bold">eligible</span> to apply for elections.</p>
                            </div>
                            <div class="flex items-center gap-3 bg-green-50 border border-green-200 px-5 py-3 rounded-xl h-fit whitespace-nowrap">
                                <span class="w-2.5 h-2.5 bg-green-600 rounded-full"></span>
                                <span class="font-bold text-green-700">Eligible</span>
                            </div>
                        </div>
                    </div>

                    <!-- Available Categories -->
                    <div class="bg-white rounded-2xl border border-gray-200 p-6 md:p-8 shadow-sm">
                        <div class="mb-6">
                            <h3 class="text-xl font-bold text-gray-900 flex items-center gap-2">
                                <i class="fas fa-list text-primary"></i>
                                Available Categories
                            </h3>
                            <p class="text-gray-500 text-sm mt-1">Click "Apply now" to submit your application for any position.</p>
                        </div>

                        <!-- Categories Grid -->
                        <div id="categoriesGrid" class="grid md:grid-cols-2 lg:grid-cols-3 gap-6">
                            <!-- Populated by JavaScript -->
                        </div>
                    </div>

                    <!-- My Applications Section -->
                    <div class="bg-white rounded-2xl border border-gray-200 p-6 md:p-8 shadow-sm">
                        <div class="mb-6">
                            <h3 class="text-xl font-bold text-gray-900 flex items-center gap-2">
                                <i class="fas fa-file-contract text-primary"></i>
                                My Applications
                            </h3>
                            <p class="text-gray-500 text-sm mt-1">Track the status of all your election applications.</p>
                        </div>

                        <!-- Applications List -->
                        <div id="applicationsList" class="space-y-4">
                            <!-- Populated by JavaScript -->
                        </div>

                        <!-- Empty State -->
                        <div id="emptyApplications" class="py-12 text-center">
                            <div class="w-16 h-16 bg-gray-100 rounded-full flex items-center justify-center mx-auto mb-4">
                                <i class="fas fa-inbox text-gray-400 text-2xl"></i>
                            </div>
                            <p class="text-gray-500">No applications yet. Browse available categories and apply now.</p>
                        </div>
                    </div>

                    <!-- Application History -->
                    <div class="bg-white rounded-2xl border border-gray-200 p-6 md:p-8 shadow-sm">
                        <h3 class="text-xl font-bold text-gray-900 flex items-center gap-2 mb-6">
                            <i class="fas fa-history text-primary"></i>
                            Application History
                        </h3>

                        <!-- History Timeline -->
                        <div id="historyTimeline" class="space-y-4">
                            <!-- Populated by JavaScript -->
                        </div>

                        <!-- Empty History -->
                        <div id="emptyHistory" class="py-8 text-center text-gray-500 text-sm">
                            No previous applications in your history.
                        </div>
                    </div>
                </div>

                <!-- ========== APPLICATION MODAL ========== -->
                <div id="applicationModal" class="fixed inset-0 bg-gray-900/50 backdrop-blur-sm z-50 hidden flex items-center justify-center p-4">
                    <div class="bg-white rounded-2xl border border-gray-200 shadow-2xl max-w-2xl w-full max-h-[90vh] overflow-y-auto">
                        <!-- Modal Header -->
                        <div class="sticky top-0 bg-white border-b border-gray-200 p-6 flex items-center justify-between">
                            <h2 class="text-xl font-bold text-gray-900" id="modalTitle">Apply for Position</h2>
                            <button class="closeModalBtn text-gray-400 hover:text-gray-600 text-2xl p-2 hover:bg-gray-100 rounded-lg transition">
                                ×
                            </button>
                        </div>

                        <!-- Modal Content -->
                        <form id="applicationForm" class="p-6 space-y-6">
                            <!-- Category Info -->
                            <div class="bg-red-50 border border-red-200 rounded-xl p-4">
                                <p class="text-sm text-gray-600">
                                    <span class="font-bold">Application deadline:</span>
                                    <span id="modalDeadline" class="font-medium">May 5, 2025</span>
                                </p>
                            </div>

                            <!-- Motivation Statement -->
                            <div>
                                <label class="block text-sm font-bold text-gray-800 mb-2">
                                    Motivation Statement <span class="text-primary">*</span>
                                </label>
                                <textarea name="motivation" id="motivationInput" 
                                          placeholder="Tell us why you're a great candidate for this position (100-500 characters)..."
                                          minlength="20" maxlength="500" rows="5"
                                          class="w-full px-4 py-3 border border-gray-300 rounded-xl bg-gray-50 focus:bg-white focus:ring-2 focus:ring-primary/20 focus:border-primary transition font-sm" required></textarea>
                                <p class="text-xs text-gray-500 mt-1">
                                    <span id="charCount">0</span>/500 characters
                                </p>
                            </div>

                            <!-- Manifesto Link -->
                            <div>
                                <label class="block text-sm font-bold text-gray-800 mb-2">
                                    Campaign Manifesto (Optional)
                                </label>
                                <input type="url" name="manifesto_url" id="manifestoInput" 
                                       placeholder="https://example.com/manifesto"
                                       class="w-full px-4 py-3 border border-gray-300 rounded-xl bg-gray-50 focus:bg-white focus:ring-2 focus:ring-primary/20 focus:border-primary transition">
                                <p class="text-xs text-gray-500 mt-1">Upload or link to your campaign manifesto document.</p>
                            </div>

                            <!-- Eligibility Checkbox -->
                            <div class="bg-gray-50 border border-gray-200 rounded-xl p-4">
                                <label class="flex items-start gap-3 cursor-pointer">
                                    <input type="checkbox" name="confirm_eligibility" id="eligibilityCheckbox" required
                                           class="mt-1 w-4 h-4 accent-primary rounded">
                                    <span class="text-sm text-gray-700">
                                        I confirm that I meet all eligibility requirements for this position, including being a registered IUEA student with no outstanding academic or disciplinary issues.
                                    </span>
                                </label>
                            </div>

                            <!-- Form Actions -->
                            <div class="flex gap-3 pt-4 border-t border-gray-200">
                                <button type="button" class="closeModalBtn flex-1 px-6 py-3 border border-gray-300 text-gray-700 rounded-xl font-bold hover:bg-gray-50 transition">
                                    Cancel
                                </button>
                                <button type="submit" class="flex-1 bg-primary text-white px-6 py-3 rounded-xl font-bold hover:bg-primary-dark transition shadow-lg shadow-primary/20">
                                    Submit Application
                                </button>
                            </div>
                        </form>
                    </div>
                </div>

                <!-- VOTE NOW PAGE -->
                <div id="page-vote" class="page-content space-y-6">
                    <div class="bg-white rounded-2xl border border-gray-200 p-6 md:p-8 shadow-sm">
                        <div class="flex items-center justify-between mb-6">
                            <div>
                                <h2 class="text-2xl font-bold text-gray-900">Cast your vote</h2>
                                <p class="text-gray-500 mt-1">2025 IUEA Guild Elections – Secure Ballot Panel</p>
                            </div>
                            <div class="bg-red-50 px-4 py-2 rounded-lg border border-red-200 text-sm">
                                <i class="fas fa-shield-alt text-primary mr-2"></i>
                                <span class="font-medium text-gray-700">End-to-end encrypted</span>
                            </div>
                        </div>

                        @if($categories->count() > 0)
                            <div class="space-y-12">
                                @foreach($categories as $category)
                                    <div>
                                        <h3 class="font-bold text-gray-900 text-lg mb-4 flex items-center justify-between">
                                            <span class="flex items-center">
                                                <span class="w-1.5 h-6 bg-primary rounded-full mr-3"></span>
                                                {{ $category->name }}
                                            </span>
                                            @if(in_array($category->id, $votedCategoryIds))
                                                <span class="text-xs bg-green-100 text-green-700 px-3 py-1 rounded-full"><i class="fas fa-check-circle"></i> Voted</span>
                                            @endif
                                        </h3>
                                        
                                        @if(!in_array($category->id, $votedCategoryIds))
                                            <div class="grid md:grid-cols-2 lg:grid-cols-3 gap-4">
                                                @foreach($category->candidates as $candidate)
                                                    <form action="{{ route('dashboard.vote') }}" method="POST">
                                                        @csrf
                                                        <input type="hidden" name="candidate_id" value="{{ $candidate->id }}">
                                                        <div class="candidate-card border border-gray-200 rounded-xl p-4 flex items-start gap-4 hover:border-primary transition group relative">
                                                            <div class="w-14 h-14 bg-red-50 rounded-full flex-shrink-0 flex items-center justify-center text-primary font-bold overflow-hidden border-2 border-white shadow-sm">
                                                                @if($candidate->photo_path)
                                                                    <img src="{{ Storage::url($candidate->photo_path) }}" class="w-full h-full object-cover">
                                                                @else
                                                                    {{ substr($candidate->name, 0, 1) }}{{ substr(strrchr($candidate->name, " "), 1, 1) }}
                                                                @endif
                                                            </div>
                                                            <div class="flex-1">
                                                                <p class="font-bold text-gray-900">{{ $candidate->name }}</p>
                                                                <p class="text-xs text-gray-500">{{ $candidate->faculty }}</p>
                                                                <button type="submit" class="mt-3 w-full bg-gray-50 text-gray-700 py-1.5 rounded-lg text-xs font-bold hover:bg-primary hover:text-white transition-colors border border-gray-200 group-hover:border-primary/20">
                                                                    Vote for Candidate
                                                                </button>
                                                            </div>
                                                        </div>
                                                    </form>
                                                @endforeach
                                            </div>
                                        @else
                                            <div class="bg-gray-50 rounded-xl p-8 border border-gray-200 text-center">
                                                <div class="w-12 h-12 bg-green-100 rounded-full flex items-center justify-center mx-auto mb-3">
                                                    <i class="fas fa-check text-green-600"></i>
                                                </div>
                                                <p class="text-sm text-gray-600 font-medium">Your vote for this position has been recorded.</p>
                                            </div>
                                        @endif
                                    </div>
                                @endforeach
                            </div>
                        @else
                            <div class="py-20 text-center">
                                <div class="w-16 h-16 bg-gray-100 rounded-full flex items-center justify-center mx-auto mb-4">
                                    <i class="fas fa-calendar-times text-gray-400 text-2xl"></i>
                                </div>
                                <h3 class="text-lg font-bold text-gray-900">No active voting sessions</h3>
                                <p class="text-gray-500 max-w-sm mx-auto mt-1">Voting is currently disabled as there are no open elections at this time.</p>
                            </div>
                        @endif
                    </div>
                </div>

                <!-- RESULTS PAGE -->
                <div id="page-results" class="page-content space-y-6">
                    <div class="bg-white rounded-2xl border border-gray-200 p-6 md:p-8 shadow-sm">
                        <div class="flex flex-col md:flex-row md:items-center justify-between gap-4 mb-6">
                            <div>
                                <h2 class="text-2xl font-bold text-gray-900">Election results (Live)</h2>
                                <p class="text-gray-500 mt-1">Real-time tally of all cast and verified ballots.</p>
                            </div>
                            <div class="flex items-center gap-3">
                                <span class="bg-green-100 text-green-700 px-4 py-2 rounded-full text-sm font-medium flex items-center gap-1">
                                    <span class="w-2 h-2 bg-green-600 rounded-full"></span> Live
                                </span>
                            </div>
                        </div>
                        
                        <div class="space-y-12">
                            @foreach($results as $category)
                                <div>
                                    <h3 class="font-bold text-gray-900 text-lg mb-6 flex items-center">
                                        <span class="w-1.5 h-6 bg-primary rounded-full mr-3"></span>
                                        {{ $category->name }}
                                        <span class="ml-auto text-xs font-medium text-gray-400 uppercase tracking-widest">{{ $category->votes_count }} total votes</span>
                                    </h3>
                                    
                                    <div class="grid lg:grid-cols-2 gap-8 bg-gray-50 rounded-2xl p-6 border border-gray-200">
                                        <div class="space-y-6">
                                            @foreach($category->candidates as $candidate)
                                                @php 
                                                    $percentage = $category->votes_count > 0 ? round(($candidate->votes_count / $category->votes_count) * 100) : 0;
                                                @endphp
                                                <div>
                                                    <div class="flex justify-between text-sm mb-1.5">
                                                        <span class="font-semibold text-gray-800">{{ $candidate->name }}</span>
                                                        <span class="font-bold text-gray-900">{{ $candidate->votes_count }} ({{ $percentage }}%)</span>
                                                    </div>
                                                    <div class="w-full bg-gray-200 rounded-full h-3">
                                                        <div class="bg-primary h-3 rounded-full transition-all duration-500" style="width: {{ $percentage }}%"></div>
                                                    </div>
                                                </div>
                                            @endforeach
                                        </div>
                                        
                                        @php $winner = $category->candidates->first(); @endphp
                                        @if($winner && $category->votes_count > 0)
                                            <div class="flex flex-col items-center justify-center text-center p-6 bg-white rounded-xl border border-gray-100 shadow-sm">
                                                <div class="w-20 h-20 bg-primary/10 rounded-full flex items-center justify-center text-primary text-3xl mb-4 relative">
                                                    <i class="fas fa-crown"></i>
                                                    <div class="absolute -top-1 -right-1 w-6 h-6 bg-primary text-white rounded-full flex items-center justify-center text-[10px] border-2 border-white">#1</div>
                                                </div>
                                                <p class="text-xs font-bold text-primary uppercase tracking-widest mb-1">Current Leader</p>
                                                <h4 class="text-xl font-bold text-gray-900">{{ $winner->name }}</h4>
                                                <p class="text-sm text-gray-500 mt-1">{{ $winner->faculty }}</p>
                                                <div class="mt-4 flex gap-4">
                                                    <div class="text-center">
                                                        <p class="text-xs text-gray-400 uppercase">Margin</p>
                                                        @php 
                                                            $second = $category->candidates->skip(1)->first();
                                                            $margin = $second ? $winner->votes_count - $second->votes_count : $winner->votes_count;
                                                        @endphp
                                                        <p class="font-bold text-gray-900">+{{ $margin }}</p>
                                                    </div>
                                                    <div class="text-center">
                                                        <p class="text-xs text-gray-400 uppercase">Status</p>
                                                        <p class="font-bold text-green-600">Leading</p>
                                                    </div>
                                                </div>
                                            </div>
                                        @endif
                                    </div>
                                </div>
                            @endforeach
                        </div>
                        
                        <div class="mt-12 pt-6 border-t border-gray-100 flex justify-between items-center bg-gray-50 -mx-6 -mb-6 p-6 rounded-b-2xl">
                            <span class="text-xs text-gray-400 italic">Data integrity verified by Guild Secure Tally System.</span>
                            <button class="text-primary hover:text-primary-dark text-sm font-bold flex items-center gap-2">
                                <i class="fas fa-file-pdf"></i> Download Official Results Sheet
                            </button>
                        </div>
                    </div>
                </div>

                <!-- PROFILE & SECURITY PAGE -->
                <div id="page-profile" class="page-content space-y-6">
                    <div class="bg-white rounded-2xl border border-gray-200 p-6 md:p-8 shadow-sm">
                        <h2 class="text-2xl font-bold text-gray-900 mb-6">Student Information</h2>
                        
                        <div class="flex flex-col lg:flex-row gap-8">
                            <div class="lg:w-1/3 flex flex-col items-center text-center">
                                <div class="w-32 h-32 rounded-3xl bg-gradient-to-br from-primary to-primary-dark flex items-center justify-center text-white text-5xl font-black mb-6 shadow-xl border-4 border-white">
                                    {{ substr($user->name, 0, 1) }}{{ substr(strrchr($user->name, " "), 1, 1) }}
                                </div>
                                <h3 class="text-2xl font-black text-gray-900">{{ $user->name }}</h3>
                                <p class="text-gray-500 font-medium">{{ $user->student_id }}</p>
                                <div class="mt-6 bg-green-50 text-green-700 px-6 py-2.5 rounded-xl text-sm font-bold border border-green-200 inline-flex items-center gap-2">
                                    <i class="fas fa-shield-check text-lg"></i>
                                    Verified Voter Identity
                                </div>
                            </div>
                            
                            <div class="lg:w-2/3">
                                <div class="grid md:grid-cols-2 gap-6">
                                    <div>
                                        <label class="block text-xs font-black uppercase tracking-widest text-gray-400 mb-2">Display Name</label>
                                        <p class="px-4 py-3 bg-gray-100 rounded-xl text-gray-700 font-bold border border-gray-200">{{ $user->name }}</p>
                                    </div>
                                    <div>
                                        <label class="block text-xs font-black uppercase tracking-widest text-gray-400 mb-2">University ID</label>
                                        <p class="px-4 py-3 bg-gray-100 rounded-xl text-gray-700 font-bold border border-gray-200">{{ $user->student_id }}</p>
                                    </div>
                                    <div>
                                        <label class="block text-xs font-black uppercase tracking-widest text-gray-400 mb-2">University Email</label>
                                        <p class="px-4 py-3 bg-gray-100 rounded-xl text-gray-700 font-bold border border-gray-200">{{ $user->email }}</p>
                                    </div>
                                    <div>
                                        <label class="block text-xs font-black uppercase tracking-widest text-gray-400 mb-2">Account Role</label>
                                        <p class="px-4 py-3 bg-gray-100 rounded-xl text-primary font-black border border-gray-200 uppercase tracking-tighter">{{ $user->role }}</p>
                                    </div>
                                </div>
                            </div>
                        </div>
                    </div>
                    
                    <div class="bg-white rounded-2xl border border-gray-200 p-6 md:p-8 shadow-sm">
                        <h3 class="text-xl font-bold text-gray-900 mb-6 flex items-center gap-2">
                            <i class="fas fa-lock text-primary"></i> Change Access Code
                        </h3>
                        
                        <form action="{{ route('dashboard.security.update') }}" method="POST" class="max-w-2xl">
                            @csrf
                            <div class="space-y-4">
                                <div>
                                    <label class="block text-sm font-semibold text-gray-700 mb-2">Current Password</label>
                                    <input type="password" name="current_password" placeholder="••••••••" class="w-full px-4 py-3 border border-gray-300 rounded-xl bg-gray-50 focus:bg-white focus:ring-2 focus:ring-primary/20 focus:border-primary transition" required>
                                </div>
                                <div class="grid md:grid-cols-2 gap-4">
                                    <div>
                                        <label class="block text-sm font-semibold text-gray-700 mb-2">New Password</label>
                                        <input type="password" name="new_password" placeholder="••••••••" class="w-full px-4 py-3 border border-gray-300 rounded-xl bg-gray-50 focus:bg-white focus:ring-2 focus:ring-primary/20 focus:border-primary transition" required>
                                    </div>
                                    <div>
                                        <label class="block text-sm font-semibold text-gray-700 mb-2">Confirm New Password</label>
                                        <input type="password" name="new_password_confirmation" placeholder="••••••••" class="w-full px-4 py-3 border border-gray-300 rounded-xl bg-gray-50 focus:bg-white focus:ring-2 focus:ring-primary/20 focus:border-primary transition" required>
                                    </div>
                                </div>
                                <button type="submit" class="bg-primary text-white px-8 py-3.5 rounded-xl font-bold hover:bg-primary-dark transition shadow-lg shadow-primary/20">
                                    Update Security Credentials
                                </button>
                            </div>
                        </form>
                    </div>
                </div>

                <!-- HELP & SUPPORT PAGE -->
                <div id="page-help" class="page-content space-y-6">
                    <div class="bg-white rounded-2xl border border-gray-200 p-6 md:p-8 shadow-sm">
                        <h2 class="text-2xl font-bold text-gray-900 mb-2">Help & support center</h2>
                        <p class="text-gray-500 mb-8">Access resources, guides, and contact information for the IUEA Guild Elections.</p>
                        
                        <div class="grid md:grid-cols-2 lg:grid-cols-4 gap-4 mb-10">
                            <div class="border border-gray-200 rounded-2xl p-6 text-center hover:border-primary transition cursor-pointer group">
                                <div class="w-14 h-14 bg-red-50 rounded-2xl flex items-center justify-center mx-auto mb-4 group-hover:bg-primary transition shadow-sm">
                                    <i class="fas fa-vote-yea text-primary text-2xl group-hover:text-white"></i>
                                </div>
                                <h4 class="font-bold text-gray-900">Voting process</h4>
                                <p class="text-xs text-gray-500 mt-1">Tutorials & Steps</p>
                            </div>
                            <div class="border border-gray-200 rounded-2xl p-6 text-center hover:border-primary transition cursor-pointer group">
                                <div class="w-14 h-14 bg-red-50 rounded-2xl flex items-center justify-center mx-auto mb-4 group-hover:bg-primary transition shadow-sm">
                                    <i class="fas fa-shield-alt text-primary text-2xl group-hover:text-white"></i>
                                </div>
                                <h4 class="font-bold text-gray-900">Privacy</h4>
                                <p class="text-xs text-gray-500 mt-1">Data protection</p>
                            </div>
                            <div class="border border-gray-200 rounded-2xl p-6 text-center hover:border-primary transition cursor-pointer group">
                                <div class="w-14 h-14 bg-red-50 rounded-2xl flex items-center justify-center mx-auto mb-4 group-hover:bg-primary transition shadow-sm">
                                    <i class="fas fa-user-cog text-primary text-2xl group-hover:text-white"></i>
                                </div>
                                <h4 class="font-bold text-gray-900">Account</h4>
                                <p class="text-xs text-gray-500 mt-1">ID & Identity</p>
                            </div>
                            <div class="border border-gray-200 rounded-2xl p-6 text-center hover:border-primary transition cursor-pointer group">
                                <div class="w-14 h-14 bg-red-50 rounded-2xl flex items-center justify-center mx-auto mb-4 group-hover:bg-primary transition shadow-sm">
                                    <i class="fas fa-laptop text-primary text-2xl group-hover:text-white"></i>
                                </div>
                                <h4 class="font-bold text-gray-900">Technical</h4>
                                <p class="text-xs text-gray-500 mt-1">System status</p>
                            </div>
                        </div>
                        
                        <h3 class="font-bold text-gray-900 text-lg mb-4">Frequently asked questions</h3>
                        <div class="space-y-4" id="faqAccordion">
                            <div class="faq-item border border-gray-200 rounded-2xl overflow-hidden bg-white shadow-sm">
                                <button class="faq-question w-full flex justify-between items-center p-6 text-left hover:bg-gray-50 transition">
                                    <span class="font-bold text-gray-900">How do I know my vote was counted?</span>
                                    <i class="fas fa-chevron-down text-gray-500 transition-transform"></i>
                                </button>
                                <div class="faq-answer text-gray-700 text-sm leading-relaxed p-6 bg-gray-50 border-t border-gray-100">
                                    After casting your vote, the system generates a unique hash. You can view your voting history in the "Registration" tab, and once elections conclude, the Electoral Commission provides an audited tally that verifies all electronic ballots without compromising individual secret votes.
                                </div>
                            </div>
                            <div class="faq-item border border-gray-200 rounded-2xl overflow-hidden bg-white shadow-sm">
                                <button class="faq-question w-full flex justify-between items-center p-6 text-left hover:bg-gray-50 transition">
                                    <span class="font-bold text-gray-900">Is my vote truly anonymous?</span>
                                    <i class="fas fa-chevron-down text-gray-500 transition-transform"></i>
                                </button>
                                <div class="faq-answer text-gray-700 text-sm leading-relaxed p-6 bg-gray-50 border-t border-gray-100">
                                    Yes. IUEA GuildVote uses one-way encryption to decouple your identity from your choice of candidate. While the system records that "Student X" has voted, the actual vote data is stored in a separate, encrypted vault that never links back to your student profile.
                                </div>
                            </div>
                        </div>
                    </div>
                </div>
            </div>
        </main>
    </div>

    <!-- ========== JAVASCRIPT ========== -->
    <script>
        // ========== MOCK DATA ==========
        // Mock categories created by admin
        const mockCategories = [
            {
                id: 1,
                name: 'Guild President 2025',
                description: 'Elect the next Guild President to lead student affairs.',
                icon: 'fa-user-tie',
                deadline: 'May 5, 2025',
                votes_count: 342
            },
            {
                id: 2,
                name: 'Faculty Representative',
                description: 'Represent the Faculty of Computing & Engineering.',
                icon: 'fa-users',
                deadline: 'May 10, 2025',
                votes_count: 156
            },
            {
                id: 3,
                name: 'Constitutional Referendum',
                description: 'Vote on proposed changes to IUEA Constitution.',
                icon: 'fa-gavel',
                deadline: 'May 15, 2025',
                votes_count: 428
            },
            {
                id: 4,
                name: 'Treasurer Position',
                description: 'Manage Guild finances and budgets.',
                icon: 'fa-coins',
                deadline: 'May 8, 2025',
                votes_count: 89
            },
            {
                id: 5,
                name: 'Academic Affairs Officer',
                description: 'Advocate for students on academic matters.',
                icon: 'fa-book',
                deadline: 'May 12, 2025',
                votes_count: 201
            },
            {
                id: 6,
                name: 'Sports Director',
                description: 'Oversee sports and recreational activities.',
                icon: 'fa-basketball',
                deadline: 'May 7, 2025',
                votes_count: 312
            }
        ];

        // Mock applications (in-memory storage)
        let mockApplications = [
            {
                id: 'app-1',
                categoryId: 1,
                categoryName: 'Guild President 2025',
                status: 'approved',
                submittedDate: '2025-02-01',
                motivation: 'I believe in transparent leadership and student-centered decisions. With my experience in community organizing, I aim to make the Guild more responsive to student needs.',
                manifesto: 'https://example.com/manifesto1'
            },
            {
                id: 'app-2',
                categoryId: 2,
                categoryName: 'Faculty Representative',
                status: 'pending',
                submittedDate: '2025-02-03',
                motivation: 'As a Computing student, I want to bridge the gap between students and faculty administration. I promise to advocate for better lab facilities and course flexibility.',
                manifesto: 'https://example.com/manifesto2'
            },
            {
                id: 'app-3',
                categoryId: 4,
                categoryName: 'Treasurer Position',
                status: 'rejected',
                submittedDate: '2025-01-28',
                motivation: 'With my accounting background, I can ensure financial transparency and efficient budget allocation.',
                manifesto: null
            }
        ];

        // Application history (archived applications from previous years)
        const applicationHistory = [
            { categoryName: 'Guild President 2023', status: 'withdrawn', year: 2023 },
            { categoryName: 'Academic Affairs Officer 2023', status: 'registered', year: 2023 },
            { categoryName: 'Faculty Rep 2022', status: 'rejected', year: 2022 }
        ];

        // ========== APPLICATION MODAL ==========
        const modal = document.getElementById('applicationModal');
        const form = document.getElementById('applicationForm');
        const modalTitle = document.getElementById('modalTitle');
        const modalDeadline = document.getElementById('modalDeadline');
        const motivationInput = document.getElementById('motivationInput');
        const charCount = document.getElementById('charCount');
        let currentSelectedCategory = null;

        // Open modal on Apply button click
        function openApplicationModal(category) {
            currentSelectedCategory = category;
            modalTitle.textContent = `Apply for ${category.name}`;
            modalDeadline.textContent = category.deadline;
            form.reset();
            charCount.textContent = '0';
            modal.classList.remove('hidden');
        }

        // Close modal
        function closeApplicationModal() {
            modal.classList.add('hidden');
            currentSelectedCategory = null;
        }

        document.querySelectorAll('.closeModalBtn').forEach(btn => {
            btn.addEventListener('click', closeApplicationModal);
        });

        modal.addEventListener('click', (e) => {
            if (e.target === modal) closeApplicationModal();
        });

        // Character count for motivation
        motivationInput.addEventListener('input', (e) => {
            charCount.textContent = e.target.value.length;
        });

        // Form submission
        form.addEventListener('submit', (e) => {
            e.preventDefault();
            
            const motivation = document.getElementById('motivationInput').value;
            const manifesto = document.getElementById('manifestoInput').value;
            const eligibility = document.getElementById('eligibilityCheckbox').checked;

            if (!currentSelectedCategory || !motivation || !eligibility) {
                alert('Please fill all required fields.');
                return;
            }

            // Create new application
            const newApplication = {
                id: 'app-' + Date.now(),
                categoryId: currentSelectedCategory.id,
                categoryName: currentSelectedCategory.name,
                status: 'pending',
                submittedDate: new Date().toISOString().split('T')[0],
                motivation: motivation,
                manifesto: manifesto || null
            };

            // Add to applications array
            mockApplications.push(newApplication);

            // Save to localStorage
            localStorage.setItem('studentApplications', JSON.stringify(mockApplications));

            // Refresh UI
            renderApplications();
            renderCategories();
            renderHistory();

            // Close modal
            closeApplicationModal();
        });

        // ========== RENDER CATEGORIES ==========
        function renderCategories() {
            const grid = document.getElementById('categoriesGrid');
            grid.innerHTML = '';

            mockCategories.forEach(category => {
                const isApplied = mockApplications.some(app => app.categoryId === category.id);
                
                const card = document.createElement('div');
                card.className = 'border border-gray-200 rounded-2xl p-6 hover:border-primary transition hover:shadow-md bg-white shadow-sm';
                card.innerHTML = `
                    <div class="flex items-start justify-between mb-4">
                        <div class="w-12 h-12 bg-red-50 rounded-xl flex items-center justify-center text-primary text-2xl">
                            <i class="fas ${category.icon}"></i>
                        </div>
                        ${isApplied ? '<span class="bg-blue-100 text-blue-700 text-xs px-3 py-1 rounded-full font-semibold">Applied</span>' : ''}
                    </div>
                    <h4 class="font-bold text-gray-900 text-lg mb-2">${category.name}</h4>
                    <p class="text-sm text-gray-600 mb-4">${category.description}</p>
                    <div class="flex items-center text-xs text-gray-500 mb-6 gap-4">
                        <span><i class="fas fa-calendar mr-1"></i>Apply by ${category.deadline}</span>
                        <span><i class="fas fa-vote-yea mr-1"></i>${category.votes_count} votes</span>
                    </div>
                    <button class="w-full ${isApplied ? 'bg-gray-100 text-gray-400 cursor-not-allowed' : 'bg-primary text-white hover:bg-primary-dark'} py-2.5 rounded-lg font-bold transition-colors text-sm" 
                            ${isApplied ? 'disabled' : 'onclick="openApplicationModal(' + JSON.stringify(category).replace(/"/g, '&quot;') + ')"'}
                    >
                        ${isApplied ? '✓ Applied' : 'Apply now'}
                    </button>
                `;
                grid.appendChild(card);
            });
        }

        // ========== RENDER MY APPLICATIONS ==========
        function renderApplications() {
            const list = document.getElementById('applicationsList');
            const empty = document.getElementById('emptyApplications');

            if (mockApplications.length === 0) {
                list.innerHTML = '';
                empty.style.display = 'block';
                return;
            }

            empty.style.display = 'none';
            list.innerHTML = '';

            mockApplications.forEach(app => {
                const statusColors = {
                    pending: { bg: 'bg-orange-50', border: 'border-orange-200', text: 'text-orange-700', label: 'Pending' },
                    approved: { bg: 'bg-green-50', border: 'border-green-200', text: 'text-green-700', label: 'Approved' },
                    rejected: { bg: 'bg-red-50', border: 'border-red-200', text: 'text-red-700', label: 'Rejected' },
                    registered: { bg: 'bg-blue-50', border: 'border-blue-200', text: 'text-blue-700', label: 'Registered' }
                };
                const colors = statusColors[app.status];

                const appDate = new Date(app.submittedDate).toLocaleDateString('en-US', { 
                    year: 'numeric', month: '2-digit', day: '2-digit' 
                });

                const card = document.createElement('div');
                card.className = `border border-gray-200 rounded-2xl p-6 flex items-center justify-between ${colors.bg} ${colors.border} bg-white`;
                card.innerHTML = `
                    <div class="flex-1">
                        <h4 class="font-bold text-gray-900">${app.categoryName}</h4>
                        <p class="text-sm text-gray-600 mt-1">Applied: ${appDate}</p>
                        <p class="text-xs text-gray-500 mt-1 italic">"${app.motivation.substring(0, 60)}..."</p>
                    </div>
                    <span class="${colors.bg} ${colors.text} border ${colors.border} px-4 py-2 rounded-lg font-bold text-sm whitespace-nowrap ml-4">
                        ${colors.label}
                    </span>
                `;
                list.appendChild(card);
            });
        }

        // ========== RENDER APPLICATION HISTORY ==========
        function renderHistory() {
            const timeline = document.getElementById('historyTimeline');
            const empty = document.getElementById('emptyHistory');

            if (applicationHistory.length === 0 && mockApplications.filter(app => app.status === 'registered').length === 0) {
                timeline.innerHTML = '';
                empty.style.display = 'block';
                return;
            }

            empty.style.display = 'none';

            // Combine current registered applications with history
            const registered = mockApplications.filter(app => app.status === 'registered').map(app => ({
                categoryName: app.categoryName,
                year: new Date().getFullYear(),
                status: 'registered'
            }));

            const allHistory = [...registered, ...applicationHistory];

            timeline.innerHTML = allHistory.map(item => `
                <div class="flex gap-4">
                    <div class="relative">
                        <div class="w-3 h-3 bg-primary rounded-full mt-2"></div>
                        <div class="absolute left-1/2 top-5 w-0.5 h-12 bg-gray-200 -translate-x-1/2"></div>
                    </div>
                    <div>
                        <p class="font-bold text-gray-900">${item.categoryName}</p>
                        <p class="text-xs text-gray-500">${item.year} · 
                            <span class="px-2 py-1 rounded-full text-[10px] font-bold 
                                ${item.status === 'registered' ? 'bg-green-100 text-green-700' : 
                                  item.status === 'withdrawn' ? 'bg-gray-100 text-gray-700' : 
                                  'bg-red-100 text-red-700'}">
                                ${item.status.charAt(0).toUpperCase() + item.status.slice(1)}
                            </span>
                        </p>
                    </div>
                </div>
            `).join('');
        }

        // ========== INITIALIZE VOTER REGISTRATION PAGE ==========
        function initializeVoterRegistration() {
            // Load applications from localStorage if available
            const saved = localStorage.getItem('studentApplications');
            if (saved) {
                try {
                    mockApplications = JSON.parse(saved);
                } catch (e) {
                    console.log('Using default mock applications');
                }
            }

            // Initial render
            renderCategories();
            renderApplications();
            renderHistory();
        }

        // ========== MAIN APPLICATION LOGIC ==========
        (function() {
            // Mobile sidebar toggle
            const sidebar = document.getElementById('sidebar');
            const backdrop = document.getElementById('sidebarBackdrop');
            const mobileBtn = document.getElementById('mobileMenuBtn');

            function openSidebar() {
                sidebar.classList.remove('-translate-x-full');
                backdrop.classList.remove('hidden');
                document.body.classList.add('overflow-hidden', 'md:overflow-auto');
            }
            function closeSidebar() {
                sidebar.classList.add('-translate-x-full');
                backdrop.classList.add('hidden');
                document.body.classList.remove('overflow-hidden', 'md:overflow-auto');
            }

            if (mobileBtn) mobileBtn.addEventListener('click', openSidebar);
            if (backdrop) backdrop.addEventListener('click', closeSidebar);

            // Page switching
            const pages = {
                dashboard: document.getElementById('page-dashboard'),
                register: document.getElementById('page-register'),
                vote: document.getElementById('page-vote'),
                results: document.getElementById('page-results'),
                profile: document.getElementById('page-profile'),
                help: document.getElementById('page-help')
            };
            const sidebarLinks = document.querySelectorAll('.sidebar-link[data-page]');
            const pageTitle = document.getElementById('pageTitle');

            function activatePage(pageId) {
                Object.values(pages).forEach(p => p?.classList.remove('active-page'));
                if (pages[pageId]) {
                    pages[pageId].classList.add('active-page');
                    
                    // Initialize voter registration when switching to register page
                    if (pageId === 'register') {
                        initializeVoterRegistration();
                    }
                }
                
                sidebarLinks.forEach(link => {
                    link.classList.remove('active');
                    if (link.dataset.page === pageId) {
                        link.classList.add('active');
                    }
                });
                
                const titles = {
                    dashboard: 'Dashboard',
                    register: 'Voter Registration',
                    vote: 'Vote now',
                    results: 'Live Results',
                    profile: 'Profile & Security',
                    help: 'Help & support'
                };
                if (pageTitle) pageTitle.textContent = titles[pageId] || 'Dashboard';
                
                if (window.innerWidth < 768) closeSidebar();
                
                // Save current page state
                localStorage.setItem('last_page', pageId);
            }

            sidebarLinks.forEach(link => {
                link.addEventListener('click', function(e) {
                    e.preventDefault();
                    const page = this.dataset.page;
                    if (page) activatePage(page);
                });
            });

            // Handle direct links to pages (from buttons inside the dashboard)
            document.querySelectorAll('[data-page]').forEach(el => {
                if(!el.classList.contains('sidebar-link')) {
                   el.addEventListener('click', function(e) {
                        e.preventDefault();
                        const page = this.dataset.page;
                        if (page) activatePage(page);
                    });
                }
            });

            // Default to dashboard or last saved page
            const lastPage = localStorage.getItem('last_page') || 'dashboard';
            activatePage(lastPage);

            // FAQ Accordion
            const faqItems = document.querySelectorAll('.faq-item');
            faqItems.forEach(item => {
                const question = item.querySelector('.faq-question');
                question.addEventListener('click', () => {
                    item.classList.toggle('active');
                });
            });
        })();
    </script>

    <!-- ========== VOTER REGISTRATION PAGE SCRIPT ========== -->
    <script>
        // Include voter registration functionality inline
        // Copy the content of resources/js/pages/voter-registration.js here
        // OR load it as a separate file:
    </script>
    <script src="{{ asset('js/pages/voter-registration.js') }}"></script>
</body>
</html>
