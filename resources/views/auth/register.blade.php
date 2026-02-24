<!DOCTYPE html>
<html lang="en">
<head>
    <meta charset="UTF-8">
    <meta name="viewport" content="width=device-width, initial-scale=1.0">
    <title>IUEA GuildVote – Register</title>
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
                    Join the digital <br>democracy – <span class="underline decoration-4 decoration-white/40">Register now</span>
                </h1>
                <p class="text-white/80 text-lg mb-8">
                    Create your secure voter profile in minutes. Your data is protected by industry‑leading encryption.
                </p>
                
                <div class="space-y-6">
                    <div class="flex items-start gap-4">
                        <div class="w-10 h-10 bg-white/20 rounded-lg flex items-center justify-center shrink-0">
                            <i class="fas fa-id-card text-white"></i>
                        </div>
                        <div>
                            <p class="font-bold">Verified Identity</p>
                            <p class="text-white/60 text-sm">Valid student credentials only.</p>
                        </div>
                    </div>
                    <div class="flex items-start gap-4">
                        <div class="w-10 h-10 bg-white/20 rounded-lg flex items-center justify-center shrink-0">
                            <i class="fas fa-lock text-white"></i>
                        </div>
                        <div>
                            <p class="font-bold">End-to-End Encryption</p>
                            <p class="text-white/60 text-sm">Your vote remains anonymous and private.</p>
                        </div>
                    </div>
                </div>
            </div>
        </div>
        
        <div class="relative z-10 text-white/60 text-sm mt-12">
            © 2026 IUEA – Secure Voter Enrollment
        </div>
    </div>

    <!-- RIGHT PANEL -->
    <div id="right-panel" class="md:w-1/2 bg-white flex items-center justify-center p-6 md:p-10">
        <div class="w-full max-w-md">
            <div class="mb-8">
                <h2 class="text-3xl font-bold text-gray-900 mb-2">Create Account</h2>
                <p class="text-gray-500">Register to participate in the upcoming elections.</p>
            </div>
            
            <form id="auth-form" action="{{ route('register') }}" method="POST" enctype="multipart/form-data" class="space-y-4">
                @csrf
                
                <!-- Full Name -->
                <div>
                    <label class="block text-sm font-semibold text-gray-700 mb-1">Full Name</label>
                    <div class="relative">
                        <div class="absolute inset-y-0 left-0 pl-3 flex items-center pointer-events-none">
                            <i class="fas fa-user text-gray-400"></i>
                        </div>
                        <input type="text" name="name" value="{{ old('name') }}" placeholder="John Doe" 
                               class="w-full pl-10 pr-4 py-2.5 border border-gray-300 rounded-xl bg-gray-50 focus:bg-white focus:ring-2 focus:ring-primary/20 focus:border-primary transition duration-200 text-gray-900 @error('name') border-red-500 @enderror" required>
                    </div>
                    @error('name')
                        <p class="text-red-500 text-xs mt-1">{{ $message }}</p>
                    @enderror
                </div>

                <!-- Profile Photo -->
                <div>
                    <label class="block text-sm font-semibold text-gray-700 mb-1">Profile Photo (Optional)</label>
                    <div class="relative">
                        <div class="absolute inset-y-0 left-0 pl-3 flex items-center pointer-events-none">
                            <i class="fas fa-image text-gray-400"></i>
                        </div>
                        <input type="file" name="profile_photo" class="w-full pl-10 pr-4 py-1.5 border border-gray-300 rounded-xl bg-gray-50 focus:bg-white focus:ring-2 focus:ring-primary/20 focus:border-primary transition duration-200 text-gray-900" accept="image/*">
                    </div>
                </div>

                <!-- Email -->
                <div>
                    <label class="block text-sm font-semibold text-gray-700 mb-1">Personal Email (Gmail)</label>
                    <div class="relative">
                        <div class="absolute inset-y-0 left-0 pl-3 flex items-center pointer-events-none">
                            <i class="fas fa-envelope text-gray-400"></i>
                        </div>
                        <input type="email" name="email" value="{{ old('email') }}" placeholder="username@gmail.com" 
                               class="w-full pl-10 pr-4 py-2.5 border border-gray-300 rounded-xl bg-gray-50 focus:bg-white focus:ring-2 focus:ring-primary/20 focus:border-primary transition duration-200 text-gray-900 @error('email') border-red-500 @enderror" required>
                    </div>
                    @error('email')
                        <p class="text-red-500 text-xs mt-1">{{ $message }}</p>
                    @enderror
                </div>

                <!-- Student ID -->
                <div>
                    <label class="block text-sm font-semibold text-gray-700 mb-1">Student ID Number</label>
                    <div class="relative">
                        <div class="absolute inset-y-0 left-0 pl-3 flex items-center pointer-events-none">
                            <i class="fas fa-id-card text-gray-400"></i>
                        </div>
                        <input type="text" name="student_id" value="{{ old('student_id') }}" placeholder="21/U/1234/PS" 
                               class="w-full pl-10 pr-4 py-2.5 border border-gray-300 rounded-xl bg-gray-50 focus:bg-white focus:ring-2 focus:ring-primary/20 focus:border-primary transition duration-200 text-gray-900 @error('student_id') border-red-500 @enderror" required>
                    </div>
                </div>

                <!-- Faculty Selection -->
                <div>
                    <label class="block text-sm font-semibold text-gray-700 mb-1">Your Faculty</label>
                    <div class="relative">
                        <div class="absolute inset-y-0 left-0 pl-3 flex items-center pointer-events-none">
                            <i class="fas fa-graduation-cap text-gray-400"></i>
                        </div>
                        <select name="faculty" class="w-full pl-10 pr-4 py-2.5 border border-gray-300 rounded-xl bg-gray-50 focus:bg-white focus:ring-2 focus:ring-primary/20 focus:border-primary transition duration-200 text-gray-900 @error('faculty') border-red-500 @enderror" required>
                            <option value="">Select Faculty</option>
                            <option value="Science & Technology" {{ old('faculty') == 'Science & Technology' ? 'selected' : '' }}>Science & Technology</option>
                            <option value="Business & Management" {{ old('faculty') == 'Business & Management' ? 'selected' : '' }}>Business & Management</option>
                            <option value="Law" {{ old('faculty') == 'Law' ? 'selected' : '' }}>Law</option>
                            <option value="Social Sciences" {{ old('faculty') == 'Social Sciences' ? 'selected' : '' }}>Social Sciences</option>
                            <option value="Engineering" {{ old('faculty') == 'Engineering' ? 'selected' : '' }}>Engineering</option>
                        </select>
                    </div>
                    @error('faculty')
                        <p class="text-red-500 text-xs mt-1">{{ $message }}</p>
                    @enderror
                </div>

                <!-- Password -->
                <div class="grid grid-cols-1 md:grid-cols-2 gap-4">
                    <div>
                        <label class="block text-sm font-semibold text-gray-700 mb-1">Password</label>
                        <input type="password" name="password" placeholder="••••••••" 
                               class="w-full px-4 py-2.5 border border-gray-300 rounded-xl bg-gray-50 focus:bg-white focus:ring-2 focus:ring-primary/20 focus:border-primary transition duration-200 text-gray-900 @error('password') border-red-500 @enderror" required>
                    </div>
                    <div>
                        <label class="block text-sm font-semibold text-gray-700 mb-1">Confirm</label>
                        <input type="password" name="password_confirmation" placeholder="••••••••" 
                               class="w-full px-4 py-2.5 border border-gray-300 rounded-xl bg-gray-50 focus:bg-white focus:ring-2 focus:ring-primary/20 focus:border-primary transition duration-200 text-gray-900" required>
                    </div>
                </div>
                @error('password')
                    <p class="text-red-500 text-xs mt-1">{{ $message }}</p>
                @enderror

                <button type="submit" id="submit-btn" class="w-full bg-red-50 text-primary border-2 border-primary font-bold py-3 px-4 rounded-xl shadow-md hover:bg-red-100 transition duration-200">
                    Create Voter Profile
                </button>

                <div class="relative my-6">
                    <div class="absolute inset-0 flex items-center">
                        <div class="w-full border-t border-gray-100"></div>
                    </div>
                    <div class="relative flex justify-center text-sm">
                        <span class="px-4 bg-white text-gray-500 uppercase tracking-widest text-[10px] font-black">Or join with</span>
                    </div>
                </div>

                <a href="{{ route('auth.google') }}" class="w-full bg-white border border-gray-300 text-gray-700 font-bold py-3 px-4 rounded-xl shadow-sm hover:shadow-md hover:bg-gray-50 transition duration-200 flex items-center justify-center gap-3">
                    <img src="https://www.gstatic.com/firebasejs/ui/2.0.0/images/auth/google.svg" class="w-5 h-5">
                    <span>Sign in with Google</span>
                </a>
                
                <div class="text-center mt-6">
                    <p class="text-gray-600 text-sm">
                        Already have an account? 
                        <a href="{{ route('login') }}" class="font-semibold text-primary hover:text-primary-dark transition">Log in here</a>
                    </p>
                </div>
            </form>
            
            <div class="flex items-center justify-center gap-4 mt-8 pt-6 border-t border-gray-100 text-gray-400 text-xs">
                <span><i class="fas fa-shield-alt mr-1"></i> Secure enrollment</span>
                <span><i class="fas fa-lock mr-1"></i> Data encrypted</span>
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
