@php
    $initials = collect(explode(' ', $user->name))->map(fn($n) => strtoupper(substr($n, 0, 1)))->take(2)->join('');
@endphp
<!DOCTYPE html>
<html lang="en">
<head>
    <meta charset="UTF-8">
    <meta name="viewport" content="width=device-width, initial-scale=1.0">
    <title>IUEA GuildVote • Student Voting</title>
    <script src="https://cdn.tailwindcss.com"></script>
    <link rel="stylesheet" href="https://cdnjs.cloudflare.com/ajax/libs/font-awesome/6.4.0/css/all.min.css">
    <link rel="preconnect" href="https://fonts.googleapis.com">
    <link rel="preconnect" href="https://fonts.gstatic.com" crossorigin>
    <link href="https://fonts.googleapis.com/css2?family=Inter:ital,opsz,wght@0,14..32,100..900;1,14..32,100..900&display=swap" rel="stylesheet">
    <style>
        * {
            font-family: 'Inter', system-ui, sans-serif;
        }

        :root {
            --primary: #8B0000;
            --primary-dark: #6B0000;
            --primary-light: #A52A2A;
            --primary-soft: rgba(139, 0, 0, 0.05);
        }

        body {
            background: linear-gradient(145deg, #f8fafc 0%, #f1f5f9 100%);
            min-height: 100vh;
        }

        .glass-header {
            background: rgba(255, 255, 255, 0.8);
            backdrop-filter: blur(12px);
            border-bottom: 1px solid rgba(0, 0, 0, 0.05);
        }

        .btn-vote {
            background: var(--primary);
            color: white;
            font-weight: 600;
            padding: 0.75rem 1.5rem;
            border-radius: 2rem;
            transition: all 0.2s ease;
            box-shadow: 0 4px 10px -2px rgba(139, 0, 0, 0.3);
            border: none;
            cursor: pointer;
            display: inline-flex;
            align-items: center;
            justify-content: center;
            gap: 0.5rem;
            width: 100%;
        }

        .btn-vote:hover:not(:disabled) {
            background: var(--primary-dark);
            transform: translateY(-2px);
            box-shadow: 0 10px 20px -5px rgba(139, 0, 0, 0.4);
        }

        .btn-vote:disabled {
            background: #d1d5db;
            box-shadow: none;
            cursor: not-allowed;
            opacity: 0.6;
        }

        .candidate-card {
            background: white;
            border-radius: 2rem;
            padding: 1.75rem 1.5rem;
            transition: all 0.25s cubic-bezier(0.4, 0, 0.2, 1);
            border: 1px solid #e9edf2;
            box-shadow: 0 10px 25px -8px rgba(0, 0, 0, 0.06);
            display: flex;
            flex-direction: column;
            height: 100%;
        }

        .candidate-card:hover {
            border-color: var(--primary);
            box-shadow: 0 20px 30px -12px rgba(139, 0, 0, 0.2);
            transform: translateY(-4px);
        }

        .candidate-card.voted {
            background: linear-gradient(145deg, #fff, #fff5f5);
            border-color: var(--primary);
            border-width: 2px;
        }

        .avatar-circle {
            width: 380px;
            height: 380px;
            border-radius: 2.5rem;
            object-fit: cover;
            border: 1px solid white;
            box-shadow: 0 10px 25px -10px rgba(139, 0, 0, 0.4);
            margin: 0 auto 1.5rem;
        }

        .category-badge {
            background: var(--primary-soft);
            color: var(--primary);
            font-size: 0.75rem;
            font-weight: 600;
            padding: 0.25rem 1rem;
            border-radius: 999px;
            display: inline-block;
        }

        .history-item {
            background: white;
            border-radius: 1.5rem;
            padding: 1.25rem 1.5rem;
            transition: all 0.2s ease;
            border: 1px solid #edf2f7;
            display: flex;
            align-items: center;
            gap: 1rem;
            box-shadow: 0 2px 8px rgba(0,0,0,0.02);
        }

        .history-item:hover {
            border-color: var(--primary);
            box-shadow: 0 8px 18px -8px rgba(139,0,0,0.12);
            transform: translateX(5px);
        }

        .badge-outcome {
            background: #e6f7e6;
            color: #0e6245;
            font-size: 0.7rem;
            font-weight: 600;
            padding: 0.25rem 0.75rem;
            border-radius: 999px;
            display: inline-flex;
            align-items: center;
            gap: 0.25rem;
            border: 1px solid #b8e0c0;
        }

        .result-bar {
            height: 6px;
            background: #e2e8f0;
            border-radius: 999px;
            overflow: hidden;
            margin: 0.5rem 0 0.25rem;
        }

        .result-fill {
            height: 100%;
            background: var(--primary);
            border-radius: 999px;
            width: 0%;
            transition: width 0.5s ease;
        }

        .toast {
            position: fixed;
            bottom: 2rem;
            right: 2rem;
            background: white;
            color: #1e293b;
            padding: 1rem 1.5rem;
            border-radius: 999px;
            box-shadow: 0 20px 30px -12px rgba(0,0,0,0.25);
            border-left: 5px solid var(--primary);
            font-weight: 500;
            display: flex;
            align-items: center;
            gap: 0.75rem;
            z-index: 50;
            opacity: 0;
            transform: translateY(1rem);
            transition: opacity 0.3s ease, transform 0.3s ease;
            pointer-events: none;
        }

        .toast.show {
            opacity: 1;
            transform: translateY(0);
        }

        .empty-state {
            background: #f9fafc;
            border-radius: 2rem;
            padding: 3rem 2rem;
            text-align: center;
            border: 2px dashed #d4d8dd;
        }

        .dropdown-menu {
            display: none;
            position: absolute;
            right: 0;
            top: 100%;
            background: white;
            border: 1px solid #e9edf2;
            border-radius: 1rem;
            box-shadow: 0 10px 25px -8px rgba(0, 0, 0, 0.1);
            z-index: 50;
            min-width: 200px;
            margin-top: 0.5rem;
        }

        .dropdown-menu.show {
            display: block;
        }
    </style>
</head>
    <!-- Toast Container -->
    <div id="toast" class="toast">
        <i class="fas fa-check-circle text-[#8B0000]"></i>
        <span id="toast-message"></span>
    </div>

    <!-- Header glass -->
    <header class="glass-header sticky top-0 z-40">
        <div class="max-w-7xl mx-auto px-4 sm:px-6 lg:px-8 py-4">
            <div class="flex items-center justify-between">
                <div class="flex items-center gap-4">
                    <img src="https://iuea.ac.ug/sitepad-data/uploads/2024/07/IUEA-Logo-official-1.png" 
                         alt="IUEA Logo" 
                         class="h-12 w-auto object-contain">
                    <div class="h-6 w-px bg-gray-300"></div>
                    <div>
                        <span class="text-sm font-medium text-gray-500">GuildVote</span>
                        <span class="text-sm font-semibold text-gray-800 ml-2 bg-gray-100 px-3 py-1 rounded-full">2026 Elections</span>
                    </div>
                </div>

                <div class="flex items-center gap-4 relative" id="userDropdownContainer">
                    <div class="text-right hidden md:block">
                        <p class="text-sm font-semibold text-gray-800">{{ $user->name }}</p>
                        <p class="text-xs text-gray-500">IUEA · Student</p>
                    </div>
                    <button id="userMenuBtn" class="w-11 h-11 rounded-full overflow-hidden ring-2 ring-white shadow-md focus:outline-none">
                        <img src="{{ $user->profile_photo_url }}" class="w-full h-full object-cover">
                    </button>
                    
                    <div id="userDropdown" class="dropdown-menu">
                        <div class="p-4 border-b border-gray-100">
                            <p class="text-sm font-bold text-gray-800">{{ $user->name }}</p>
                            <p class="text-xs text-gray-500">{{ $user->email }}</p>
                        </div>
                        <div class="py-2">
                            <form action="{{ route('logout') }}" method="POST" id="logout-form">
                                @csrf
                                <button type="submit" class="w-full flex items-center gap-3 px-4 py-2 text-sm text-red-600 hover:bg-red-50">
                                    <i class="fas fa-sign-out-alt w-4"></i> Logout
                                </button>
                            </form>
                        </div>
                    </div>
                </div>
            </div>
        </div>
    </header>

    <main class="max-w-7xl mx-auto px-4 sm:px-6 lg:px-8 py-12 pb-24">
        @if(session('success'))
            <div class="mb-8 p-4 bg-green-50 border border-green-200 text-green-700 rounded-2xl flex items-center gap-3">
                <i class="fas fa-check-circle"></i>
                {{ session('success') }}
            </div>
        @endif

        @if(session('error'))
            <div class="mb-8 p-4 bg-red-50 border border-red-200 text-red-700 rounded-2xl flex items-center gap-3">
                <i class="fas fa-exclamation-circle"></i>
                {{ session('error') }}
            </div>
        @endif

        <!-- Page Header -->
        <div class="flex flex-col sm:flex-row sm:items-center justify-between gap-4 mb-10">
            <div>
                <h1 class="text-3xl font-bold text-gray-900">Choose your representatives</h1>
                <p class="text-gray-500 mt-1">Vote for candidates in each category. Only one vote per election category.</p>
            </div>
            <div class="flex flex-wrap items-center gap-4">
                @if($electionEndTime)
                <div class="bg-gradient-to-r from-purple-600 to-indigo-600 rounded-2xl px-6 py-3 shadow-lg shadow-purple-900/20 flex items-center gap-4 text-white">
                    <div class="flex flex-col">
                        <span class="text-[10px] font-black uppercase tracking-widest opacity-70">Polls Close In</span>
                        <div class="flex items-center gap-2 font-black text-xl" id="electionCountdown">
                            <span id="cd-days">00</span><span class="text-xs opacity-50">d</span>
                            <span id="cd-hours">00</span><span class="text-xs opacity-50">h</span>
                            <span id="cd-minutes">00</span><span class="text-xs opacity-50">m</span>
                            <span id="cd-seconds">00</span><span class="text-xs opacity-50">s</span>
                        </div>
                    </div>
                    <i class="fas fa-clock text-2xl opacity-50"></i>
                </div>
                @endif
                <div class="bg-white rounded-2xl px-5 py-3 shadow-sm border border-gray-200 flex items-center gap-4">
                    <i class="fas fa-users text-[#8B0000]"></i>
                    <div class="flex flex-col">
                        <span class="text-[10px] font-black uppercase tracking-widest text-gray-400">Total Participation</span>
                        <span class="text-2xl font-bold text-[#8B0000]" id="globalTurnout">...%</span>
                    </div>
                </div>
                <a href="{{ route('dashboard.live-results') }}" target="_blank" class="bg-gradient-to-br from-[#8B0000] to-[#B22222] text-white rounded-2xl px-6 py-3 shadow-lg shadow-red-900/20 flex items-center gap-3 hover:scale-105 transition-transform">
                    <div class="relative">
                        <i class="fas fa-chart-bar text-xl"></i>
                        <span class="absolute -top-1 -right-1 w-2 h-2 bg-green-400 rounded-full animate-pulse"></span>
                    </div>
                    <div class="flex flex-col">
                        <span class="text-[10px] font-black uppercase tracking-widest opacity-70 leading-none mb-1">Live Results</span>
                        <span class="text-sm font-bold">Watch Count</span>
                    </div>
                </a>
            </div>
        </div>

        <!-- Categories List (injected by JS) -->
        @if($isEnded)
            <section class="mb-20">
                <div class="flex items-center gap-3 mb-8">
                    <div class="w-10 h-10 bg-yellow-400/10 rounded-2xl flex items-center justify-center">
                        <i class="fas fa-crown text-yellow-600"></i>
                    </div>
                    <h2 class="text-2xl font-bold text-gray-900">Official Winners & Results</h2>
                </div>
                
                <div class="grid grid-cols-1 md:grid-cols-2 lg:grid-cols-3 gap-8">
                    @foreach($results as $cat)
                        @php 
                            $winner = $cat->candidates->sortByDesc(function($c) { return $c->total_votes; })->first(); 
                        @endphp
                        @if($winner)
                        <div class="bg-white rounded-[2.5rem] p-8 border-2 border-yellow-400 shadow-xl shadow-yellow-900/5 relative overflow-hidden">
                            <div class="absolute top-0 right-0 bg-yellow-400 text-yellow-900 px-6 py-2 rounded-bl-3xl font-black text-xs uppercase tracking-tighter">
                                Winner
                            </div>
                            <div class="flex flex-col items-center text-center">
                                <img src="{{ $winner->photo_path ? (filter_var($winner->photo_path, FILTER_VALIDATE_URL) ? $winner->photo_path : Storage::url($winner->photo_path)) : 'https://ui-avatars.com/api/?name='.urlencode($winner->name).'&background=8B0000&color=fff' }}" class="w-32 h-32 rounded-3xl object-cover mb-4 border-4 border-yellow-100">
                                <h3 class="font-black text-gray-900 text-xl">{{ $winner->name }}</h3>
                                <p class="text-primary font-bold text-sm uppercase mb-4">{{ $cat->name }}</p>
                                <div class="bg-gray-50 rounded-2xl px-6 py-3 w-full">
                                    <p class="text-[10px] text-gray-400 font-bold uppercase">Final Count</p>
                                    <p class="text-2xl font-black text-gray-900">{{ $winner->total_votes }} votes</p>
                                </div>
                            </div>
                        </div>
                        @endif
                    @endforeach
                </div>
            </section>
        @endif
        <div id="categoriesContainer" class="space-y-20"></div>

        <!-- History Section -->
        <section class="mt-28 pt-12 border-t border-gray-200">
            <div class="flex items-center gap-3 mb-8">
                <div class="w-10 h-10 bg-[#8B0000]/10 rounded-2xl flex items-center justify-center">
                    <i class="fas fa-timeline text-[#8B0000]"></i>
                </div>
                <h2 class="text-2xl font-bold text-gray-900">My Voting History</h2>
            </div>

            <div id="historyContainer" class="space-y-3"></div>
            <div id="emptyHistory" class="empty-state hidden">
                <i class="fas fa-box-open text-4xl text-gray-300 mb-4"></i>
                <p class="text-gray-500 text-lg">No votes cast yet.</p>
                <p class="text-gray-400 text-sm mt-2">Your history will appear as soon as you have cast your vote.</p>
            </div>
        </section>
    </main>

    <!-- Hidden form for voting -->
    <form id="voteForm" action="{{ route('dashboard.vote') }}" method="POST" style="display: none;">
        @csrf
        <input type="hidden" name="candidate_id" id="candidateInput">
    </form>

    <!-- Toast notification -->
    <div id="toast" class="toast">
        <i class="fas fa-check-circle text-[#8B0000]"></i>
        <span id="toastMessage">Vote recorded</span>
    </div>

    <script>
        (function() {
            // ========== DATA FROM LARAVEL ==========
            const categories = [
                @foreach($categories as $category)
                {
                    id: {{ $category->id }},
                    name: '{{ str_replace("'", "\'", $category->name) }}',
                    candidates: [
                        @foreach($category->candidates as $candidate)
                        { 
                            id: {{ $candidate->id }}, 
                            name: '{{ str_replace("'", "\'", $candidate->name) }}', 
                            photo: '{{ $candidate->photo_path ? (filter_var($candidate->photo_path, FILTER_VALIDATE_URL) ? $candidate->photo_path : Storage::url($candidate->photo_path)) : "https://ui-avatars.com/api/?name=".urlencode($candidate->name)."&background=8B0000&color=fff" }}', 
                            slogan: '{{ str_replace(["\r", "\n", "'"], ["", "", "\'"], $candidate->biography ?? "Leadership for all") }}',
                            faculty: '{{ str_replace("'", "\'", $candidate->faculty ?? "N/A") }}',
                            studentClass: '{{ str_replace("'", "\'", $candidate->student_class ?? "N/A") }}'
                        },
                        @endforeach
                    ],
                    totalVotes: {{ $category->votes_count ?? 1 }}
                },
                @endforeach
            ];

            let voteHistory = [
                @foreach($myVotes as $vote)
                { 
                    date: '{{ $vote->created_at->toDateString() }}', 
                    categoryId: {{ $vote->category_id }}, 
                    categoryName: '{{ str_replace("'", "\'", $vote->category->name) }}', 
                    candidateName: '{{ str_replace("'", "\'", $vote->candidate->name) }}' 
                },
                @endforeach
            ];

            let votedCandidateIds = new Set([@foreach($myVotes as $vote) {{ $vote->candidate_id }}, @endforeach]);
            let votedCategoryIds = [@foreach($votedCategoryIds as $id) {{ $id }}, @endforeach];

            // ========== UTILITIES ==========
            function formatRelativeDate(dateStr) {
                const d = new Date(dateStr);
                const now = new Date();
                const diffTime = now - d;
                const diffDays = Math.floor(diffTime / (1000 * 60 * 60 * 24));
                if (diffDays === 0) return "Today";
                if (diffDays === 1) return "Yesterday";
                if (diffDays < 7) return `${diffDays} days ago`;
                return d.toLocaleDateString('en-GB', { day: 'numeric', month: 'short', year: 'numeric' });
            }

            function showToast(message) {
                const toast = document.getElementById('toast');
                document.getElementById('toastMessage').textContent = message;
                toast.classList.add('show');
                setTimeout(() => toast.classList.remove('show'), 2500);
            }

            // ========== DYNAMIC RENDERING ==========
            function render() {
                renderCategories();
                renderHistory();
                updateGlobalTurnout();
            }

            function renderCategories() {
                const container = document.getElementById('categoriesContainer');
                if (!container) return;
                container.innerHTML = '';

                if (categories.length === 0) {
                    container.innerHTML = `
                        <div class="empty-state">
                            <i class="fas fa-calendar-times text-4xl text-gray-300 mb-4"></i>
                            <p class="text-gray-500 text-lg">No active elections at the moment.</p>
                            <p class="text-gray-400 text-sm mt-2">Please come back later once the voting opens.</p>
                        </div>
                    `;
                    return;
                }

                categories.forEach(cat => {
                    const section = document.createElement('section');
                    section.className = 'space-y-6';

                    // Category Header
                    const header = document.createElement('div');
                    header.className = 'flex items-center gap-4 mb-4';
                    header.innerHTML = `
                        <h2 class="text-2xl font-bold text-gray-800">${cat.name}</h2>
                        <span class="category-badge">${cat.candidates.length} candidates</span>
                    `;
                    section.appendChild(header);

                    // Candidates Grid
                    const grid = document.createElement('div');
                    grid.className = 'grid grid-cols-1 md:grid-cols-2 lg:grid-cols-3 gap-7';

                    const isCategoryVoted = votedCategoryIds.includes(cat.id);

                    cat.candidates.forEach(candidate => {
                        const hasThisCandidateVoted = votedCandidateIds.has(candidate.id);
                        
                        const card = document.createElement('div');
                        card.className = `candidate-card ${hasThisCandidateVoted ? 'voted' : ''}`;

                        card.innerHTML = `
                            <div class="flex flex-col h-full">
                                <div class="flex justify-center">
                                    <img src="${candidate.photo}" alt="${candidate.name}" class="avatar-circle">
                                </div>
                                <div class="text-center flex-1">
                                    <h3 class="text-lg font-bold text-gray-800 mb-0.5">${candidate.name}</h3>
                                    <div class="flex flex-wrap justify-center gap-2 mb-3">
                                        <span class="text-[10px] font-bold uppercase tracking-widest text-[#8B0000] bg-red-50 px-2 py-0.5 rounded-md border border-red-100">${candidate.faculty}</span>
                                        <span class="text-[10px] font-bold uppercase tracking-widest text-gray-500 bg-gray-50 px-2 py-0.5 rounded-md border border-gray-100">${candidate.studentClass}</span>
                                    </div>
                                    <p class="text-sm text-gray-500 italic px-2">“${candidate.slogan}”</p>
                                </div>
                                <button 
                                    class="btn-vote mt-6"
                                    data-candidate-id="${candidate.id}"
                                    ${isCategoryVoted ? 'disabled' : ''}
                                >
                                    ${hasThisCandidateVoted ? '<i class="fas fa-check-circle"></i> Your choice' : (isCategoryVoted ? 'Chosen' : '<i class="fas fa-vote-yea"></i> Vote')}
                                </button>
                            </div>
                        `;
                        grid.appendChild(card);
                    });

                    section.appendChild(grid);
                    container.appendChild(section);
                });

                document.querySelectorAll('.btn-vote:not(:disabled)').forEach(btn => {
                    btn.addEventListener('click', onVoteClick);
                });
            }

            function onVoteClick(e) {
                const btn = e.currentTarget;
                const candidateId = btn.dataset.candidateId;
                
                // Confirm before voting
                if (!confirm("Are you sure you want to confirm your vote? This action is irreversible.")) {
                    return;
                }

                // Use the hidden form to submit
                document.getElementById('candidateInput').value = candidateId;
                document.getElementById('voteForm').submit();
            }

            function renderHistory() {
                const container = document.getElementById('historyContainer');
                const emptyDiv = document.getElementById('emptyHistory');

                if (voteHistory.length === 0) {
                    container.innerHTML = '';
                    emptyDiv.classList.remove('hidden');
                    return;
                }

                emptyDiv.classList.add('hidden');
                container.innerHTML = voteHistory.map(entry => `
                    <div class="history-item">
                        <div class="w-10 h-10 rounded-full bg-[#8B0000]/10 flex items-center justify-center text-[#8B0000] flex-shrink-0">
                            <i class="fas fa-check-to-slot"></i>
                        </div>
                        <div class="flex-1 min-w-0">
                            <p class="font-semibold text-gray-800">${entry.candidateName}</p>
                            <p class="text-sm text-gray-500">${entry.categoryName}</p>
                        </div>
                        <div class="text-right flex-shrink-0">
                            <span class="text-sm text-gray-600 whitespace-nowrap"><i class="far fa-calendar-alt mr-1 text-[#8B0000]"></i>${formatRelativeDate(entry.date)}</span>
                            <div class="badge-outcome mt-1">
                                <i class="fas fa-check-circle text-xs"></i> Confirmed
                            </div>
                        </div>
                    </div>
                `).join('');
            }

            function updateGlobalTurnout() {
                const turnout = {{ $totalCategories > 0 ? round((count($votedCategoryIds) / $totalCategories) * 100) : 0 }};
                document.getElementById('globalTurnout').textContent = turnout + '%';
            }

            // ========== COUNTDOWN LOGIC ==========
            function initCountdown() {
                const endTimeStr = '{{ $electionEndTime }}';
                if (!endTimeStr) return;

                const endTime = new Date(endTimeStr).getTime();
                
                const timer = setInterval(() => {
                    const now = new Date().getTime();
                    const distance = endTime - now;

                    if (distance < 0) {
                        clearInterval(timer);
                        document.getElementById('electionCountdown').innerHTML = "POLLS CLOSED";
                        // Disable all vote buttons if they aren't already
                        document.querySelectorAll('.btn-vote').forEach(btn => btn.disabled = true);
                        return;
                    }

                    const days = Math.floor(distance / (1000 * 60 * 60 * 24));
                    const hours = Math.floor((distance % (1000 * 60 * 60 * 24)) / (1000 * 60 * 60));
                    const minutes = Math.floor((distance % (1000 * 60 * 60)) / (1000 * 60));
                    const seconds = Math.floor((distance % (1000 * 60)) / 1000);

                    const d_el = document.getElementById('cd-days');
                    const h_el = document.getElementById('cd-hours');
                    const m_el = document.getElementById('cd-minutes');
                    const s_el = document.getElementById('cd-seconds');

                    if (d_el) d_el.textContent = days.toString().padStart(2, '0');
                    if (h_el) h_el.textContent = hours.toString().padStart(2, '0');
                    if (m_el) m_el.textContent = minutes.toString().padStart(2, '0');
                    if (s_el) s_el.textContent = seconds.toString().padStart(2, '0');
                }, 1000);
            }

            function showToast(message, type = 'success') {
                const toast = document.getElementById('toast');
                const toastMsg = document.getElementById('toast-message');
                const icon = toast.querySelector('i');

                toastMsg.textContent = message;
                
                if (type === 'error') {
                    toast.style.borderLeftColor = '#ef4444';
                    icon.className = 'fas fa-exclamation-circle text-red-500';
                } else if (type === 'warning') {
                    toast.style.borderLeftColor = '#f59e0b';
                    icon.className = 'fas fa-exclamation-triangle text-amber-500';
                } else {
                    toast.style.borderLeftColor = 'var(--primary)';
                    icon.className = 'fas fa-check-circle text-[#8B0000]';
                }

                toast.classList.add('show');
                setTimeout(() => toast.classList.remove('show'), 5000);
            }

            // ========== USER MENU DROPDOWN ==========
            document.addEventListener('DOMContentLoaded', () => {
                const menuBtn = document.getElementById('userMenuBtn');
                const dropdown = document.getElementById('userDropdown');
                
                if (menuBtn) {
                    menuBtn.addEventListener('click', (e) => {
                        e.stopPropagation();
                        dropdown.classList.toggle('show');
                    });
                }

                document.addEventListener('click', () => {
                    if (dropdown) dropdown.classList.remove('show');
                });

                @if(session('success')) showToast("{{ session('success') }}"); @endif
                @if(session('error')) showToast("{{ session('error') }}", 'error'); @endif
                @if(session('warning')) showToast("{{ session('warning') }}", 'warning'); @endif

                render();
                initCountdown();
            });
        })();
    </script>
</body>
</html>
