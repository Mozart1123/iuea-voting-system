<!DOCTYPE html>
<html lang="en">
<head>
    <meta charset="UTF-8">
    <meta name="viewport" content="width=device-width, initial-scale=1.0">
    <title>IUEA GuildVote – Secure University Elections</title>
    <script src="https://cdn.tailwindcss.com"></script>
    <link rel="preconnect" href="https://fonts.googleapis.com">
    <link rel="preconnect" href="https://fonts.gstatic.com" crossorigin>
    <link href="https://fonts.googleapis.com/css2?family=Inter:wght@300;400;500;600;700;800&display=swap" rel="stylesheet">
    <link rel="stylesheet" href="https://cdnjs.cloudflare.com/ajax/libs/font-awesome/6.4.0/css/all.min.css">
    <script>
        tailwind.config = {
            darkMode: 'class',
            theme: {
                extend: {
                    colors: {
                        primary: '#8B0000',
                        'primary-dark': '#660000',
                        'primary-light': '#A52A2A',
                        secondary: '#4B5563',
                    },
                    animation: {
                        'fade-in': 'fadeIn 0.5s ease-out forwards',
                    },
                    keyframes: {
                        fadeIn: {
                            '0%': { opacity: '0', transform: 'translateY(10px)' },
                            '100%': { opacity: '1', transform: 'translateY(0)' },
                        }
                    }
                }
            }
        }
    </script>
    <style>
        :root {
            --primary: #8B0000;
            --primary-dark: #660000;
            --primary-light: #A52A2A;
            --secondary: #4B5563;
            --bg-main: #ffffff;
            --bg-sec: #f9fafb;
            --bg-card: #ffffff;
            --text-main: #111827;
            --text-sec: #4b5563;
            --border-color: #e5e7eb;
        }

        .dark {
            --bg-main: #0f172a;
            --bg-sec: #1e293b;
            --bg-card: #1e293b;
            --text-main: #f9fafb;
            --text-sec: #9ca3af;
            --border-color: #334155;
        }

        * {
            font-family: 'Inter', sans-serif;
            transition: background-color 0.3s ease, border-color 0.3s ease, color 0.3s ease;
        }

        body {
            background-color: var(--bg-main);
            color: var(--text-main);
        }

        .text-primary { color: var(--primary); }
        .bg-primary { background-color: var(--primary); }
        .border-primary { border-color: var(--primary); }
        
        .feature-card {
            background-color: var(--bg-card);
            border: 1px solid var(--border-color);
            transition: all 0.3s ease;
        }

        .feature-card:hover {
            transform: translateY(-6px);
            box-shadow: 0 20px 30px -10px rgba(139, 0, 0, 0.15);
            border-color: var(--primary) !important;
        }

        .animate-float {
            animation: float 5s ease-in-out infinite;
        }

        @keyframes float {
            0% { transform: translateY(0px); }
            50% { transform: translateY(-10px); }
            100% { transform: translateY(0px); }
        }

        .scroll-smooth {
            scroll-behavior: smooth;
        }

        .glass-nav {
            background: rgba(var(--bg-main-rgb, 255, 255, 255), 0.8);
            backdrop-filter: blur(12px);
            border-bottom: 1px solid var(--border-color);
        }
    </style>
</head>
<body class="scroll-smooth antialiased">

    <!-- Navigation (White + Dark red accent) -->
    <nav class="fixed w-full bg-white/80 backdrop-blur-md z-50 border-b border-gray-200">
        <div class="container mx-auto px-6 py-4">
            <div class="flex justify-between items-center">
                <div class="flex items-center space-x-3">
                    <img src="https://iuea.ac.ug/sitepad-data/uploads/2024/07/IUEA-Logo-official-1.png" alt="IUEA Logo" class="w-16 h-16 object-contain">
        
                </div>
                
                <div class="hidden md:flex space-x-8 text-gray-700 font-medium">
                    <a href="#features" class="hover:text-primary transition">Features</a>
                    <a href="#how-it-works" class="hover:text-primary transition">How it works</a>
                    <a href="#security" class="hover:text-primary transition">Security</a>
                    <a href="#testimonials" class="hover:text-primary transition">Testimonials</a>
                </div>
                
                <div class="flex items-center space-x-4">
                    <button id="theme-toggle" class="p-2 rounded-lg text-secondary hover:text-primary transition-colors">
                        <i class="fas fa-moon dark:hidden"></i>
                        <i class="fas fa-sun hidden dark:block text-yellow-400"></i>
                    </button>
                    <a href="{{ route('login') }}" class="hidden md:inline-block px-5 py-2 text-primary border border-primary rounded-lg hover:bg-white hover:text-black transition-all font-semibold">Login</a>
                    <a href="{{ route('register') }}" class="px-5 py-2 bg-primary text-white rounded-lg shadow-md hover:bg-primary-dark transition font-semibold">Register</a>
                    <button id="mobile-menu-btn" class="md:hidden text-secondary">
                        <i class="fas fa-bars text-2xl"></i>
                    </button>
                </div>
            </div>
            
            <!-- Mobile menu -->
            <div id="mobile-menu" class="md:hidden hidden mt-4 pb-4 space-y-3">
                <a href="#features" class="block hover:text-primary transition">Features</a>
                <a href="#how-it-works" class="block hover:text-primary transition">How it works</a>
                <a href="#security" class="block hover:text-primary transition">Security</a>
                <a href="#testimonials" class="block hover:text-primary transition">Testimonials</a>
                <a href="{{ route('login') }}" class="block text-primary font-semibold">Login</a>
            </div>
        </div>
    </nav>

    <!-- Hero Section – CSS variable backgrounds -->
    <section class="pt-32 pb-20 overflow-hidden" style="background-color: var(--bg-main);">
        <div class="container mx-auto px-6">
            <div class="flex flex-col lg:flex-row items-center">
                <div class="lg:w-1/2 mb-16 lg:mb-0">
                    <div class="inline-flex items-center px-4 py-2 rounded-full text-sm font-semibold mb-6 border border-primary/20" style="background-color: rgba(139, 0, 0, 0.05); color: var(--primary);">
                        <span class="w-2 h-2 bg-primary rounded-full mr-2"></span> Built with Laravel 10
                    </div>
                    <h1 class="text-4xl md:text-5xl lg:text-6xl font-bold leading-tight tracking-tight" style="color: var(--text-main);">
                        <span class="block">Secure.</span>
                        <span class="block">Transparent.</span>
                        <span class="block text-primary">Trusted.</span>
                    </h1>
                    <p class="text-lg md:text-xl mt-6 max-w-xl" style="color: var(--text-sec);">
                        The digital voting solution for IUEA Student Guild Elections. 
                        Real‑time, verifiable, and built with enterprise‑grade Laravel security.
                    </p>
                    <div class="flex flex-col sm:flex-row gap-4 mt-10">
                        <a href="{{ route('register') }}" class="px-8 py-4 bg-primary text-white rounded-xl font-bold shadow-lg hover:shadow-xl transition transform hover:-translate-y-1 text-center">
                            Register <i class="fas fa-arrow-right ml-2"></i>
                        </a>
                        <a href="{{ route('login') }}" class="px-8 py-4 bg-transparent text-primary border-2 border-primary rounded-xl font-bold hover:bg-white hover:text-black hover:border-white transition-all text-center">
                            Login
                        </a>
                    </div>
                    
                    <div class="flex items-center space-x-8 mt-12">
                        <div>
                            <p class="text-2xl font-bold text-gray-900 stat-value" data-target="99.9">0%</p>
                            <p class="text-gray-500 text-sm">Uptime SLA</p>
                        </div>
                        <div class="w-px h-10 bg-gray-300"></div>
                        <div>
                            <p class="text-2xl font-bold text-gray-900 stat-value" data-target="0">0</p>
                            <p class="text-gray-500 text-sm">Disputes</p>
                        </div>
                        <div class="w-px h-10 bg-gray-300"></div>
                        <div>
                            <p class="text-2xl font-bold text-gray-900 stat-value" data-target="100">0%</p>
                            <p class="text-gray-500 text-sm">Verifiable</p>
                        </div>
                    </div>
                </div>
                
                <!-- Dashboard mockup – clean, white card with red accents -->
                <div class="lg:w-1/2 relative">
                    <div class="relative z-10">
                        <div class="rounded-2xl shadow-2xl border p-6" style="background-color: var(--bg-card); border-color: var(--border-color);">
                            <div class="flex items-center justify-between mb-6">
                                <div class="flex items-center space-x-3">
                                    <div class="w-3 h-3 bg-primary rounded-full"></div>
                                    <span class="font-semibold text-gray-700">Live Dashboard</span>
                                </div>
                                <span class="text-xs bg-green-100 text-green-700 px-3 py-1 rounded-full">real‑time</span>
                            </div>
                            <div class="grid grid-cols-2 gap-4 mb-6">
                                <div class="p-4 rounded-xl" style="background-color: var(--bg-sec);">
                                    <p style="color: var(--text-sec);" class="text-sm">Turnout</p>
                                    <p class="text-2xl font-bold" style="color: var(--text-main);">78.2%</p>
                                </div>
                                <div class="p-4 rounded-xl" style="background-color: var(--bg-sec);">
                                    <p style="color: var(--text-sec);" class="text-sm">Votes cast</p>
                                    <p class="text-2xl font-bold" style="color: var(--text-main);">4,823</p>
                                </div>
                            </div>
                            <div class="space-y-4">
                                <div class="flex justify-between text-sm">
                                    <span class="font-medium">J. Musoke</span>
                                    <span class="text-gray-700">1,842</span>
                                </div>
                                <div class="w-full bg-gray-200 rounded-full h-2">
                                    <div class="bg-primary h-2 rounded-full" style="width: 38%"></div>
                                </div>
                                <div class="flex justify-between text-sm">
                                    <span class="font-medium">S. Mutesi</span>
                                    <span class="text-gray-700">1,540</span>
                                </div>
                                <div class="w-full bg-gray-200 rounded-full h-2">
                                    <div class="bg-blue-500 h-2 rounded-full" style="width: 32%"></div>
                                </div>
                                <div class="flex justify-between text-sm">
                                    <span class="font-medium">D. Akello</span>
                                    <span class="text-gray-700">1,441</span>
                                </div>
                                <div class="w-full bg-gray-200 rounded-full h-2">
                                    <div class="bg-green-500 h-2 rounded-full" style="width: 30%"></div>
                                </div>
                            </div>
                            <div class="mt-6 pt-4 border-t border-gray-200 text-center text-gray-400 text-xs">
                                Last sync: just now
                            </div>
                        </div>
                    </div>
                    <!-- subtle decoration -->
                    <div class="absolute -top-6 -right-6 w-48 h-48 bg-primary/5 rounded-full blur-3xl -z-10"></div>
                </div>
            </div>
        </div>
    </section>

    <!-- Problem & Solution – 3 columns, clean white cards, red accents -->
    <section class="py-20" style="background-color: var(--bg-sec);">
        <div class="container mx-auto px-6">
            <div class="text-center max-w-3xl mx-auto mb-16">
                <span class="text-primary font-semibold text-sm uppercase tracking-wider">Why change?</span>
                <h2 class="text-3xl md:text-4xl font-bold mt-3" style="color: var(--text-main);">Problems with traditional voting, solved.</h2>
                <p class="text-lg mt-4" style="color: var(--text-sec);">Paper ballots and manual counts create friction, fraud risks, and distrust. GuildVote brings modern integrity.</p>
            </div>
            <div class="grid md:grid-cols-3 gap-10">
                <div class="p-8 rounded-2xl border shadow-sm hover:shadow-lg transition" style="background-color: var(--bg-card); border-color: var(--border-color);">
                    <div class="w-14 h-14 rounded-xl flex items-center justify-center mb-6" style="background-color: rgba(139, 0, 0, 0.1);">
                        <i class="fas fa-exclamation-triangle text-primary text-2xl"></i>
                    </div>
                    <h3 class="text-xl font-bold mb-3" style="color: var(--text-main);">Fraud &amp; errors</h3>
                    <p class="mb-5" style="color: var(--text-sec);">Manual counting is slow and vulnerable to manipulation.</p>
                    <div class="border-t pt-5" style="border-color: var(--border-color);">
                        <span class="font-semibold text-primary">Solution</span>
                        <p class="mt-2" style="color: var(--text-main);">End‑to‑end encryption + immutable audit trails.</p>
                    </div>
                </div>
                <div class="bg-white p-8 rounded-2xl border border-gray-200 shadow-sm hover:shadow-lg transition">
                    <div class="w-14 h-14 bg-red-100 rounded-xl flex items-center justify-center mb-6">
                        <i class="fas fa-clock text-primary text-2xl"></i>
                    </div>
                    <h3 class="text-xl font-bold mb-3">Delayed results</h3>
                    <p class="text-gray-600 mb-5">Counting takes days, prolonging uncertainty.</p>
                    <div class="border-t border-gray-200 pt-5">
                        <span class="font-semibold text-primary">Solution</span>
                        <p class="text-gray-700 mt-2">Real‑time dashboard, results the second polls close.</p>
                    </div>
                </div>
                <div class="bg-white p-8 rounded-2xl border border-gray-200 shadow-sm hover:shadow-lg transition">
                    <div class="w-14 h-14 bg-red-100 rounded-xl flex items-center justify-center mb-6">
                        <i class="fas fa-eye-slash text-primary text-2xl"></i>
                    </div>
                    <h3 class="text-xl font-bold mb-3">No transparency</h3>
                    <p class="text-gray-600 mb-5">Students can't verify their vote was counted.</p>
                    <div class="border-t border-gray-200 pt-5">
                        <span class="font-semibold text-primary">Solution</span>
                        <p class="text-gray-700 mt-2">Public audit logs, voter‑verifiable paper trails.</p>
                    </div>
                </div>
            </div>
        </div>
    </section>

    <!-- Features – white cards, red borders on hover -->
    <section id="features" class="py-20" style="background-color: var(--bg-main);">
        <div class="container mx-auto px-6">
            <div class="text-center max-w-3xl mx-auto mb-16">
                <span class="text-primary font-semibold text-sm uppercase tracking-wider">Powerful features</span>
                <h2 class="text-3xl md:text-4xl font-bold mt-3" style="color: var(--text-main);">Everything you need for flawless elections</h2>
                <p class="text-lg mt-4" style="color: var(--text-sec);">Built on Laravel’s rock-solid foundation, designed for university‑scale.</p>
            </div>
            <div class="grid md:grid-cols-2 lg:grid-cols-3 gap-8">
                <div class="feature-card p-8 rounded-2xl border transition">
                    <div class="w-12 h-12 rounded-lg flex items-center justify-center mb-5" style="background-color: rgba(139, 0, 0, 0.1);">
                        <i class="fas fa-lock text-primary text-xl"></i>
                    </div>
                    <h3 class="text-lg font-bold mb-2" style="color: var(--text-main);">End‑to‑end encryption</h3>
                    <p class="text-sm leading-relaxed" style="color: var(--text-sec);">Votes are encrypted on the client and never visible in plaintext.</p>
                </div>
                <div class="feature-card bg-white p-8 rounded-2xl border border-gray-200 hover:border-primary transition">
                    <div class="w-12 h-12 bg-primary/10 rounded-lg flex items-center justify-center mb-5">
                        <i class="fas fa-chart-line text-primary text-xl"></i>
                    </div>
                    <h3 class="text-lg font-bold mb-2">Real‑time counting</h3>
                    <p class="text-gray-500 text-sm leading-relaxed">Results update instantly, accessible to authorised roles.</p>
                </div>
                <div class="feature-card bg-white p-8 rounded-2xl border border-gray-200 hover:border-primary transition">
                    <div class="w-12 h-12 bg-primary/10 rounded-lg flex items-center justify-center mb-5">
                        <i class="fas fa-fingerprint text-primary text-xl"></i>
                    </div>
                    <h3 class="text-lg font-bold mb-2">Student ID auth</h3>
                    <p class="text-gray-500 text-sm leading-relaxed">Seamless integration with university identity systems.</p>
                </div>
                <div class="feature-card bg-white p-8 rounded-2xl border border-gray-200 hover:border-primary transition">
                    <div class="w-12 h-12 bg-primary/10 rounded-lg flex items-center justify-center mb-5">
                        <i class="fas fa-user-shield text-primary text-xl"></i>
                    </div>
                    <h3 class="text-lg font-bold mb-2">Role‑based access</h3>
                    <p class="text-gray-500 text-sm leading-relaxed">Granular permissions for EC, IT, observers.</p>
                </div>
                <div class="feature-card bg-white p-8 rounded-2xl border border-gray-200 hover:border-primary transition">
                    <div class="w-12 h-12 bg-primary/10 rounded-lg flex items-center justify-center mb-5">
                        <i class="fas fa-file-alt text-primary text-xl"></i>
                    </div>
                    <h3 class="text-lg font-bold mb-2">Audit logs</h3>
                    <p class="text-gray-500 text-sm leading-relaxed">Every action recorded; full transparency reports.</p>
                </div>
                <div class="feature-card bg-white p-8 rounded-2xl border border-gray-200 hover:border-primary transition">
                    <div class="w-12 h-12 bg-primary/10 rounded-lg flex items-center justify-center mb-5">
                        <i class="fas fa-mobile-alt text-primary text-xl"></i>
                    </div>
                    <h3 class="text-lg font-bold mb-2">Mobile‑first</h3>
                    <p class="text-gray-500 text-sm leading-relaxed">Flawless voting on phones, tablets, laptops.</p>
                </div>
            </div>
        </div>
    </section>

    <!-- How It Works – 3 steps, dark red circles, white bg -->
    <section id="how-it-works" class="py-20" style="background-color: var(--bg-sec);">
        <div class="container mx-auto px-6">
            <div class="text-center max-w-3xl mx-auto mb-16">
                <span class="text-primary font-semibold text-sm uppercase tracking-wider">Simple process</span>
                <h2 class="text-3xl md:text-4xl font-bold mt-3 text-gray-900">How GuildVote works</h2>
                <p class="text-gray-600 text-lg mt-4">Three steps from verification to result.</p>
            </div>
            <div class="flex flex-col md:flex-row justify-center items-start gap-8 md:gap-0">
                <div class="md:w-1/3 text-center px-6">
                    <div class="relative inline-flex">
                        <div class="w-20 h-20 bg-primary text-white rounded-full flex items-center justify-center text-3xl font-bold shadow-lg">1</div>
                        <div class="absolute -bottom-2 -right-2 w-8 h-8 bg-white border-2 border-primary rounded-full flex items-center justify-center text-primary text-xs"><i class="fas fa-check"></i></div>
                    </div>
                    <h3 class="text-xl font-bold mt-6 mb-2">Student verification</h3>
                    <p class="text-gray-600">Login with IUEA credentials. Instant eligibility check.</p>
                </div>
                <div class="hidden md:flex items-center self-center text-primary text-2xl font-light">→</div>
                <div class="md:w-1/3 text-center px-6">
                    <div class="relative inline-flex">
                        <div class="w-20 h-20 bg-primary text-white rounded-full flex items-center justify-center text-3xl font-bold shadow-lg">2</div>
                    </div>
                    <h3 class="text-xl font-bold mt-6 mb-2">Secure ballot</h3>
                    <p class="text-gray-600">Cast encrypted votes on an intuitive, accessible interface.</p>
                </div>
                <div class="hidden md:flex items-center self-center text-primary text-2xl font-light">→</div>
                <div class="md:w-1/3 text-center px-6">
                    <div class="relative inline-flex">
                        <div class="w-20 h-20 bg-primary text-white rounded-full flex items-center justify-center text-3xl font-bold shadow-lg">3</div>
                    </div>
                    <h3 class="text-xl font-bold mt-6 mb-2">Real‑time results</h3>
                    <p class="text-gray-600">Results are published instantly with full audit trails.</p>
                </div>
            </div>
        </div>
    </section>

    <!-- Security & Trust – Laravel security, white card with red accent -->
    <section id="security" class="py-20" style="background-color: var(--bg-main);">
        <div class="container mx-auto px-6">
            <div class="text-center max-w-3xl mx-auto mb-16">
                <span class="text-primary font-semibold text-sm uppercase tracking-wider">Laravel powered</span>
                <h2 class="text-3xl md:text-4xl font-bold mt-3 text-gray-900">Security you can trust</h2>
                <p class="text-gray-600 text-lg mt-4">We inherit Laravel’s best‑in‑class defenses and add election‑specific safeguards.</p>
            </div>
            <div class="grid md:grid-cols-2 lg:grid-cols-4 gap-8">
                <div class="bg-gray-50 p-6 rounded-xl border border-gray-200">
                    <div class="w-10 h-10 bg-primary/10 rounded-lg flex items-center justify-center mb-4">
                        <i class="fas fa-shield-alt text-primary"></i>
                    </div>
                    <h4 class="font-bold">CSRF protection</h4>
                    <p class="text-gray-500 text-sm mt-1">Automatic tokens on every form.</p>
                </div>
                <div class="bg-gray-50 p-6 rounded-xl border border-gray-200">
                    <div class="w-10 h-10 bg-primary/10 rounded-lg flex items-center justify-center mb-4">
                        <i class="fas fa-user-lock text-primary"></i>
                    </div>
                    <h4 class="font-bold">Authentication</h4>
                    <p class="text-gray-500 text-sm mt-1">Multi‑factor ready, secure sessions.</p>
                </div>
                <div class="bg-gray-50 p-6 rounded-xl border border-gray-200">
                    <div class="w-10 h-10 bg-primary/10 rounded-lg flex items-center justify-center mb-4">
                        <i class="fas fa-database text-primary"></i>
                    </div>
                    <h4 class="font-bold">Database integrity</h4>
                    <p class="text-gray-500 text-sm mt-1">Prepared statements, encryption at rest.</p>
                </div>
                <div class="bg-gray-50 p-6 rounded-xl border border-gray-200">
                    <div class="w-10 h-10 bg-primary/10 rounded-lg flex items-center justify-center mb-4">
                        <i class="fas fa-search text-primary"></i>
                    </div>
                    <h4 class="font-bold">Server validation</h4>
                    <p class="text-gray-500 text-sm mt-1">All votes re‑validated on backend.</p>
                </div>
            </div>
            <!-- Audit transparency highlight -->
            <div class="mt-16 bg-gray-50 rounded-3xl p-10 border border-gray-200 flex flex-col lg:flex-row items-center gap-10">
                <div class="lg:w-2/3">
                    <h3 class="text-2xl font-bold mb-4 flex items-center gap-2">
                        <i class="fas fa-file-invoice text-primary"></i> 
                        Audit transparency
                    </h3>
                    <p class="text-gray-700 mb-4">Every vote, every login, every configuration change — all recorded in an immutable audit log. Generate public reports without revealing individual votes.</p>
                    <ul class="space-y-2 text-gray-600">
                        <li class="flex items-start"><i class="fas fa-check text-primary mt-1 mr-3"></i> Verify total votes = eligible voters who cast ballot</li>
                        <li class="flex items-start"><i class="fas fa-check text-primary mt-1 mr-3"></i> No votes altered after submission</li>
                        <li class="flex items-start"><i class="fas fa-check text-primary mt-1 mr-3"></i> Full timestamp and actor trail</li>
                    </ul>
                </div>
                <div class="lg:w-1/3 bg-white p-6 rounded-xl shadow-sm border border-gray-200 w-full">
                    <div class="flex items-center gap-3 mb-4">
                        <i class="fas fa-file-pdf text-primary text-2xl"></i>
                        <span class="font-semibold">Sample audit report</span>
                    </div>
                    <div class="text-sm space-y-2">
                        <div class="flex justify-between"><span>Election ID:</span><span class="font-mono">#G2023-01</span></div>
                        <div class="flex justify-between"><span>Votes cast:</span><span>4,823</span></div>
                        <div class="flex justify-between"><span>Audit hash:</span><span class="text-xs text-gray-500">a1b2c3…</span></div>
                        <div class="flex justify-between text-primary font-semibold"><span>Integrity:</span><span>100% verified</span></div>
                    </div>
                </div>
            </div>
        </div>
    </section>

    <!-- Testimonials – white cards, subtle red quote marks -->
    <section id="testimonials" class="py-20" style="background-color: var(--bg-sec);">
        <div class="container mx-auto px-6">
            <div class="text-center max-w-3xl mx-auto mb-16">
                <span class="text-primary font-semibold text-sm uppercase tracking-wider">Testimonials</span>
                <h2 class="text-3xl md:text-4xl font-bold mt-3 text-gray-900">Trusted by the IUEA community</h2>
            </div>
            <div class="grid md:grid-cols-3 gap-8">
                <div class="bg-white p-8 rounded-2xl border border-gray-200 shadow-sm">
                    <i class="fas fa-quote-left text-primary/30 text-4xl mb-4"></i>
                    <p class="text-gray-700 italic mb-6">“The first election with zero disputes. Students trust the system because they can verify everything.”</p>
                    <div class="flex items-center">
                        <div class="w-12 h-12 bg-primary/20 rounded-full flex items-center justify-center text-primary font-bold">JK</div>
                        <div class="ml-4">
                            <p class="font-bold">John K. Musoke</p>
                            <p class="text-gray-500 text-sm">Guild President 2023</p>
                        </div>
                    </div>
                </div>
                <div class="bg-white p-8 rounded-2xl border border-gray-200 shadow-sm">
                    <i class="fas fa-quote-left text-primary/30 text-4xl mb-4"></i>
                    <p class="text-gray-700 italic mb-6">“Laravel security combined with an intuitive UI — exactly what we needed for campus‑wide elections.”</p>
                    <div class="flex items-center">
                        <div class="w-12 h-12 bg-primary/20 rounded-full flex items-center justify-center text-primary font-bold">SN</div>
                        <div class="ml-4">
                            <p class="font-bold">Sarah Nalwoga</p>
                            <p class="text-gray-500 text-sm">Head of IT</p>
                        </div>
                    </div>
                </div>
                <div class="bg-white p-8 rounded-2xl border border-gray-200 shadow-sm">
                    <i class="fas fa-quote-left text-primary/30 text-4xl mb-4"></i>
                    <p class="text-gray-700 italic mb-6">“Transparency reports gave us the confidence to certify results immediately. A game changer.”</p>
                    <div class="flex items-center">
                        <div class="w-12 h-12 bg-primary/20 rounded-full flex items-center justify-center text-primary font-bold">DO</div>
                        <div class="ml-4">
                            <p class="font-bold">Dr. David Okello</p>
                            <p class="text-gray-500 text-sm">Dean of Students</p>
                        </div>
                    </div>
                </div>
            </div>
        </div>
    </section>

    <!-- CTA – clean white card with red gradient? Actually white with red buttons -->
    <section class="py-20 border-t" style="background-color: var(--bg-main); border-color: var(--border-color);">
        <div class="container mx-auto px-6 text-center">
            <h2 class="text-4xl md:text-5xl font-bold mb-6" style="color: var(--text-main);">Bring digital democracy to <span class="text-primary">IUEA</span></h2>
            <p class="text-xl text-gray-600 max-w-3xl mx-auto mb-10">Join the universities that have made their student elections secure, transparent, and instant.</p>
            <div class="flex flex-col sm:flex-row justify-center gap-6">
                <a href="{{ route('register') }}" class="px-10 py-5 bg-primary text-white rounded-xl font-bold text-lg shadow-lg hover:shadow-xl transition transform hover:-translate-y-1">
                    <i class="fas fa-rocket mr-2"></i> Register
                </a>
                <a href="{{ route('login') }}" class="px-10 py-5 border-2 border-primary rounded-xl font-bold text-lg hover:bg-white hover:text-black hover:border-white transition-all shadow-sm" style="color: var(--primary);">
                    <i class="fas fa-sign-in-alt mr-2"></i> Login
                </a>
            </div>
            <div class="grid grid-cols-2 md:grid-cols-4 gap-8 max-w-3xl mx-auto mt-16">
                <div><span class="text-3xl font-bold text-gray-900 stat-value" data-target="10">0</span><p class="text-gray-500 text-sm">elections</p></div>
                <div><span class="text-3xl font-bold text-gray-900 stat-value" data-target="50000">0</span><p class="text-gray-500 text-sm">votes</p></div>
                <div><span class="text-3xl font-bold text-gray-900 stat-value" data-target="100">0%</span><p class="text-gray-500 text-sm">uptime</p></div>
                <div><span class="text-3xl font-bold text-gray-900">24/7</span><p class="text-gray-500 text-sm">support</p></div>
            </div>
        </div>
    </section>

    <!-- Footer – Deep Midnight Blue (Black Blue) background -->
    <footer class="border-t py-12" style="background-color: #0f172a; border-color: rgba(255,255,255,0.05);">
        <div class="container mx-auto px-6">
            <div class="flex flex-col md:flex-row justify-between items-start gap-10">
                <div class="max-w-xs">
                    <div class="flex items-center space-x-3 mb-4">
                        <img src="https://iuea.ac.ug/sitepad-data/uploads/2024/07/IUEA-Logo-official-1.png" alt="IUEA Logo" class="w-20 h-20 object-contain brightness-0 invert">
                        <span class="font-bold text-xl text-white">IUEA <span class="text-red-400">GuildVote</span></span>
                    </div>
                    <p class="text-sm leading-relaxed text-gray-200">Official voting platform for the International University of East Africa. Secure, transparent, trusted.</p>
                </div>
                <div class="grid grid-cols-2 md:grid-cols-4 gap-10 flex-1">
                    <div>
                        <h4 class="font-bold mb-4 text-white">Product</h4>
                        <ul class="space-y-2 text-sm text-gray-300">
                            <li><a href="#features" class="hover:text-white transition">Features</a></li>
                            <li><a href="#security" class="hover:text-white transition">Security</a></li>
                            <li><a href="{{ route('register') }}" class="hover:text-white transition">Register</a></li>
                        </ul>
                    </div>
                    <div>
                        <h4 class="font-bold mb-4 text-white">University</h4>
                        <ul class="space-y-2 text-sm text-gray-300">
                            <li><a href="#" class="hover:text-white transition">About IUEA</a></li>
                            <li><a href="#" class="hover:text-white transition">Student Guild</a></li>
                            <li><a href="#" class="hover:text-white transition">Election Commission</a></li>
                        </ul>
                    </div>
                    <div>
                        <h4 class="font-bold mb-4 text-white">Legal</h4>
                        <ul class="space-y-2 text-sm text-gray-300">
                            <li><a href="#" class="hover:text-white transition">Privacy</a></li>
                            <li><a href="#" class="hover:text-white transition">Terms</a></li>
                            <li><a href="#" class="hover:text-white transition">Election guidelines</a></li>
                        </ul>
                    </div>
                    <div>
                        <h4 class="font-bold mb-4 text-white">Contact</h4>
                        <ul class="space-y-2 text-sm text-gray-300">
                            <li><i class="fas fa-map-marker-alt text-red-400 mr-2"></i> Kampala</li>
                            <li><i class="fas fa-envelope text-red-400 mr-2"></i> guildvote@iuea.ac.ug</li>
                            <li><i class="fas fa-phone text-red-400 mr-2"></i> +256 41 466 7234</li>
                        </ul>
                    </div>
                </div>
            </div>
            <div class="border-t border-white/10 mt-10 pt-8 flex flex-col md:flex-row justify-between items-center text-sm text-gray-300">
                <p>© 2023 IUEA GuildVote. All rights reserved.</p>
                <div class="flex space-x-6 mt-4 md:mt-0">
                    <a href="#" class="hover:text-white transition"><i class="fab fa-twitter"></i></a>
                    <a href="#" class="hover:text-white transition"><i class="fab fa-linkedin"></i></a>
                    <a href="#" class="hover:text-white transition"><i class="fab fa-facebook"></i></a>
                    <a href="#" class="hover:text-white transition"><i class="fab fa-instagram"></i></a>
                </div>
            </div>
        </div>
    </footer>

    <!-- JavaScript -->
    <script>
        // Theme Toggle Logic
        const themeToggle = document.getElementById('theme-toggle');
        const html = document.documentElement;
        
        // Initial state
        if (localStorage.theme === 'dark' || (!('theme' in localStorage) && window.matchMedia('(prefers-color-scheme: dark)').matches)) {
            html.classList.add('dark');
        } else {
            html.classList.remove('dark');
        }

        themeToggle.addEventListener('click', () => {
            html.classList.toggle('dark');
            if (html.classList.contains('dark')) {
                localStorage.theme = 'dark';
            } else {
                localStorage.theme = 'light';
            }
        });

        // Mobile Menu Toggle
        const mobileMenuBtn = document.getElementById('mobile-menu-btn');
        const mobileMenu = document.getElementById('mobile-menu');
        
        if (mobileMenuBtn && mobileMenu) {
            mobileMenuBtn.addEventListener('click', () => {
                mobileMenu.classList.toggle('hidden');
                mobileMenu.classList.toggle('animate-fade-in');
            });

            document.querySelectorAll('#mobile-menu a').forEach(link => {
                link.addEventListener('click', () => {
                    mobileMenu.classList.add('hidden');
                });
            });
        }

        // Navbar Scroll Effect
        const nav = document.querySelector('nav');
        window.addEventListener('scroll', () => {
            if (window.scrollY > 50) {
                nav.classList.add('shadow-lg', 'py-2');
                nav.style.backgroundColor = 'var(--bg-main)';
                nav.classList.remove('py-4');
            } else {
                nav.classList.remove('shadow-lg', 'py-2');
                nav.style.backgroundColor = 'transparent';
                nav.classList.add('py-4');
            }
        });

        // Smooth Scroll for Anchors
        document.querySelectorAll('a[href^="#"]').forEach(anchor => {
            anchor.addEventListener('click', function(e) {
                const targetId = this.getAttribute('href');
                if (targetId === '#') return;
                
                const targetElement = document.querySelector(targetId);
                if (targetElement) {
                    e.preventDefault();
                    const offset = 80;
                    const bodyRect = document.body.getBoundingClientRect().top;
                    const elementRect = targetElement.getBoundingClientRect().top;
                    const elementPosition = elementRect - bodyRect;
                    const offsetPosition = elementPosition - offset;

                    window.scrollTo({
                        top: offsetPosition,
                        behavior: 'smooth'
                    });
                }
            });
        });

        // Statistics Animation (Counter)
        const animateStats = (el) => {
            const target = parseFloat(el.getAttribute('data-target'));
            const isPercentage = el.textContent.includes('%') || target === 100 || target === 99.9;
            const duration = 2000; // 2 seconds
            const startTime = performance.now();
            
            const updateCounter = (currentTime) => {
                const elapsed = currentTime - startTime;
                const progress = Math.min(elapsed / duration, 1);
                
                // Easing function: easeOutExpo
                const easeProgress = progress === 1 ? 1 : 1 - Math.pow(2, -10 * progress);
                
                let currentValue = easeProgress * target;
                
                if (isPercentage) {
                    el.textContent = currentValue.toFixed(target % 1 === 0 ? 0 : 1) + '%';
                } else {
                    el.textContent = Math.floor(currentValue).toLocaleString() + (target > 1000 ? '' : '+');
                }
                
                if (progress < 1) {
                    requestAnimationFrame(updateCounter);
                }
            };
            
            requestAnimationFrame(updateCounter);
        };

        // Intersection Observer for Reveal Animations
        const revealObserver = new IntersectionObserver((entries) => {
            entries.forEach(entry => {
                if (entry.isIntersecting) {
                    entry.target.classList.add('opacity-100', 'translate-y-0');
                    entry.target.classList.remove('opacity-0', 'translate-y-10');
                    
                    // Trigger stat animation if it's a stat element
                    if (entry.target.classList.contains('stat-value')) {
                        animateStats(entry.target);
                    }
                    
                    revealObserver.unobserve(entry.target);
                }
            });
        }, { threshold: 0.15 });

        // Initialize Reveal Elements
        document.querySelectorAll('.feature-card, section h2, section p, .stat-value').forEach(el => {
            if (!el.classList.contains('stat-value')) {
                el.classList.add('opacity-0', 'translate-y-10', 'transition-all', 'duration-1000', 'ease-out');
            }
            revealObserver.observe(el);
        });

        // Subtle Mouse Parallax for Hero Image
        const heroContainer = document.querySelector('section');
        const heroMockup = document.querySelector('.lg\\:w-1\\/2.relative');
        
        if (heroContainer && heroMockup) {
            heroContainer.addEventListener('mousemove', (e) => {
                const { clientX, clientY } = e;
                const xPos = (clientX / window.innerWidth - 0.5) * 20;
                const yPos = (clientY / window.innerHeight - 0.5) * 20;
                
                heroMockup.style.transform = `translate3d(${xPos}px, ${yPos}px, 0)`;
            });
        }
    </script>
</body>
</html>