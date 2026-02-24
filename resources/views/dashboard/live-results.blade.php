<!DOCTYPE html>
<html lang="en">

<head>
    <meta charset="UTF-8">
    <meta name="viewport" content="width=device-width, initial-scale=1.0">
    <title>IUEA GuildVote • Live Results</title>
    <script src="https://cdn.tailwindcss.com"></script>
    <link rel="stylesheet" href="https://cdnjs.cloudflare.com/ajax/libs/font-awesome/6.4.0/css/all.min.css">
    <link rel="preconnect" href="https://fonts.googleapis.com">
    <link rel="preconnect" href="https://fonts.gstatic.com" crossorigin>
    <link href="https://fonts.googleapis.com/css2?family=Inter:ital,opsz,wght@0,14..32,100..900;1,14..32,100..900&display=swap" rel="stylesheet">
    <style>
        * { font-family: 'Inter', system-ui, sans-serif; }
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
        .stat-card {
            background: white;
            border-radius: 1.5rem;
            padding: 1.5rem;
            box-shadow: 0 8px 20px -8px rgba(0, 0, 0, 0.08);
            border: 1px solid #eef2f6;
        }
        .category-card {
            background: white;
            border-radius: 2rem;
            padding: 2rem;
            box-shadow: 0 20px 35px -12px rgba(0, 0, 0, 0.15);
            border: 1px solid #eef2f6;
            transition: opacity 0.5s ease-in-out;
        }
        .category-card.fade-out { opacity: 0; }
        .progress-bar {
            height: 0.75rem;
            background: #e2e8f0;
            border-radius: 999px;
            overflow: hidden;
            margin: 0.5rem 0 0.25rem;
        }
        .progress-fill {
            height: 100%;
            background: var(--primary);
            border-radius: 999px;
            transition: width 0.5s ease;
        }
        .candidate-photo {
            width: 64px;
            height: 64px;
            border-radius: 50%;
            object-fit: cover;
            border: 3px solid white;
            box-shadow: 0 8px 16px -4px rgba(139, 0, 0, 0.2);
            pointer-events: none;
            -webkit-user-drag: none;
            user-select: none;
        }
        .nav-dot {
            width: 12px;
            height: 12px;
            border-radius: 50%;
            background: #d1d5db;
            transition: all 0.3s;
            cursor: pointer;
        }
        .nav-dot.active {
            background: var(--primary);
            transform: scale(1.3);
        }
        .nav-dot:hover { background: var(--primary-light); }
        .timer-bar {
            height: 4px;
            background: #e2e8f0;
            border-radius: 999px;
            overflow: hidden;
            width: 100%;
        }
        .timer-fill {
            height: 100%;
            background: var(--primary);
            width: 100%;
            transition: width linear;
        }
        .footer-links a {
            color: #6b7280;
            transition: color 0.2s;
        }
        .footer-links a:hover { color: var(--primary); }
    </style>
</head>

<body>
    <!-- Header -->
    <header class="glass-header sticky top-0 z-40">
        <div class="max-w-7xl mx-auto px-4 sm:px-6 lg:px-8 py-4">
            <div class="flex items-center justify-between">
                <div class="flex items-center gap-4">
                    <img src="https://iuea.ac.ug/sitepad-data/uploads/2024/07/IUEA-Logo-official-1.png" alt="IUEA Logo" class="h-12 w-auto object-contain">
                    <span class="text-xl font-bold text-gray-800">GuildVote</span>
                </div>
                <div class="flex items-center gap-4">
                    <a href="{{ route('dashboard.index') }}" class="text-gray-500 hover:text-red-700 font-semibold text-sm transition mr-4">
                        <i class="fas fa-arrow-left mr-1"></i> Dashboard
                    </a>
                    <span class="badge-live bg-red-600 text-white px-3 py-1 rounded-full text-sm font-semibold flex items-center gap-1">
                        <i class="fas fa-circle text-[8px] animate-pulse"></i> LIVE
                    </span>
                </div>
            </div>
        </div>
    </header>

    <main class="max-w-6xl mx-auto px-4 sm:px-6 lg:px-8 py-10">
        <!-- Statistiques globales -->
        <div class="grid grid-cols-1 md:grid-cols-2 lg:grid-cols-4 gap-6 mb-10">
            <div class="stat-card">
                <p class="text-gray-500 text-sm">Total Votes</p>
                <p class="text-3xl font-bold text-gray-900 mt-2" id="totalVotes">0</p>
            </div>
            <div class="stat-card">
                <p class="text-gray-500 text-sm">Voter Turnout</p>
                <p class="text-3xl font-bold text-gray-900 mt-2" id="turnout">0%</p>
            </div>
            <div class="stat-card">
                <p class="text-gray-500 text-sm">Status</p>
                <p class="text-3xl font-bold text-green-600 mt-2" id="status">ACTIVE</p>
            </div>
            <div class="stat-card border-primary/20 bg-primary/[0.02]">
                <p class="text-primary text-sm font-bold uppercase tracking-widest">Time Remaining</p>
                <p class="text-3xl font-black text-primary mt-2 flex items-center gap-2" id="countdown">
                    --:--:--
                </p>
                <p class="text-[10px] text-gray-400 mt-1 uppercase font-bold" id="deadlineText">Loading...</p>
            </div>
        </div>

        <!-- Navigation et timer -->
        <div class="flex flex-wrap items-center justify-between gap-4 mb-6">
            <h2 id="currentCategoryName" class="text-2xl font-bold text-gray-800">Loading...</h2>
            <div class="flex items-center gap-4">
                <button id="prevBtn" class="w-10 h-10 rounded-full border border-gray-300 flex items-center justify-center text-gray-600 hover:bg-gray-100 transition">
                    <i class="fas fa-chevron-left"></i>
                </button>
                <div id="navDots" class="flex gap-2"></div>
                <button id="nextBtn" class="w-10 h-10 rounded-full border border-gray-300 flex items-center justify-center text-gray-600 hover:bg-gray-100 transition">
                    <i class="fas fa-chevron-right"></i>
                </button>
            </div>
        </div>

        <!-- Barre de progression timer -->
        <div class="timer-bar mb-8">
            <div id="timerFill" class="timer-fill" style="width: 100%;"></div>
        </div>

        <!-- Conteneur de la catégorie active -->
        <div id="categoryContainer" class="category-card">
            <div class="flex flex-col items-center py-20 text-gray-400">
                <i class="fas fa-spinner fa-spin text-3xl mb-4"></i>
                <p>Initializing live feed...</p>
            </div>
        </div>

        <!-- Pied de page -->
        <footer class="mt-16 pt-8 border-t border-gray-200 flex flex-wrap justify-between items-center text-sm text-gray-500">
            <p>© 2026 IUEA Guild. All Rights Reserved.</p>
            <div class="footer-links flex gap-6">
                <a href="#">Privacy Policy</a>
                <a href="#">Terms of Use</a>
            </div>
        </footer>
    </main>

    <!-- Template pour un candidat -->
    <template id="candidateTemplate">
        <div class="flex items-start gap-4 py-3 border-b border-gray-100 last:border-0">
            <img src="" alt="Photo" class="candidate-photo" draggable="false" oncontextmenu="return false;">
            <div class="flex-1">
                <div class="flex justify-between items-center">
                    <span class="font-semibold text-gray-800 candidate-name"></span>
                    <span class="font-bold text-gray-900 candidate-votes"></span>
                </div>
                <div class="progress-bar">
                    <div class="progress-fill" style="width: 0%;"></div>
                </div>
                <div class="flex justify-between text-xs text-gray-500 mt-1">
                    <span class="candidate-percentage"></span>
                    <span class="candidate-votes-short"></span> votes
                </div>
            </div>
        </div>
    </template>

    <script>
        (function () {
            let electionsData = [];
            let currentIndex = 0;
            let autoTimer = null;
            const AUTO_SWITCH_TIME = 20 * 1000; // 20 secondes avant de changer de catégorie
            const FETCH_INTERVAL = 5000; // Rafraîchir les données toutes les 5s

            const API_URL = '{{ route("api.live-stats") }}';

            // DOM Elements
            const container = document.getElementById('categoryContainer');
            const categoryNameEl = document.getElementById('currentCategoryName');
            const totalVotesEl = document.getElementById('totalVotes');
            const turnoutEl = document.getElementById('turnout');
            const statusEl = document.getElementById('status');
            const navDots = document.getElementById('navDots');
            const prevBtn = document.getElementById('prevBtn');
            const nextBtn = document.getElementById('nextBtn');
            const timerFill = document.getElementById('timerFill');
            const candidateTemplate = document.getElementById('candidateTemplate');

            let electionEndTime = null;
            let countdownTimer = null;

            async function fetchData() {
                try {
                    const response = await fetch(API_URL);
                    const data = await response.json();
                    
                    const oldLength = electionsData.length;
                    electionsData = data.categories;
                    
                    // Update global stats
                    totalVotesEl.textContent = data.stats.total_cast.toLocaleString();
                    turnoutEl.textContent = data.stats.turnout + '%';
                    
                    if (data.stats.election_end_time) {
                        electionEndTime = new Date(data.stats.election_end_time).getTime();
                        document.getElementById('deadlineText').textContent = "Until " + new Date(data.stats.election_end_time).toLocaleString('en-US', { month: 'short', day: 'numeric', hour: '2-digit', minute: '2-digit' });
                        startCountdown();
                    }

                    if (electionsData.length === 0) {
                        container.innerHTML = '<div class="text-center py-20 text-gray-400">No active voting categories at the moment.</div>';
                        categoryNameEl.textContent = 'No active polls';
                        return;
                    }

                    // If it's the first time or categories changed, reset view
                    if (oldLength === 0) {
                        renderCategory(0);
                        resetTimer();
                    } else {
                        // Just refresh the current view
                        renderCategory(currentIndex, true);
                    }
                } catch (error) {
                    console.error('API Error:', error);
                }
            }

            function startCountdown() {
                if (countdownTimer) clearInterval(countdownTimer);
                
                const update = () => {
                    const now = new Date().getTime();
                    const distance = electionEndTime - now;

                    const countdownEl = document.getElementById('countdown');
                    const statusEl = document.getElementById('status');
                    
                    if (distance < 0) {
                        clearInterval(countdownTimer);
                        countdownEl.innerHTML = "FINISHED";
                        countdownEl.classList.remove('text-primary');
                        countdownEl.classList.add('text-gray-400');
                        statusEl.textContent = "CLOSED";
                        statusEl.classList.remove('text-green-600');
                        statusEl.classList.add('text-red-700');
                        return;
                    }

                    const hours = Math.floor((distance % (1000 * 60 * 60 * 24)) / (1000 * 60 * 60));
                    const minutes = Math.floor((distance % (1000 * 60 * 60)) / (1000 * 60));
                    const seconds = Math.floor((distance % (1000 * 60)) / 1000);

                    countdownEl.innerHTML = 
                        `${hours.toString().padStart(2, '0')}:${minutes.toString().padStart(2, '0')}:${seconds.toString().padStart(2, '0')}`;
                };

                update();
                countdownTimer = setInterval(update, 1000);
            }

            function renderCategory(index, silent = false) {
                if (electionsData.length === 0) return;
                
                const election = electionsData[index];
                if (!election) {
                    currentIndex = 0;
                    return renderCategory(0, silent);
                }

                categoryNameEl.textContent = election.name;
                
                const content = document.createDocumentFragment();
                // Sort candidates by votes
                const sortedCandidates = [...election.candidates].sort((a, b) => b.votes - a.votes);

                sortedCandidates.forEach(candidate => {
                    const clone = candidateTemplate.content.cloneNode(true);
                    const img = clone.querySelector('img');
                    img.src = candidate.photo;
                    img.alt = candidate.name;
                    clone.querySelector('.candidate-name').textContent = candidate.name;
                    clone.querySelector('.candidate-votes').textContent = candidate.votes.toLocaleString() + ' votes';
                    clone.querySelector('.candidate-votes-short').textContent = candidate.votes.toLocaleString();
                    clone.querySelector('.candidate-percentage').textContent = candidate.percentage + '%';
                    const fill = clone.querySelector('.progress-fill');
                    fill.style.width = candidate.percentage + '%';
                    content.appendChild(clone);
                });

                if (!silent) {
                    container.classList.add('fade-out');
                    setTimeout(() => {
                        container.innerHTML = '';
                        container.appendChild(content);
                        container.classList.remove('fade-out');
                    }, 400);
                } else {
                    container.innerHTML = '';
                    container.appendChild(content);
                }

                updateNavDots(index);
            }

            function updateNavDots(activeIndex) {
                navDots.innerHTML = '';
                electionsData.forEach((_, i) => {
                    const dot = document.createElement('div');
                    dot.className = `nav-dot ${i === activeIndex ? 'active' : ''}`;
                    dot.addEventListener('click', () => {
                        currentIndex = i;
                        renderCategory(i);
                        resetTimer();
                    });
                    navDots.appendChild(dot);
                });
            }

            function nextCategory() {
                currentIndex = (currentIndex + 1) % electionsData.length;
                renderCategory(currentIndex);
                resetTimer();
            }

            function prevCategory() {
                currentIndex = (currentIndex - 1 + electionsData.length) % electionsData.length;
                renderCategory(currentIndex);
                resetTimer();
            }

            function resetTimer() {
                if (autoTimer) clearTimeout(autoTimer);
                
                timerFill.style.transition = 'none';
                timerFill.style.width = '100%';
                timerFill.offsetHeight; // force reflow
                timerFill.style.transition = `width ${AUTO_SWITCH_TIME}ms linear`;
                timerFill.style.width = '0%';

                autoTimer = setTimeout(nextCategory, AUTO_SWITCH_TIME);
            }

            // Events
            prevBtn.addEventListener('click', prevCategory);
            nextBtn.addEventListener('click', nextCategory);

            // Initial and periodic fetch
            fetchData();
            setInterval(fetchData, FETCH_INTERVAL);

            // Context menu protection for photos
            document.addEventListener('contextmenu', function (e) {
                if (e.target.tagName === 'IMG') e.preventDefault();
            });
        })();
    </script>
</body>
</html>
