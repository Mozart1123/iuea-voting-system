<!DOCTYPE html>
<html lang="en">
<head>
    <meta charset="UTF-8">
    <meta name="viewport" content="width=device-width, initial-scale=1.0">
    <title>IUEA GuildVote – Vote Successful</title>
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
        .success-bg { background: radial-gradient(circle at center, #1a4a1a 0%, #000000 100%); }
    </style>
</head>
<body class="min-h-screen success-bg flex items-center justify-center p-6 text-center overflow-hidden">

    <div id="success-card" class="max-w-xl w-full">
        <div class="mb-12 relative flex justify-center">
            <div id="ring" class="absolute w-48 h-48 border-4 border-green-500/30 rounded-full animate-ping"></div>
            <div id="check-box" class="w-32 h-32 bg-green-500 rounded-full flex items-center justify-center shadow-[0_0_50px_rgba(34,197,94,0.5)] z-10">
                <i class="fas fa-check text-5xl text-white"></i>
            </div>
        </div>

        <h1 class="text-4xl font-black text-white uppercase tracking-tighter mb-4">BALLOT CONFIRMED</h1>
        <p class="text-green-400 font-black text-xs uppercase tracking-[0.5em] mb-8">Securely Recorded • Voted Once</p>
        
        <div class="bg-white/5 border border-white/10 backdrop-blur-xl p-8 rounded-[2.5rem] mb-12">
            <p class="text-white/70 text-sm leading-relaxed mb-6 font-medium">Your vote has been securely hashed and stored in our database. Double voting is impossible. Your participation helps shape the future of IUEA.</p>
            <div class="flex justify-center gap-2">
                <div class="w-2 h-2 bg-green-500 rounded-full"></div>
                <div class="w-2 h-2 bg-green-500/50 rounded-full"></div>
                <div class="w-2 h-2 bg-green-500/20 rounded-full"></div>
            </div>
        </div>

        <h2 id="timer-text" class="text-[10px] font-black text-white/40 uppercase tracking-[0.3em]">
            Redirecting to entry screen in <span id="countdown" class="text-white">5</span> seconds...
        </h2>
    </div>

    <script>
        gsap.from("#success-card", { scale: 0.8, opacity: 0, duration: 1, ease: "back.out(1.7)" });
        gsap.from("#check-box", { rotate: -45, duration: 1, delay: 0.2 });

        let count = 5;
        const countdownEl = document.getElementById('countdown');
        const interval = setInterval(() => {
            count--;
            countdownEl.textContent = count;
            if (count === 0) {
                clearInterval(interval);
                window.location.href = "{{ route('voter.entry') }}";
            }
        }, 1000);
    </script>
</body>
</html>
