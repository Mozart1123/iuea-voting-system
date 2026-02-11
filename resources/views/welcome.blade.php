<!DOCTYPE html>
<html lang="en" class="scroll-smooth">
<head>
    <meta charset="UTF-8">
    <meta name="viewport" content="width=device-width, initial-scale=1.0">
    <title>IUEA GuildVote | Secure University Elections</title>
    <script src="https://cdn.tailwindcss.com"></script>
    <link href="https://fonts.googleapis.com/css2?family=Inter:wght@300;400;500;600;700;800&display=swap" rel="stylesheet">
    <script src="https://unpkg.com/aos@2.3.1/dist/aos.js"></script>
    <link href="https://unpkg.com/aos@2.3.1/dist/dist/aos.css" rel="stylesheet">
    
    <script>
        tailwind.config = {
            theme: {
                extend: {
                    colors: {
                        brand: {
                            DEFAULT: '#b22222',
                            dark: '#8b1a1a',
                            light: '#d44b4b',
                            surface: '#fff5f5'
                        }
                    }
                }
            }
        }
    </script>

    <style>
        body { font-family: 'Inter', sans-serif; }
        .glass { background: rgba(255, 255, 255, 0.7); backdrop-filter: blur(12px); border: 1px solid rgba(255, 255, 255, 0.3); }
        .red-gradient { background: linear-gradient(135deg, #b22222 0%, #8b1a1a 100%); }
        .soft-red-glow { box-shadow: 0 0 40px -10px rgba(178, 34, 34, 0.3); }
        
        @keyframes pulse-soft {
            0%, 100% { opacity: 0.4; transform: scale(1); }
            50% { opacity: 0.6; transform: scale(1.05); }
        }
        .animate-pulse-soft { animation: pulse-soft 8s infinite; }
    </style>
</head>
<body class="bg-[#fafafa] text-zinc-900 antialiased overflow-x-hidden">

    <nav class="fixed w-full z-50 top-0 px-6 py-6">
        <div class="max-w-7xl mx-auto flex items-center justify-between glass rounded-2xl px-8 py-3 shadow-sm">
            <div class="flex items-center gap-3">
                <div class="w-9 h-9 bg-brand rounded-lg flex items-center justify-center shadow-lg shadow-brand/20">
                    <svg class="w-5 h-5 text-white" fill="none" stroke="currentColor" viewBox="0 0 24 24"><path stroke-linecap="round" stroke-linejoin="round" stroke-width="2.5" d="M9 12l2 2 4-4m6 2a9 9 0 11-18 0 9 9 0 0118 0z"/></svg>
                </div>
                <span class="font-extrabold text-xl tracking-tight text-zinc-900">IUEA <span class="text-brand">GuildVote</span></span>
            </div>
            
            <div class="hidden md:flex items-center gap-10 text-sm font-semibold text-zinc-500">
                <a href="#features" class="hover:text-brand transition-colors">Features</a>
                <a href="#security" class="hover:text-brand transition-colors">Security</a>
                <a href="#audit" class="hover:text-brand transition-colors">Public Audit</a>
            </div>

            <div class="flex items-center gap-5">
                <a href="/login" class="text-sm font-bold text-zinc-600 hover:text-brand transition-colors">Admin Login</a>
                <a href="#demo" class="red-gradient text-white px-6 py-2.5 rounded-xl text-sm font-bold shadow-xl shadow-brand/30 hover:scale-105 transition-all">Request Demo</a>
            </div>
        </div>
    </nav>

    <section class="relative pt-40 pb-20 lg:pt-56 lg:pb-32 overflow-hidden">
        <div class="absolute top-0 left-1/2 -translate-x-1/2 w-full h-full -z-10">
            <div class="absolute top-[-10%] left-[-5%] w-[50%] h-[70%] bg-brand/5 rounded-full blur-[120px] animate-pulse-soft"></div>
            <div class="absolute bottom-[-10%] right-[-5%] w-[40%] h-[60%] bg-orange-500/5 rounded-full blur-[100px] animate-pulse-soft"></div>
        </div>

        <div class="max-w-7xl mx-auto px-6 text-center">
            <div data-aos="fade-up" data-aos-duration="1000">
                <div class="inline-flex items-center gap-2 py-1.5 px-4 rounded-full bg-brand/5 border border-brand/10 text-brand text-xs font-bold uppercase tracking-widest mb-8">
                    <span class="relative flex h-2 w-2">
                        <span class="animate-ping absolute inline-flex h-full w-full rounded-full bg-brand opacity-75"></span>
                        <span class="relative inline-flex rounded-full h-2 w-2 bg-brand"></span>
                    </span>
                    Institutional Digital Democracy
                </div>
                <h1 class="text-6xl lg:text-8xl font-black text-zinc-900 mb-8 leading-[0.95] tracking-tight">
                    Secure. Transparent. <br/><span class="text-brand italic">Immutable.</span>
                </h1>
                <p class="max-w-2xl mx-auto text-xl text-zinc-500 mb-12 leading-relaxed font-medium">
                    The premium digital voting solution built for the IUEA Guild Elections. Engineered for integrity, designed for simplicity.
                </p>
                <div class="flex flex-col sm:flex-row items-center justify-center gap-5">
                    <button class="w-full sm:w-auto px-10 py-5 red-gradient text-white rounded-2xl font-bold shadow-2xl shadow-brand/40 hover:-translate-y-1 transition-all">
                        Launch Election Portal
                    </button>
                    <button class="w-full sm:w-auto px-10 py-5 bg-white text-zinc-900 border border-zinc-200 rounded-2xl font-bold hover:bg-zinc-50 transition-colors shadow-sm">
                        View Live Turnout
                    </button>
                </div>
            </div>
        </div>
    </section>

    <section class="py-24 border-y border-zinc-100 bg-white">
        <div class="max-w-7xl mx-auto px-6">
            <div class="grid lg:grid-cols-3 gap-16">
                <div class="space-y-4" data-aos="fade-up">
                    <div class="text-brand font-bold text-sm tracking-widest uppercase">The Challenge</div>
                    <h3 class="text-3xl font-bold text-zinc-900">Why legacy voting fails.</h3>
                    <p class="text-zinc-500 leading-relaxed">Paper ballots are prone to human error, physical tampering, and logistical delays that undermine student trust.</p>
                </div>
                <div class="lg:col-span-2 grid sm:grid-cols-2 gap-8">
                    <div class="p-8 rounded-3xl bg-zinc-50 border border-zinc-100 transition-hover hover:border-brand/20">
                        <div class="w-12 h-12 bg-brand/10 rounded-xl flex items-center justify-center mb-6">
                            <svg class="w-6 h-6 text-brand" fill="none" stroke="currentColor" viewBox="0 0 24 24"><path stroke-linecap="round" stroke-linejoin="round" stroke-width="2" d="M12 11c0 3.517-1.009 6.799-2.753 9.571m-3.44-2.04l.054-.09A10.003 10.003 0 0012 3a10.003 10.003 0 00-7.303 3.176m10.606 10.606L15 19l2.42-2.42M17 12a5 5 0 11-10 0 5 5 0 0110 0z"/></svg>
                        </div>
                        <h4 class="text-xl font-bold mb-3">Identity Fraud Prevention</h4>
                        <p class="text-zinc-500 text-sm">Biometric integration and unique student ID hashing ensure one student, one vote.</p>
                    </div>
                    <div class="p-8 rounded-3xl bg-zinc-50 border border-zinc-100 transition-hover hover:border-brand/20">
                        <div class="w-12 h-12 bg-brand/10 rounded-xl flex items-center justify-center mb-6">
                            <svg class="w-6 h-6 text-brand" fill="none" stroke="currentColor" viewBox="0 0 24 24"><path stroke-linecap="round" stroke-linejoin="round" stroke-width="2" d="M9 12l2 2 4-4m5.618-4.016A11.955 11.955 0 0112 2.944a11.955 11.955 0 01-8.618 3.04A12.02 12.02 0 003 9c0 5.591 3.824 10.29 9 11.622 5.176-1.332 9-6.03 9-11.622 0-1.042-.133-2.052-.382-3.016z"/></svg>
                        </div>
                        <h4 class="text-xl font-bold mb-3">Real-time Verification</h4>
                        <p class="text-zinc-500 text-sm">End-to-end encryption allows voters to verify their ballot was counted without compromising anonymity.</p>
                    </div>
                </div>
            </div>
        </div>
    </section>

    <section class="py-24 bg-zinc-900 overflow-hidden relative">
        <div class="max-w-7xl mx-auto px-6">
            <div class="flex flex-col lg:flex-row items-center gap-20">
                <div class="lg:w-1/3 text-white">
                    <h2 class="text-4xl font-extrabold mb-6 leading-tight">Master Command Center</h2>
                    <p class="text-zinc-400 mb-8 leading-relaxed">Admins gain a bird’s eye view of the election lifecycle. Monitor turnout by faculty, manage candidate profiles, and export certified results with one click.</p>
                    <ul class="space-y-4">
                        <li class="flex items-center gap-3 text-sm font-medium"><div class="w-1.5 h-1.5 rounded-full bg-brand"></div> Live Voter Heatmaps</li>
                        <li class="flex items-center gap-3 text-sm font-medium"><div class="w-1.5 h-1.5 rounded-full bg-brand"></div> Role-based Access Control</li>
                        <li class="flex items-center gap-3 text-sm font-medium"><div class="w-1.5 h-1.5 rounded-full bg-brand"></div> Automated Disqualification Logs</li>
                    </ul>
                </div>
                <div class="lg:w-2/3" data-aos="fade-left">
                    <div class="relative">
                        <div class="absolute inset-0 bg-brand/20 blur-[100px] -z-10"></div>
                        <div class="bg-zinc-800 rounded-2xl p-2 border border-white/10 shadow-2xl">
                            <div class="bg-zinc-900 rounded-xl overflow-hidden border border-white/5">
                                <img src="https://images.unsplash.com/photo-1551288049-bebda4e38f71?auto=format&fit=crop&q=80&w=2070" class="opacity-80 grayscale-[0.2] hover:grayscale-0 transition-all duration-700" alt="Dashboard UI">
                            </div>
                        </div>
                    </div>
                </div>
            </div>
        </div>
    </section>

    <section id="security" class="py-24 bg-white">
        <div class="max-w-7xl mx-auto px-6 text-center mb-16">
            <h2 class="text-4xl font-extrabold text-zinc-900 mb-4">Enterprise Security Architecture</h2>
            <p class="text-zinc-500 max-w-2xl mx-auto">Built on the Laravel framework, GuildVote utilizes the latest in cryptographic security to ensure election integrity.</p>
        </div>
        
        <div class="max-w-7xl mx-auto px-6 grid md:grid-cols-2 lg:grid-cols-4 gap-8">
            <div class="p-8 rounded-3xl border border-zinc-100 hover:shadow-xl hover:shadow-brand/5 transition-all group">
                <div class="text-brand font-black text-4xl mb-4 group-hover:scale-110 transition-transform origin-left">01</div>
                <h5 class="font-bold text-lg mb-2">Bcrypt Hashing</h5>
                <p class="text-sm text-zinc-500">Student credentials and sensitive data are protected by industry-standard Bcrypt algorithms.</p>
            </div>
            <div class="p-8 rounded-3xl border border-zinc-100 hover:shadow-xl hover:shadow-brand/5 transition-all group">
                <div class="text-brand font-black text-4xl mb-4 group-hover:scale-110 transition-transform origin-left">02</div>
                <h5 class="font-bold text-lg mb-2">CSRF Shield</h5>
                <p class="text-sm text-zinc-500">Cross-Site Request Forgery protection ensures every vote originates from an authenticated session.</p>
            </div>
            <div class="p-8 rounded-3xl border border-zinc-100 hover:shadow-xl hover:shadow-brand/5 transition-all group">
                <div class="text-brand font-black text-4xl mb-4 group-hover:scale-110 transition-transform origin-left">03</div>
                <h5 class="font-bold text-lg mb-2">SQL Injection Proof</h5>
                <p class="text-sm text-zinc-500">Eloquent ORM parameter binding makes the database immune to common injection attacks.</p>
            </div>
            <div class="p-8 rounded-3xl border border-zinc-100 hover:shadow-xl hover:shadow-brand/5 transition-all group">
                <div class="text-brand font-black text-4xl mb-4 group-hover:scale-110 transition-transform origin-left">04</div>
                <h5 class="font-bold text-lg mb-2">AES-256 Logs</h5>
                <p class="text-sm text-zinc-500">The audit trail is encrypted at rest, ensuring that election history is permanent and unchangeable.</p>
            </div>
        </div>
    </section>

    <section class="py-24">
        <div class="max-w-7xl mx-auto px-6">
            <div class="red-gradient rounded-[3rem] p-12 lg:p-24 text-center text-white relative overflow-hidden shadow-[0_40px_80px_-15px_rgba(178,34,34,0.4)]">
                <div class="absolute top-0 right-0 w-96 h-96 bg-white/10 rounded-full -mr-48 -mt-48 blur-3xl"></div>
                <div class="relative z-10">
                    <h2 class="text-4xl lg:text-6xl font-black mb-8">Ready to evolve IUEA <br/>Democracy?</h2>
                    <p class="text-brand-surface/80 text-lg mb-12 max-w-xl mx-auto">Join the ranks of modern institutions leading the way in transparent student leadership.</p>
                    <div class="flex flex-col sm:flex-row gap-4 justify-center">
                        <button class="bg-white text-brand px-10 py-5 rounded-2xl font-black shadow-xl hover:scale-105 transition-all">Setup Election Now</button>
                        <button class="bg-brand-dark/30 backdrop-blur-md border border-white/20 px-10 py-5 rounded-2xl font-bold hover:bg-brand-dark/50 transition-all">Talk to Support</button>
                    </div>
                </div>
            </div>
        </div>
    </section>

    <footer class="bg-zinc-50 pt-20 pb-10 border-t border-zinc-200">
        <div class="max-w-7xl mx-auto px-6">
            <div class="flex flex-col md:flex-row justify-between items-start gap-12 mb-16">
                <div class="max-w-xs">
                    <div class="flex items-center gap-3 mb-6">
                        <div class="w-8 h-8 bg-brand rounded flex items-center justify-center text-white font-bold text-xs">GV</div>
                        <span class="font-extrabold text-lg text-zinc-900">IUEA GuildVote</span>
                    </div>
                    <p class="text-sm text-zinc-500 leading-relaxed">The official voting standard for the International University of East Africa. Built with passion for transparency.</p>
                </div>
                <div class="grid grid-cols-2 sm:grid-cols-3 gap-12">
                    <div>
                        <h6 class="font-bold text-xs uppercase tracking-widest text-zinc-400 mb-6">Resources</h6>
                        <ul class="space-y-4 text-sm font-medium text-zinc-600">
                            <li><a href="#" class="hover:text-brand">Voter Guide</a></li>
                            <li><a href="#" class="hover:text-brand">API Docs</a></li>
                            <li><a href="#" class="hover:text-brand">Audit Portal</a></li>
                        </ul>
                    </div>
                    <div>
                        <h6 class="font-bold text-xs uppercase tracking-widest text-zinc-400 mb-6">Security</h6>
                        <ul class="space-y-4 text-sm font-medium text-zinc-600">
                            <li><a href="#" class="hover:text-brand">Data Privacy</a></li>
                            <li><a href="#" class="hover:text-brand">Laravel Shield</a></li>
                            <li><a href="#" class="hover:text-brand">Encryption</a></li>
                        </ul>
                    </div>
                </div>
            </div>
            <div class="pt-8 border-t border-zinc-200 flex flex-col sm:flex-row justify-between gap-4 items-center">
                <p class="text-xs text-zinc-400">© 2026 International University of East Africa. Technical partner: GuildVote Core Team.</p>
                <div class="flex gap-6 grayscale opacity-50 hover:grayscale-0 hover:opacity-100 transition-all">
                    <div class="w-5 h-5 bg-zinc-400 rounded-full"></div>
                    <div class="w-5 h-5 bg-zinc-400 rounded-full"></div>
                    <div class="w-5 h-5 bg-zinc-400 rounded-full"></div>
                </div>
            </div>
        </div>
    </footer>

    <script>
        AOS.init({ once: true, duration: 800 });
        
        // Sticky nav class toggle
        window.addEventListener('scroll', () => {
            const nav = document.querySelector('nav div');
            if (window.scrollY > 20) {
                nav.classList.add('shadow-lg', 'bg-white/90');
                nav.classList.remove('bg-white/70');
            } else {
                nav.classList.remove('shadow-lg', 'bg-white/90');
                nav.classList.add('bg-white/70');
            }
        });
    </script>
</body>
</html>