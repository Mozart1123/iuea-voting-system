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
        .focus-ring:focus {
            outline: none;
            ring: 2px solid var(--primary);
            border-color: var(--primary);
        }
    </style>
</head>
<body class="min-h-screen flex flex-col md:flex-row antialiased">

    <!-- LEFT PANEL – brand, value prop, testimonial (dark red) -->
    <div class="md:w-1/2 gradient-left text-white flex flex-col justify-between p-8 md:p-12 lg:p-16 relative overflow-hidden">
        <!-- subtle pattern overlay -->
        <div class="absolute inset-0 opacity-5" style="background-image: url('data:image/svg+xml,%3Csvg width=\'60\' height=\'60\' viewBox=\'0 0 60 60\' xmlns=\'http://www.w3.org/2000/svg\'%3E%3Cg fill=\'white\' fill-opacity=\'1\' fill-rule=\'evenodd\'%3E%3Cpath d=\'M36 34v-4h-2v4h-4v2h4v4h2v-4h4v-2h-4zm0-30V0h-2v4h-4v2h4v4h2V6h4V4h-4zM6 34v-4H4v4H0v2h4v4h2v-4h4v-2H6zM6 4V0H4v4H0v2h4v4h2V6h4V4H6z\'/%3E%3C/g%3E%3C/svg%3E');"></div>
        
        <div class="relative z-10">
            <!-- logo + name -->
            <div class="flex items-center space-x-3 mb-12">
                <img src="https://iuea.ac.ug/sitepad-data/uploads/2024/07/IUEA-Logo-official-1.png" alt="IUEA Logo" class="w-20 h-20 object-contain brightness-0 invert">
                <span class="font-bold text-2xl tracking-tight">IUEA <span class="font-extrabold">GuildVote</span></span>
            </div>
            
            <div class="max-w-lg">
                <h1 class="text-3xl md:text-4xl lg:text-5xl font-bold leading-tight mb-6">
                    Secure, transparent <br>elections – <span class="underline decoration-4 decoration-white/40">built for IUEA</span>
                </h1>
                <p class="text-white/80 text-lg mb-8">
                    Trusted by 10+ student guild elections. Powered by Laravel’s enterprise‑grade security.
                </p>
                
                <!-- trust badges / stats -->
                <div class="flex flex-wrap gap-6 mb-12">
                    <div class="flex items-center gap-2">
                        <div class="w-8 h-8 bg-white/20 rounded-lg flex items-center justify-center">
                            <i class="fas fa-check text-white text-sm"></i>
                        </div>
                        <span class="text-sm font-medium">50K+ votes cast</span>
                    </div>
                    <div class="flex items-center gap-2">
                        <div class="w-8 h-8 bg-white/20 rounded-lg flex items-center justify-center">
                            <i class="fas fa-shield-alt text-white text-sm"></i>
                        </div>
                        <span class="text-sm font-medium">100% verifiable</span>
                    </div>
                    <div class="flex items-center gap-2">
                        <div class="w-8 h-8 bg-white/20 rounded-lg flex items-center justify-center">
                            <i class="fas fa-clock text-white text-sm"></i>
                        </div>
                        <span class="text-sm font-medium">real‑time results</span>
                    </div>
                </div>
                
                <!-- testimonial / quote -->
                <div class="border-l-4 border-white/40 pl-6 italic text-white/90 text-lg">
                    “GuildVote brought integrity back to our elections. No disputes, full transparency.”
                    <div class="flex items-center mt-4 not-italic">
                        <div class="w-10 h-10 bg-white/30 rounded-full flex items-center justify-center text-white font-bold">JK</div>
                        <div class="ml-3">
                            <p class="font-semibold text-white">John K. Musoke</p>
                            <p class="text-white/70 text-sm">Guild President 2023</p>
                        </div>
                    </div>
                </div>
            </div>
        </div>
        
        <!-- footer note (left side) -->
        <div class="relative z-10 text-white/60 text-sm mt-12">
            © 2025 IUEA – Official voting platform
        </div>
    </div>

    <!-- RIGHT PANEL – login form (pure white) -->
    <div class="md:w-1/2 bg-white flex items-center justify-center p-6 md:p-10">
        <div class="w-full max-w-md">
            <!-- header for mobile (appears only on small screens) -->
            <div class="md:hidden flex items-center space-x-3 mb-10">
                <img src="https://iuea.ac.ug/sitepad-data/uploads/2024/07/IUEA-Logo-official-1.png" alt="IUEA Logo" class="w-16 h-16 object-contain">
                <span class="font-bold text-xl text-gray-900">IUEA <span class="text-primary">GuildVote</span></span>
            </div>
            
            <div class="mb-8">
                <h2 class="text-3xl font-bold text-gray-900 mb-2">Welcome back</h2>
                <p class="text-gray-500">Log in to access the secure election dashboard.</p>
            </div>
            
            <!-- login form -->
            <form action="{{ route('login') }}" method="POST" class="space-y-6">
                @csrf
                <!-- email / student ID -->
                <div>
                    <label class="block text-sm font-semibold text-gray-700 mb-2">Student ID or Email</label>
                    <div class="relative">
                        <div class="absolute inset-y-0 left-0 pl-3 flex items-center pointer-events-none">
                            <i class="fas fa-user text-gray-400"></i>
                        </div>
                        <input type="text" name="login" value="{{ old('login') }}" 
                               placeholder="e.g. 21/U/1234/PS" 
                               class="w-full pl-10 pr-4 py-3 border border-gray-300 rounded-xl bg-gray-50 focus:bg-white focus:ring-2 focus:ring-primary/20 focus:border-primary transition duration-200 text-gray-900 placeholder-gray-400 @error('login') border-red-500 @enderror" 
                               required autofocus>
                    </div>
                    @error('login')
                        <p class="text-red-500 text-xs mt-1 font-medium">{{ $message }}</p>
                    @enderror
                </div>
                
                <!-- password -->
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
                
                <!-- remember me + forgot password -->
                <div class="flex items-center justify-between">
                    <div class="flex items-center">
                        <input id="remember" type="checkbox" name="remember" class="h-4 w-4 text-primary border-gray-300 rounded focus:ring-primary">
                        <label for="remember" class="ml-2 block text-sm text-gray-600">Remember me</label>
                    </div>
                    <a href="{{ route('password.request') }}" class="text-sm font-medium text-primary hover:text-primary-dark transition">Forgot password?</a>
                </div>
                
                <!-- login button -->
                <button type="submit" class="w-full bg-primary text-white font-bold py-3.5 px-4 rounded-xl shadow-lg hover:shadow-xl hover:bg-primary-dark transition duration-200 flex items-center justify-center gap-2">
                    <i class="fas fa-shield-alt"></i> Authorize Identity
                </button>
                
                <!-- SSO / callout -->
                <div class="relative my-6">
                    <div class="absolute inset-0 flex items-center">
                        <div class="w-full border-t border-gray-200"></div>
                    </div>
                    <div class="relative flex justify-center text-sm">
                        <span class="px-4 bg-white text-gray-500">Secure Access Point</span>
                    </div>
                </div>
                
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
            
            <!-- additional trust badges -->
            <div class="flex items-center justify-center gap-4 mt-10 pt-6 border-t border-gray-100 text-gray-400 text-xs">
                <span><i class="fas fa-fingerprint mr-1"></i> Biometric ready</span>
                <span><i class="fas fa-database mr-1"></i> Audit logs active</span>
                <span><i class="fas fa-clock mr-1"></i> 24/7 Support</span>
            </div>
        </div>
    </div>

    <!-- GSAP for Premium Animations -->
    <script src="https://cdnjs.cloudflare.com/ajax/libs/gsap/3.12.2/gsap.min.js"></script>
    <script>
        document.addEventListener('DOMContentLoaded', () => {
            // Main entry timeline
            const tl = gsap.timeline({ defaults: { ease: "power4.out" } });

            tl.from(".gradient-left", { xPercent: -100, duration: 1.2 })
              .from(".md-w-1-2.bg-white", { xPercent: 100, duration: 1.2 }, "-=1.2")
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
              .from("form button", { 
                  scale: 0.9, 
                  opacity: 0, 
                  duration: 0.5 
              }, "-=0.3");

            // Floating animation for Logo
            gsap.to("img[alt='IUEA Logo']", {
                y: -10,
                duration: 2,
                repeat: -1,
                yoyo: true,
                ease: "sine.inOut"
            });

            // Subtle mouse move effect on pattern
            const pattern = document.querySelector('.absolute.inset-0.opacity-5');
            document.addEventListener('mousemove', (e) => {
                const { clientX, clientY } = e;
                const moveX = (clientX - window.innerWidth / 2) * 0.01;
                const moveY = (clientY - window.innerHeight / 2) * 0.01;
                gsap.to(pattern, { x: moveX, y: moveY, duration: 1 });
            });

            // Input focus animations
            const inputs = document.querySelectorAll('input');
            inputs.forEach(input => {
                input.addEventListener('focus', () => {
                    gsap.to(input.parentElement, { scale: 1.02, duration: 0.3 });
                });
                input.addEventListener('blur', () => {
                    gsap.to(input.parentElement, { scale: 1, duration: 0.3 });
                });
            });
        });
    </script>
</body>
</html>