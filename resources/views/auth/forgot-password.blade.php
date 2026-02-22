<!DOCTYPE html>
<html lang="en">
<head>
    <meta charset="UTF-8">
    <meta name="viewport" content="width=device-width, initial-scale=1.0">
    <title>Reset Password - IUEA GuildVote</title>
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
            background-color: white;
        }
        .bg-primary { background-color: var(--primary); }
        .bg-primary-dark { background-color: var(--primary-dark); }
        .text-primary { color: var(--primary); }
        .border-primary { border-color: var(--primary); }
        .hover\:bg-primary:hover { background-color: var(--primary); }
        .hover\:text-primary:hover { color: var(--primary); }
        
        .gradient-left {
            background: linear-gradient(145deg, #660000 0%, #8B0000 100%);
        }
    </style>
</head>
<body class="min-h-screen flex flex-col md:flex-row antialiased">

    <!-- LEFT PANEL -->
    <div id="left-panel" class="md:w-1/2 gradient-left text-white flex flex-col justify-between p-8 md:p-12 lg:p-16 relative overflow-hidden">
        <div class="absolute inset-0 opacity-5" style="background-image: url('data:image/svg+xml,%3Csvg width=\'60\' height=\'60\' viewBox=\'0 0 60 60\' xmlns=\'http://www.w3.org/2000/svg\'%3E%3Cg fill=\'white\' fill-opacity=\'1\' fill-rule=\'evenodd\'%3E%3Cpath d=\'M36 34v-4h-2v4h-4v2h4v4h2v-4h4v-2h-4zm0-30V0h-2v4h-4v2h4v4h2V6h4V4h-4zM6 34v-4H4v4H0v2h4v4h2v-4h4v-2H6zM6 4V0H4v4H0v2h4v4h2V6h4V4H6z\'/%3E%3C/g%3E%3C/svg%3E');"></div>
        
        <div class="relative z-10">
            <div class="flex items-center space-x-3 mb-12">
                <img src="https://iuea.ac.ug/sitepad-data/uploads/2024/07/IUEA-Logo-official-1.png" alt="IUEA Logo" class="w-20 h-20 object-contain brightness-0 invert">
                <span class="font-bold text-2xl tracking-tight">IUEA <span class="font-extrabold">GuildVote</span></span>
            </div>
            
            <div class="max-w-lg">
                <h1 class="text-3xl md:text-4xl lg:text-5xl font-bold leading-tight mb-6">
                    Forgot your <br>password? – <span class="underline decoration-4 decoration-white/40">No problem</span>
                </h1>
                <p class="text-white/80 text-lg mb-8">
                    Let us know your university email address and we will email you a password reset link that will allow you to choose a new one.
                </p>
                
                <div class="flex items-center gap-4">
                    <div class="w-10 h-10 bg-white/20 rounded-lg flex items-center justify-center shrink-0">
                        <i class="fas fa-envelope-open-text text-white"></i>
                    </div>
                    <div>
                        <p class="font-bold">Check your inbox</p>
                        <p class="text-white/60 text-sm">A secure link will be sent to your student email.</p>
                    </div>
                </div>
            </div>
        </div>
        
        <div class="relative z-10 text-white/60 text-sm mt-12">
            © 2025 IUEA – Security First
        </div>
    </div>

    <!-- RIGHT PANEL -->
    <div id="right-panel" class="md:w-1/2 bg-white flex items-center justify-center p-6 md:p-10">
        <div class="w-full max-w-md">
            <div class="mb-8">
                <h2 class="text-3xl font-bold text-gray-900 mb-2">Password Reset</h2>
                <p class="text-gray-500">Provide your email to recover access.</p>
            </div>
            
            @if (session('status'))
                <div class="mb-6 p-4 rounded-xl bg-green-50 border border-green-200 text-green-700 text-sm font-medium">
                    {{ session('status') }}
                </div>
            @endif

            <form id="auth-form" action="{{ route('password.email') }}" method="POST" class="space-y-6">
                @csrf
                
                <!-- Email Address -->
                <div>
                    <label class="block text-sm font-semibold text-gray-700 mb-2">University Email</label>
                    <div class="relative">
                        <div class="absolute inset-y-0 left-0 pl-3 flex items-center pointer-events-none">
                            <i class="fas fa-envelope text-gray-400"></i>
                        </div>
                        <input type="email" name="email" value="{{ old('email') }}" placeholder="name@iuea.ac.ug" 
                               class="w-full pl-10 pr-4 py-3 border border-gray-300 rounded-xl bg-gray-50 focus:bg-white focus:ring-2 focus:ring-primary/20 focus:border-primary transition duration-200 text-gray-900 @error('email') border-red-500 @enderror" required autofocus>
                    </div>
                    @error('email')
                        <p class="text-red-500 text-xs mt-1 font-medium">{{ $message }}</p>
                    @enderror
                </div>

                <button type="submit" id="submit-btn" class="w-full bg-red-50 text-primary border-2 border-primary font-bold py-3.5 px-4 rounded-xl shadow-md hover:bg-red-100 transition duration-200 flex items-center justify-center gap-2">
                    <i class="fas fa-paper-plane"></i> Send Reset Link
                </button>
                
                <div class="text-center mt-8">
                    <p class="text-gray-600 text-sm">
                        Remembered your password? 
                        <a href="{{ route('login') }}" class="font-semibold text-primary hover:text-primary-dark transition">Go back to login</a>
                    </p>
                </div>
            </form>
            
            <div class="flex items-center justify-center gap-4 mt-10 pt-6 border-t border-gray-100 text-gray-400 text-xs">
                <span><i class="fas fa-shield-alt mr-1"></i> Secure process</span>
                <span><i class="fas fa-user-lock mr-1"></i> Identity verified</span>
            </div>
        </div>
    </div>
    <!-- GSAP for Premium Animations -->
    <script src="https://cdnjs.cloudflare.com/ajax/libs/gsap/3.12.2/gsap.min.js"></script>
    <script>
        document.addEventListener('DOMContentLoaded', () => {
            const tl = gsap.timeline({ defaults: { ease: "power4.out" } });

            tl.from("#left-panel", { xPercent: -100, duration: 1.2 })
              .from("#right-panel", { xPercent: 100, duration: 1.2 }, "-=1.2")
              .from(".relative.z-10 > div", { 
                  y: 40, 
                  opacity: 0, 
                  stagger: 0.2, 
                  duration: 1 
              }, "-=0.5")
              .from("form > div", { 
                  y: 20, 
                  opacity: 0, 
                  stagger: 0.1, 
                  duration: 0.8 
              }, "-=0.8")
              .from("#submit-btn", { 
                  y: 10,
                  duration: 0.5 
              }, "-=0.3");

            gsap.to("img[alt='IUEA Logo']", {
                y: -10,
                duration: 2,
                repeat: -1,
                yoyo: true,
                ease: "sine.inOut"
            });

            const pattern = document.querySelector('.absolute.inset-0.opacity-5');
            document.addEventListener('mousemove', (e) => {
                const { clientX, clientY } = e;
                const moveX = (clientX - window.innerWidth / 2) * 0.01;
                const moveY = (clientY - window.innerHeight / 2) * 0.01;
                gsap.to(pattern, { x: moveX, y: moveY, duration: 1 });
            });

            const inputs = document.querySelectorAll('input');
            inputs.forEach(input => {
                input.addEventListener('focus', () => {
                    gsap.to(input.closest('.relative') || input, { scale: 1.02, duration: 0.3 });
                });
                input.addEventListener('blur', () => {
                    gsap.to(input.closest('.relative') || input, { scale: 1, duration: 0.3 });
                });
            });
        });
    </script>
</body>
</html>
