<!DOCTYPE html>
<html lang="en">
<head>
    <meta charset="UTF-8">
    <meta name="viewport" content="width=device-width, initial-scale=1.0">
    <title>OFFICIAL DIGITAL BALLOT | IUEA EC</title>
    <script src="https://cdn.tailwindcss.com"></script>
    <link rel="preconnect" href="https://fonts.googleapis.com">
    <link rel="preconnect" href="https://fonts.gstatic.com" crossorigin>
    <link href="https://fonts.googleapis.com/css2?family=Outfit:wght@400;600;800;900&display=swap" rel="stylesheet">
    <link rel="stylesheet" href="https://cdnjs.cloudflare.com/ajax/libs/font-awesome/6.4.0/css/all.min.css">
    <script src="https://cdnjs.cloudflare.com/ajax/libs/gsap/3.12.2/gsap.min.js"></script>
    <style>
        :root {
            --primary: #8B0000;
            --primary-light: #A52A2A;
        }
        * { font-family: 'Outfit', sans-serif; }
        body { 
            background-color: #f8f9fa; 
            background-image: 
                radial-gradient(at 100% 0%, rgba(139, 0, 0, 0.05) 0px, transparent 50%),
                radial-gradient(at 0% 100%, rgba(139, 0, 0, 0.05) 0px, transparent 50%);
        }
        
        /* Premium Card Design */
        .candidate-card {
            transition: all 0.4s cubic-bezier(0.34, 1.56, 0.64, 1);
            border: 4px solid transparent;
        }
        
        .candidate-radio:checked + .candidate-card {
            border-color: var(--primary);
            background-color: white;
            box-shadow: 0 25px 50px -12px rgba(139, 0, 0, 0.25);
            transform: translateY(-10px) scale(1.02);
        }

        .check-indicator {
            opacity: 0;
            transform: scale(0.5);
            transition: all 0.4s cubic-bezier(0.34, 1.56, 0.64, 1);
        }

        .candidate-radio:checked + .candidate-card .check-indicator {
            opacity: 1;
            transform: scale(1);
        }

        .candidate-radio:checked + .candidate-card .photo-container {
            border-color: var(--primary);
        }

        /* Fixed Submission Bar */
        .submission-bar {
            background: rgba(255, 255, 255, 0.8);
            backdrop-filter: blur(20px);
            border-top: 1px solid rgba(0,0,0,0.05);
        }

        /* Animations */
        @keyframes pulse-red {
            0% { box-shadow: 0 0 0 0 rgba(139, 0, 0, 0.4); }
            70% { box-shadow: 0 0 0 15px rgba(139, 0, 0, 0); }
            100% { box-shadow: 0 0 0 0 rgba(139, 0, 0, 0); }
        }
        .active-glow {
            animation: pulse-red 2s infinite;
        }
    </style>
</head>
<body class="min-h-screen pb-40">

    <!-- Top Navigation Kiosk Header -->
    <nav class="sticky top-0 z-50 bg-white/80 backdrop-blur-md border-b border-gray-100 px-8 py-4 mb-12 shadow-sm">
        <div class="max-w-7xl mx-auto flex items-center justify-between">
            <div class="flex items-center gap-6">
                <img src="https://iuea.ac.ug/sitepad-data/uploads/2024/07/IUEA-Logo-official-1.png" alt="IUEA" class="h-12 w-auto">
                <div class="h-8 w-px bg-gray-200"></div>
                <div>
                    <h1 class="text-sm font-black uppercase tracking-[0.2em] text-gray-900">Official Ballot</h1>
                    <p class="text-[9px] font-bold text-primary uppercase tracking-[0.4em]">Kiosk Supervised Session</p>
                </div>
            </div>
            
            <div class="flex items-center gap-6">
                <div class="text-left">
                    <p class="text-[9px] font-black text-gray-400 uppercase tracking-widest leading-none mb-1">Authenticated Voter</p>
                    <p class="text-xs font-black text-gray-900 uppercase">{{ session('voter_name') }} ({{ session('voter_reg') }})</p>
                </div>
                <div class="w-10 h-10 bg-gray-900 text-white rounded-full flex items-center justify-center font-black text-xs">
                    {{ substr(session('voter_name'), 0, 1) }}
                </div>
            </div>
        </div>
    </nav>

    <div class="max-w-7xl mx-auto px-6">
        <form action="{{ route('voter.submit') }}" method="POST" id="ballotForm">
            @csrf
            
            @if(session('error'))
                <div class="mb-12 p-6 bg-red-600 text-white rounded-3xl shadow-2xl flex items-center gap-6 animate-bounce">
                    <i class="fas fa-triangle-exclamation text-3xl"></i>
                    <p class="font-black text-sm uppercase tracking-tight">{{ session('error') }}</p>
                </div>
            @endif

            <div class="space-y-24">
                @foreach($categories as $category)
                <section class="category-section">
                    <div class="flex flex-col items-center mb-12 text-center">
                        <span class="text-[10px] font-black text-primary uppercase tracking-[0.5em] mb-3">Position Category</span>
                        <h2 class="text-4xl font-black text-gray-900 uppercase tracking-tighter">{{ $category->name }}</h2>
                        <div class="w-16 h-1 bg-primary mt-4 rounded-full"></div>
                        <p class="mt-4 text-gray-400 text-xs font-medium uppercase tracking-widest">Select one candidate for this position</p>
                    </div>

                    <div class="grid grid-cols-1 md:grid-cols-2 lg:grid-cols-3 xl:grid-cols-4 gap-8">
                        @forelse($category->candidates as $candidate)
                        <div class="relative">
                            <input type="radio" name="cat_{{ $category->id }}" value="{{ $candidate->id }}" id="cand_{{ $candidate->id }}" class="candidate-radio absolute opacity-0 invisible">
                            <label for="cand_{{ $candidate->id }}" class="candidate-card block h-full bg-white rounded-[3.5rem] p-8 text-center shadow-xl shadow-gray-200/40 relative cursor-pointer">
                                
                                <!-- Oversized Check Indicator Overlay -->
                                <div class="check-indicator absolute top-6 right-6 z-20 w-12 h-12 bg-primary text-white rounded-full flex items-center justify-center shadow-2xl">
                                    <i class="fas fa-check text-lg"></i>
                                </div>

                                <!-- Photo Container -->
                                <div class="photo-container relative w-full aspect-square rounded-[3rem] overflow-hidden mb-8 border-4 border-gray-50 flex items-center justify-center bg-gray-50 transition-all">
                                    @if($candidate->image_path)
                                        <img src="{{ asset('storage/' . $candidate->image_path) }}" alt="{{ $candidate->name }}" class="w-full h-full object-cover">
                                    @else
                                        <i class="fas fa-user text-6xl text-gray-200"></i>
                                    @endif
                                </div>

                                <div class="space-y-4">
                                    <h3 class="text-2xl font-black text-gray-900 uppercase tracking-tighter leading-none">{{ $candidate->name }}</h3>
                                    <div class="flex flex-col items-center gap-2">
                                        <span class="px-4 py-1.5 bg-gray-100 rounded-full text-[9px] font-black uppercase text-gray-500 tracking-[0.1em]">
                                            {{ $candidate->faculty }}
                                        </span>
                                        <p class="text-[9px] font-mono text-gray-300 uppercase">{{ $candidate->registration_number }}</p>
                                    </div>
                                </div>
                            </label>
                        </div>
                        @empty
                        <div class="col-span-full py-20 text-center bg-white rounded-[3.5rem] border-2 border-dashed border-gray-100">
                            <i class="fas fa-user-slash text-4xl text-gray-200 mb-6"></i>
                            <p class="text-[10px] font-black text-gray-300 uppercase tracking-[0.3em]">No candidates eligible for your faculty in this position</p>
                        </div>
                        @endforelse
                    </div>
                </section>
                @endforeach
            </div>

            <!-- HIGH IMPACT FIXED SUBMISSION BAR -->
            <div class="fixed bottom-0 left-0 right-0 p-8 z-[100] submission-bar flex justify-center">
                <div class="w-full max-w-4xl flex flex-col md:flex-row items-center justify-between gap-6">
                    <div class="text-center md:text-left">
                        <h4 class="text-sm font-black text-gray-900 uppercase tracking-tight">Review Your Selection</h4>
                        <p class="text-[10px] font-medium text-gray-400 uppercase tracking-widest">All choices are cryptographically signed for integrity</p>
                    </div>
                    
                    <button type="submit" class="active-glow w-full md:w-auto px-12 py-6 bg-primary hover:bg-primary-dark text-white rounded-3xl shadow-2xl flex items-center justify-center gap-6 group transition-all transform hover:scale-[1.05]">
                        <span class="text-lg font-black uppercase tracking-[0.2em]">Cast Final Ballot</span>
                        <div class="w-10 h-10 bg-white/20 rounded-full flex items-center justify-center">
                            <i class="fas fa-paper-plane text-sm"></i>
                        </div>
                    </button>
                </div>
            </div>
        </form>
    </div>

    <script>
        // Smooth Category Transitions
        gsap.from(".category-section", { 
            y: 80, 
            opacity: 0, 
            stagger: 0.4, 
            duration: 1.2, 
            ease: "power4.out" 
        });

        // Interactive Selection Feedback
        document.querySelectorAll('.candidate-radio').forEach(radio => {
            radio.addEventListener('change', () => {
                const label = document.querySelector(`label[for="${radio.id}"]`);
                
                // Visual bounce effect
                gsap.fromTo(label, 
                    { scale: 0.95 }, 
                    { scale: 1, duration: 0.6, ease: "elastic.out(1, 0.3)" }
                );
            });
        });

        // Form Submission Safeguard
        document.getElementById('ballotForm').addEventListener('submit', function(e) {
            const btn = this.querySelector('button[type="submit"]');
            btn.innerHTML = '<i class="fas fa-spinner fa-spin mr-3"></i> ENCRYPTING BALLOT...';
            btn.style.pointerEvents = 'none';
        });
    </script>
</body>
</html>
