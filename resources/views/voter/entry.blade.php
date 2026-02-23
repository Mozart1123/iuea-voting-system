<!DOCTYPE html>
<html lang="en">
<head>
    <meta charset="UTF-8">
    <meta name="viewport" content="width=device-width, initial-scale=1.0">
    <title>IUEA GuildVote – Student Entry</title>
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
        }
        * { font-family: 'Inter', sans-serif; }
        .kiosk-bg { background: radial-gradient(circle at top right, #330000 0%, #000000 100%); }
    </style>
</head>
<body class="min-h-screen kiosk-bg flex items-center justify-center p-6 sm:p-12 overflow-hidden">

    <div id="content-card" class="w-full max-w-4xl bg-white rounded-[3rem] shadow-2xl overflow-hidden flex flex-col md:flex-row">
        
        <!-- Left Banner (Branding) -->
        <div class="md:w-5/12 bg-gray-50 p-12 flex flex-col justify-between border-r border-gray-100">
            <div>
                <img src="https://iuea.ac.ug/sitepad-data/uploads/2024/07/IUEA-Logo-official-1.png" alt="IUEA Logo" class="w-24 h-24 object-contain mb-8">
                <h1 class="text-3xl font-black text-gray-900 leading-tight uppercase tracking-tighter">GUILD <br>ELECTIONS <br><span class="text-primary">2026</span></h1>
            </div>
            <div>
                <p class="text-[10px] font-black text-gray-400 uppercase tracking-widest leading-relaxed">
                    SECURE PHYSICAL VOTING KIOSK <br>
                    REGISTERED & VERIFIED BY EC
                </p>
                <div class="mt-6 flex gap-3">
                    <div class="w-8 h-8 rounded-lg bg-primary/10 flex items-center justify-center text-primary">
                        <i class="fas fa-fingerprint text-sm"></i>
                    </div>
                    <div class="w-8 h-8 rounded-lg bg-primary/10 flex items-center justify-center text-primary">
                        <i class="fas fa-shield-halved text-sm"></i>
                    </div>
                </div>
            </div>
        </div>

        <!-- Right Side (Entry Form) -->
        <div class="md:w-7/12 p-12 lg:p-16">
            <h2 class="text-3xl font-black text-gray-900 tracking-tight mb-2">Student Verification</h2>
            <p class="text-gray-500 mb-10">Enter your credentials to proceed to the voting ballot.</p>

            @if(session('error'))
                <div class="mb-8 p-4 bg-red-50 border-l-4 border-primary text-red-900 text-xs font-bold rounded-lg flex items-center gap-3">
                    <i class="fas fa-circle-exclamation"></i>
                    {{ session('error') }}
                </div>
            @endif

            <form action="{{ route('voter.process') }}" method="POST" class="space-y-8">
                @csrf
                <div class="space-y-2">
                    <label class="text-[10px] font-black text-gray-400 uppercase tracking-widest ml-1">Full Student Name</label>
                    <div class="relative">
                        <input type="text" name="full_name" value="{{ old('full_name') }}" required
                               placeholder="e.g. MUSOKE JOHN"
                               class="w-full pl-6 pr-4 py-5 bg-gray-50 border-2 border-transparent rounded-2xl focus:bg-white focus:border-primary focus:ring-4 focus:ring-primary/5 outline-none transition-all text-gray-900 font-bold tracking-tight">
                        <i class="fas fa-signature absolute right-6 top-1/2 -translate-y-1/2 text-gray-300"></i>
                    </div>
                </div>

                <div class="space-y-2">
                    <label class="text-[10px] font-black text-gray-400 uppercase tracking-widest ml-1">Registration Number</label>
                    <div class="relative">
                        <input type="text" name="registration_number" value="{{ old('registration_number') }}" required
                               placeholder="e.g. 21/U/1234/PS"
                               class="w-full pl-6 pr-4 py-5 bg-gray-50 border-2 border-transparent rounded-2xl focus:bg-white focus:border-primary focus:ring-4 focus:ring-primary/5 outline-none transition-all text-gray-900 font-black tracking-widest uppercase">
                        <i class="fas fa-id-card-clip absolute right-6 top-1/2 -translate-y-1/2 text-gray-300"></i>
                    </div>
                </div>

                <button type="submit" class="w-full py-5 bg-primary hover:bg-primary-dark text-white font-black text-sm uppercase tracking-widest rounded-3xl shadow-xl shadow-red-900/20 transform hover:scale-[1.02] transition-all flex items-center justify-center gap-4">
                    Verify and Proceed <i class="fas fa-arrow-right"></i>
                </button>
            </form>

            <div class="mt-12 text-center">
                <p class="text-[10px] text-gray-400 font-bold uppercase tracking-widest">
                    <i class="fas fa-lock mr-2"></i> Only verified students may proceed
                </p>
            </div>
        </div>
    </div>

    <script>
        gsap.from("#content-card", { 
            scale: 0.9, 
            opacity: 0, 
            duration: 1.2, 
            ease: "power4.out" 
        });
        gsap.from(".md\\:w-5\\/12 > div", {
            x: -20,
            opacity: 0,
            stagger: 0.2,
            duration: 1,
            delay: 0.5
        });
        gsap.from("form > div", {
            y: 20,
            opacity: 0,
            stagger: 0.1,
            duration: 0.8,
            delay: 0.8
        });
    </script>
</body>
</html>
