<!DOCTYPE html>
<html lang="en">
<head>
    <meta charset="UTF-8">
    <meta name="viewport" content="width=device-width, initial-scale=1.0">
    <title>IUEA GuildVote – Login</title>
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
<body class="min-h-screen flex flex-col md:flex-row antialiased overflow-hidden">

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
                    Secure and <br>Transparent Elections – <span class="underline decoration-4 decoration-white/40">for IUEA</span>
                </h1>
                <p class="text-white/80 text-lg mb-8">
                    The new kiosk voting system. Secure authentication for administrators and supervisors.
                </p>
                
                <div class="flex flex-wrap gap-6 mb-12">
                    <div class="flex items-center gap-2">
                        <div class="w-8 h-8 bg-white/20 rounded-lg flex items-center justify-center">
                            <i class="fas fa-check text-white text-sm"></i>
                        </div>
                        <span class="text-sm font-medium">University Validated</span>
                    </div>
                    <div class="flex items-center gap-2">
                        <div class="w-8 h-8 bg-white/20 rounded-lg flex items-center justify-center">
                            <i class="fas fa-shield-alt text-white text-sm"></i>
                        </div>
                        <span class="text-sm font-medium">100% Verifiable</span>
                    </div>
                </div>
                
                <div class="border-l-4 border-white/40 pl-6 italic text-white/90 text-lg">
                    “Integrity is the foundation of our university democracy.”
                </div>
            </div>
        </div>
        
        <div class="relative z-10 text-white/60 text-sm mt-12">
            © 2026 IUEA – Official voting platform
        </div>
    </div>

    <!-- RIGHT PANEL -->
    <div id="right-panel" class="md:w-1/2 bg-white flex items-center justify-center p-6 md:p-10">
        <div class="w-full max-w-md">
            <div class="mb-8">
                <h2 class="text-3xl font-bold text-gray-900 mb-2">Welcome back</h2>
                <p class="text-gray-500">Log in to access the administrative dashboard.</p>
            </div>
            
            <!-- Flash Messages -->
            @if(session('success'))
                <div class="mb-6 p-4 bg-green-50 border border-green-200 rounded-2xl flex items-start gap-3">
                    <div class="flex-shrink-0 w-8 h-8 bg-green-100 rounded-xl flex items-center justify-center">
                        <i class="fas fa-check-circle text-green-600 text-sm"></i>
                    </div>
                    <div>
                        <p class="text-sm font-bold text-green-800 mb-0.5">Vote Submitted Successfully!</p>
                        <p class="text-sm text-green-600">{{ session('success') }}</p>
                    </div>
                </div>
            @endif

            @if(session('error'))
                <div class="mb-6 p-4 bg-red-50 border border-red-200 rounded-2xl flex items-start gap-3">
                    <div class="flex-shrink-0 w-8 h-8 bg-red-100 rounded-xl flex items-center justify-center">
                        <i class="fas fa-exclamation-circle text-red-600 text-sm"></i>
                    </div>
                    <p class="text-sm text-red-600">{{ session('error') }}</p>
                </div>
            @endif

            <!-- login form -->
            <form id="auth-form" action="{{ route('login') }}" method="POST" class="space-y-6">
                @csrf
                <div>
                    <label class="block text-sm font-semibold text-gray-700 mb-2">Username or Email</label>
                    <div class="relative">
                        <div class="absolute inset-y-0 left-0 pl-3 flex items-center pointer-events-none">
                            <i class="fas fa-user text-gray-400"></i>
                        </div>
                        <input type="text" name="login" value="{{ old('login') }}" 
                               placeholder="Administrator ID" 
                               class="w-full pl-10 pr-4 py-3 border border-gray-300 rounded-xl bg-gray-50 focus:bg-white focus:ring-2 focus:ring-primary/20 focus:border-primary transition duration-200 text-gray-900 placeholder-gray-400 @error('login') border-red-500 @enderror" 
                               required autofocus>
                    </div>
                    @error('login')
                        <p class="text-red-500 text-xs mt-1 font-medium">{{ $message }}</p>
                    @enderror
                </div>
                
                <div>
                    <label class="block text-sm font-semibold text-gray-700 mb-2">Password</label>
                    <div class="relative">
                        <div class="absolute inset-y-0 left-0 pl-3 flex items-center pointer-events-none">
                            <i class="fas fa-lock text-gray-400"></i>
                        </div>
                        <input type="password" name="password" placeholder="••••••••" 
                               class="w-full pl-10 pr-4 py-3 border border-gray-300 rounded-xl bg-gray-50 focus:bg-white focus:ring-2 focus:ring-primary/20 focus:border-primary transition duration-200 text-gray-900 @error('password') border-red-500 @enderror"
                               required>
                    </div>
                    @error('password')
                        <p class="text-red-500 text-xs mt-1 font-medium">{{ $message }}</p>
                    @enderror
                </div>
                
                <button type="submit" class="w-full bg-primary text-white font-bold py-3.5 px-4 rounded-xl shadow-md hover:bg-primary-dark transition duration-200 flex items-center justify-center gap-2">
                    <i class="fas fa-sign-in-alt"></i> Log In
                </button>
                
                <!-- SSO / callout -->
                <div class="relative my-6">
                    <div class="absolute inset-0 flex items-center">
                        <div class="w-full border-t border-gray-100"></div>
                    </div>
                    <div class="relative flex justify-center text-sm">
                        <span class="px-4 bg-white text-gray-500 uppercase tracking-widest text-[10px] font-black">Or continue with</span>
                    </div>
                </div>

                <a href="{{ route('auth.google') }}" class="w-full bg-white border border-gray-300 text-gray-700 font-bold py-3 px-4 rounded-xl shadow-sm hover:shadow-md hover:bg-gray-50 transition duration-200 flex items-center justify-center gap-3">
                    <img src="https://www.gstatic.com/firebasejs/ui/2.0.0/images/auth/google.svg" class="w-5 h-5">
                    <span>Sign in with Google</span>
                </a>
                
                <div class="text-center">
                    <p class="text-gray-600 text-sm">
                        Don’t have an account? 
                        <a href="{{ route('register') }}" class="font-semibold text-primary hover:text-primary-dark transition">Register here →</a>
                    </p>
                    <p class="text-xs text-gray-400 mt-6">
                        Secure login • CSRF protected • RSA-4096 Encrypted
                    </p>
                </div>
            </form>
            
            <div class="mt-10 pt-6 border-t border-gray-100 text-gray-400 text-xs text-center">
                <span>Secure access for authorized administrators only.</span>
            </div>
        </div>
    </div>

    <script src="https://cdnjs.cloudflare.com/ajax/libs/gsap/3.12.2/gsap.min.js"></script>
    <script>
        document.addEventListener('DOMContentLoaded', () => {
            const tl = gsap.timeline({ defaults: { ease: "power4.out" } });
            tl.from("#left-panel", { xPercent: -100, duration: 1.2 })
              .from("#right-panel", { xPercent: 100, duration: 1.2 }, "-=1.2")
              .from(".relative.z-10 > div", { y: 40, opacity: 0, stagger: 0.2, duration: 1 }, "-=0.5");
        });
    </script>
</body>
</html>