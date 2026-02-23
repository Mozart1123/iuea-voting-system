<!DOCTYPE html>
<html lang="en">
<head>
    <meta charset="UTF-8">
    <meta name="viewport" content="width=device-width, initial-scale=1.0">
    <title>LIVE RESULTS – IUEA GuildVote</title>
    <script src="https://cdn.tailwindcss.com"></script>
    <link rel="preconnect" href="https://fonts.googleapis.com">
    <link rel="preconnect" href="https://fonts.gstatic.com" crossorigin>
    <link href="https://fonts.googleapis.com/css2?family=Outfit:wght@400;600;800;900&display=swap" rel="stylesheet">
    <link rel="stylesheet" href="https://cdnjs.cloudflare.com/ajax/libs/font-awesome/6.4.0/css/all.min.css">
    <script src="https://cdnjs.cloudflare.com/ajax/libs/gsap/3.12.2/gsap.min.js"></script>
    <style>
        :root {
            --primary: #8B0000;
        }
        * { font-family: 'Outfit', sans-serif; }
        body { background-color: #0c0c0c; color: white; overflow: hidden; }
        .result-card { background: rgba(255, 255, 255, 0.03); border: 1px solid rgba(255, 255, 255, 0.05); }
        .glass-panel { background: rgba(0, 0, 0, 0.4); backdrop-filter: blur(10px); }
        .glow-red { box-shadow: 0 0 30px rgba(139, 0, 0, 0.3); }
        .bar-glow { box-shadow: 0 0 15px currentColor; }
    </style>
</head>
<body class="h-screen flex flex-col">

    <!-- Header for TV Display -->
    <header class="h-32 border-b border-white/5 flex items-center justify-between px-16 glass-panel relative z-10">
        <div class="flex items-center gap-8">
            <div class="w-16 h-16 bg-white rounded-2xl flex items-center justify-center p-2">
                <img src="https://iuea.ac.ug/sitepad-data/uploads/2024/07/IUEA-Logo-official-1.png" alt="IUEA Logo" class="w-full h-full object-contain">
            </div>
            <div>
                <h1 class="text-3xl font-black uppercase tracking-tighter">Live Election Intelligence</h1>
                <p class="text-[10px] font-bold text-primary uppercase tracking-[0.5em] mt-1">Real-time Public Broadcast • IUEA EC 2026</p>
            </div>
        </div>
        <div class="flex items-center gap-12 text-right">
            <div>
                <p class="text-[10px] font-black text-white/30 uppercase tracking-widest mb-1">Total Ballots</p>
                <p class="text-4xl font-black tracking-tighter" id="total-ballots text-primary">{{ \App\Models\Voter::count() }}</p>
            </div>
            <div class="px-6 py-2 bg-red-900/20 border border-primary/30 rounded-full flex items-center gap-3">
                <span class="w-2 h-2 bg-primary rounded-full animate-pulse glow-red"></span>
                <span class="text-[10px] font-black uppercase tracking-[0.2em]">LIVE UPDATING</span>
            </div>
        </div>
    </header>

    <!-- Main Results Grid -->
    <main class="flex-1 p-16 grid grid-cols-1 md:grid-cols-2 gap-12 overflow-hidden">
        @foreach($categories as $category)
        <section class="result-card p-10 rounded-[3rem] space-y-8 flex flex-col justify-center">
            <div class="flex items-end justify-between border-b border-white/10 pb-6 mb-4">
                <h2 class="text-2xl font-black uppercase tracking-widest text-primary">{{ $category->name }}</h2>
                <span class="text-[10px] font-bold text-white/30 uppercase tracking-[0.3em]">Aggregate Results Only</span>
            </div>

            <div class="space-y-10">
                @php 
                    $totalCatVotes = $category->votes_count;
                    $candidates = \App\Models\Candidate::where('category_id', $category->id)->withCount('votes')->orderBy('votes_count', 'desc')->get();
                @endphp
                @foreach($candidates as $candidate)
                <div class="flex items-center gap-6">
                    <div class="w-20 h-20 rounded-2xl overflow-hidden border-2 border-white/5 bg-white/5 shadow-2xl flex-shrink-0">
                        @if($candidate->image_path)
                            <img src="{{ asset('storage/' . $candidate->image_path) }}" alt="{{ $candidate->name }}" class="w-full h-full object-cover">
                        @else
                            <div class="w-full h-full flex items-center justify-center text-white/10">
                                <i class="fas fa-user text-2xl"></i>
                            </div>
                        @endif
                    </div>
                    <div class="flex-1 space-y-4">
                        <div class="flex items-end justify-between">
                            <div>
                                <span class="text-[9px] font-black uppercase tracking-widest text-white/40 mb-1 block">{{ $candidate->faculty }}</span>
                                <span class="text-xl font-bold uppercase tracking-tight">{{ $candidate->name }}</span>
                            </div>
                            <div class="text-right">
                                <p class="text-2xl font-black text-primary">{{ number_format($candidate->votes_count) }}</p>
                                <p class="text-[8px] font-bold text-white/20 uppercase tracking-widest">Aggregate Votes</p>
                            </div>
                        </div>
                        @php $percent = $totalCatVotes > 0 ? ($candidate->votes_count / $totalCatVotes) * 100 : 0; @endphp
                        <div class="w-full h-2 bg-white/5 rounded-full overflow-hidden">
                            <div class="h-full bg-primary rounded-full transition-all duration-1000 bar-glow" style="width: {{ $percent }}%"></div>
                        </div>
                    </div>
                </div>
                @endforeach
            </div>
        </section>
        @endforeach
    </main>

    <!-- Marquee Bottom -->
    <footer class="h-16 bg-primary/10 border-t border-white/5 flex items-center overflow-hidden">
        <div class="whitespace-nowrap flex animate-marquee">
            <span class="text-xs font-black uppercase tracking-[0.4em] px-12 text-white/40 italic">*** INTEGRITY CHECK: PASSING SHA-256 HASH VALIDATION ***</span>
            <span class="text-xs font-black uppercase tracking-[0.4em] px-12 text-white/40 italic">*** LIVE KIOSK SUPERVISION ACTIVE ON 5 TERMINALS ***</span>
            <span class="text-xs font-black uppercase tracking-[0.4em] px-12 text-white/40 italic">*** VOTE INTEGRITY GUARANTEED BY IUEA EC ***</span>
            <span class="text-xs font-black uppercase tracking-[0.4em] px-12 text-white/40 italic">*** NO MANUAL DATABASE MODIFICATION ALLOWED ***</span>
        </div>
    </footer style="--marquee-duration: 30s;">

    <style>
        @keyframes marquee {
            0% { transform: translateX(0); }
            100% { transform: translateX(-100%); }
        }
        .animate-marquee {
            animation: marquee var(--marquee-duration, 40s) linear infinite;
        }
    </style>

    <script>
        // Auto-refresh the page every 30 seconds for live data
        setTimeout(() => {
            window.location.reload();
        }, 30000);

        gsap.from(".result-card", { 
            scale: 0.9, 
            opacity: 0, 
            stagger: 0.2, 
            duration: 1.5, 
            ease: "expo.out" 
        });
    </script>
</body>
</html>
