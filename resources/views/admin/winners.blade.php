<!DOCTYPE html>
<html lang="en">
<head>
    <meta charset="UTF-8">
    <meta name="viewport" content="width=device-width, initial-scale=1.0">
    <title>Winners & Certificates • IUEA GuildVote</title>
    <script src="https://cdn.tailwindcss.com"></script>
    <link rel="stylesheet" href="https://cdnjs.cloudflare.com/ajax/libs/font-awesome/6.4.0/css/all.min.css">
    <script src="https://cdnjs.cloudflare.com/ajax/libs/html2pdf.js/0.10.1/html2pdf.bundle.min.js"></script>
    <link href="https://fonts.googleapis.com/css2?family=Cinzel:wght@400;700&family=Inter:wght@300;400;500;600;700;800&family=Great+Vibes&display=swap" rel="stylesheet">
    <style>
        :root {
            --primary: #8B0000;
            --primary-dark: #660000;
            --gold: #D4AF37;
            --gold-dark: #B8860B;
        }
        body { font-family: 'Inter', sans-serif; background-color: #f8fafc; }
        
        /* Sidebar Styles Matching main Admin Dashboard */
        .sidebar-admin {
            background: linear-gradient(165deg, #660000 0%, #8B0000 100%);
        }
        .sidebar-link {
            transition: all 0.2s ease;
            cursor: pointer;
            color: rgba(255, 255, 255, 0.85);
        }
        .sidebar-link:hover {
            background-color: rgba(255, 255, 255, 0.12);
            color: white;
        }
        .sidebar-link.active {
            background-color: white;
            color: var(--primary);
        }
        .sidebar-link.active i { color: var(--primary); }
        .sidebar-link i { color: rgba(255, 255, 255, 0.9); }

        .certificate-container {
            width: 800px;
            height: 600px;
            padding: 2px;
            background: var(--gold);
            background: linear-gradient(135deg, #D4AF37 0%, #F5DEB3 50%, #B8860B 100%);
            box-shadow: 0 20px 50px rgba(0,0,0,0.15);
            margin: auto;
            position: relative;
        }
        .certificate-inner {
            background: white;
            height: 100%;
            width: 100%;
            padding: 40px;
            border: 15px solid white;
            position: relative;
            display: flex;
            flex-direction: column;
            align-items: center;
            text-align: center;
            background-image: url('https://www.transparenttextures.com/patterns/white-paper.png');
        }
        .cert-border {
            position: absolute;
            top: 20px;
            left: 20px;
            right: 20px;
            bottom: 20px;
            border: 2px solid var(--gold);
            pointer-events: none;
        }
        .cinzel { font-family: 'Cinzel', serif; }
        .cursive { font-family: 'Great Vibes', cursive; }
        .seal {
            width: 100px;
            height: 100px;
            background: var(--gold);
            border-radius: 50%;
            display: flex;
            align-items: center;
            justify-content: center;
            box-shadow: inset 0 0 10px rgba(0,0,0,0.2), 0 5px 15px rgba(184,134,11,0.4);
            margin-top: 20px;
        }
    </style>
</head>
<body class="flex min-h-screen">

    <!-- Backdrop for mobile sidebar -->
    <div id="sidebarBackdrop" class="fixed inset-0 bg-gray-900/50 backdrop-blur-sm z-20 hidden backdrop-active"></div>

    <!-- ========== ADMIN SIDEBAR – MATCHES DASHBOARD ========== -->
    <aside id="sidebar" class="fixed md:static inset-y-0 left-0 w-72 sidebar-admin flex flex-col transition-transform duration-300 transform -translate-x-full md:translate-x-0 z-30 shadow-xl overflow-hidden shrink-0">
        <!-- header with logo -->
        <div class="p-6 border-b border-white/20">
            <div class="flex items-center gap-3">
                <div class="w-16 h-14 bg-white rounded-lg flex items-center justify-center shadow-md overflow-hidden p-1">
                    <img src="https://iuea.ac.ug/sitepad-data/uploads/2024/07/IUEA-Logo-official-1.png" alt="IUEA Logo" class="w-full h-full object-contain">
                </div>
                <div>
                    <span class="font-bold text-lg text-white block leading-tight">IUEA</span>
                    <span class="font-black text-lg text-white block leading-tight">GuildVote</span>
                </div>
            </div>
            <div class="mt-2 text-[10px] text-white/70 font-black uppercase tracking-widest flex items-center">
                <i class="fas fa-shield-alt mr-1 {{ Auth::user()->role === 'super_admin' ? 'text-yellow-400' : '' }}"></i> 
                {{ Auth::user()->role === 'super_admin' ? 'Super Admin Console' : 'Admin Console' }}
            </div>
        </div>
        
        <!-- navigation links -->
        <nav class="flex-1 px-4 py-6 space-y-1 overflow-y-auto">
            @if(Auth::user()->role === 'super_admin')
                <a href="{{ route('admin.super-admin.index') }}" class="sidebar-link flex items-center px-4 py-3 text-sm font-medium rounded-xl">
                    <i class="fas fa-tachometer-alt w-6"></i>
                    <span class="ml-3">Dashboard</span>
                </a>
                <a href="{{ route('admin.super-admin.index') }}#admins" class="sidebar-link flex items-center px-4 py-3 text-sm font-medium rounded-xl">
                    <i class="fas fa-user-shield w-6"></i>
                    <span class="ml-3">Manage Admins</span>
                </a>
                <a href="{{ route('admin.super-admin.index') }}#elections" class="sidebar-link flex items-center px-4 py-3 text-sm font-medium rounded-xl">
                    <i class="fas fa-check-double w-6"></i>
                    <span class="ml-3">Elections</span>
                </a>
                <a href="{{ route('admin.super-admin.index') }}#candidates" class="sidebar-link flex items-center px-4 py-3 text-sm font-medium rounded-xl">
                    <i class="fas fa-user-tie w-6"></i>
                    <span class="ml-3">Candidates</span>
                </a>
                <a href="{{ route('admin.super-admin.index') }}#users" class="sidebar-link flex items-center px-4 py-3 text-sm font-medium rounded-xl">
                    <i class="fas fa-users w-6"></i>
                    <span class="ml-3">Students</span>
                </a>
                <a href="{{ route('admin.winners') }}" class="sidebar-link active flex items-center px-4 py-3 text-sm font-medium rounded-xl shadow-lg">
                    <i class="fas fa-trophy w-6"></i>
                    <span class="ml-3 font-bold">Winners & Certificates</span>
                </a>
                <a href="{{ route('admin.super-admin.index') }}#logs" class="sidebar-link flex items-center px-4 py-3 text-sm font-medium rounded-xl">
                    <i class="fas fa-clipboard-list w-6"></i>
                    <span class="ml-3">Audit logs</span>
                </a>
                <a href="{{ route('admin.super-admin.index') }}#settings" class="sidebar-link flex items-center px-4 py-3 text-sm font-medium rounded-xl text-white/50">
                    <i class="fas fa-cog w-6"></i>
                    <span class="ml-3">Settings</span>
                </a>
            @else
                <a href="{{ route('admin.index') }}" class="sidebar-link flex items-center px-4 py-3 text-sm font-medium rounded-xl">
                    <i class="fas fa-tachometer-alt w-6"></i>
                    <span class="ml-3">Dashboard</span>
                </a>
                <a href="{{ route('admin.index') }}#elections" class="sidebar-link flex items-center px-4 py-3 text-sm font-medium rounded-xl">
                    <i class="fas fa-check-double w-6"></i>
                    <span class="ml-3">Elections</span>
                </a>
                <a href="{{ route('admin.index') }}#candidates" class="sidebar-link flex items-center px-4 py-3 text-sm font-medium rounded-xl">
                    <i class="fas fa-user-tie w-6"></i>
                    <span class="ml-3">Candidates</span>
                </a>
                <a href="{{ route('admin.index') }}#voters" class="sidebar-link flex items-center px-4 py-3 text-sm font-medium rounded-xl">
                    <i class="fas fa-users w-6"></i>
                    <span class="ml-3">Voters</span>
                </a>
                <a href="{{ route('admin.index') }}#audit" class="sidebar-link flex items-center px-4 py-3 text-sm font-medium rounded-xl">
                    <i class="fas fa-clipboard-list w-6"></i>
                    <span class="ml-3">Audit Logs</span>
                </a>
                <a href="{{ route('admin.index') }}#reports" class="sidebar-link flex items-center px-4 py-3 text-sm font-medium rounded-xl">
                    <i class="fas fa-chart-pie w-6"></i>
                    <span class="ml-3">Reports</span>
                </a>
                <a href="{{ route('admin.winners') }}" class="sidebar-link active flex items-center px-4 py-3 text-sm font-medium rounded-xl shadow-lg">
                    <i class="fas fa-trophy w-6"></i>
                    <span class="ml-3 font-bold">Winners & Certificates</span>
                </a>
                <a href="{{ route('admin.index') }}#settings" class="sidebar-link flex items-center px-4 py-3 text-sm font-medium rounded-xl">
                    <i class="fas fa-cog w-6"></i>
                    <span class="ml-3">Settings</span>
                </a>
            @endif
        </nav>
        
        <!-- admin user & logout -->
        <div class="p-4 border-t border-white/20">
            <div class="flex items-center mb-4 px-2">
                <div class="w-10 h-10 rounded-full bg-white/30 backdrop-blur-sm flex items-center justify-center text-white font-bold border-2 border-white overflow-hidden shadow-inner">
                    <img src="{{ Auth::user()->profile_photo_url }}" class="w-full h-full object-cover">
                </div>
                <div class="ml-3">
                    <p class="text-xs font-bold text-white truncate w-32">{{ Auth::user()->name }}</p>
                    <p class="text-[10px] text-white/60 uppercase font-black">{{ Auth::user()->role }}</p>
                </div>
            </div>
            <form action="{{ route('logout') }}" method="POST" id="logoutForm">
                @csrf
                <button type="submit" class="sidebar-link w-full flex items-center px-4 py-3 text-sm font-medium rounded-xl">
                    <i class="fas fa-sign-out-alt w-6"></i>
                    <span class="ml-3">Logout</span>
                </button>
            </form>
        </div>
    </aside>

    <main class="flex-1 min-w-0 p-4 md:p-8 overflow-y-auto w-full relative">
        <!-- New Mobile Header -->
        <div class="md:hidden flex items-center justify-between mb-6 bg-white p-4 rounded-2xl border border-gray-100 shadow-sm">
            <div class="flex items-center gap-3">
                <img src="https://iuea.ac.ug/sitepad-data/uploads/2024/07/IUEA-Logo-official-1.png" class="h-8" alt="Logo">
                <span class="font-black text-gray-900 text-sm">GuildVote</span>
            </div>
            <button id="mobileMenuBtn" class="w-10 h-10 bg-gray-900 text-white rounded-xl flex items-center justify-center">
                <i class="fas fa-bars"></i>
            </button>
        </div>
        <div class="max-w-6xl mx-auto">
            <header class="flex flex-col md:flex-row md:items-center justify-between gap-4 mb-10">
                <div>
                    <h1 class="text-4xl font-black text-gray-900 tracking-tight">Winners & Certificates</h1>
                    <p class="text-gray-500 mt-1">Finalize official results and generate success certificates.</p>
                </div>
                
                @if(!$isEnded)
                <div class="bg-yellow-50 border border-yellow-200 p-4 rounded-2xl flex items-center gap-4">
                    <i class="fas fa-exclamation-triangle text-yellow-600 text-2xl"></i>
                    <div>
                        <p class="font-bold text-gray-900">Voting is still in progress</p>
                        <p class="text-xs text-gray-600">Finalize the election in the dashboard to validate winners.</p>
                    </div>
                </div>
                @else
                <div class="bg-green-50 border border-green-200 p-4 rounded-2xl flex items-center gap-4">
                    <i class="fas fa-check-circle text-green-600 text-2xl"></i>
                    <div>
                        <p class="font-bold text-gray-900">Election Finalized</p>
                        <p class="text-xs text-gray-600">All results are official and secured.</p>
                    </div>
                </div>
                @endif
            </header>

            @if(count($winners) > 0)
                <div class="grid grid-cols-1 gap-12">
                    @foreach($winners as $item)
                    <div class="bg-white rounded-3xl p-8 border border-gray-100 shadow-xl shadow-gray-200/50 flex flex-col xl:flex-row gap-8 items-center">
                        <!-- Preview Card -->
                        <div class="w-full xl:w-1/3 text-center xl:text-left space-y-4">
                            <span class="bg-primary/10 text-primary text-xs font-black px-3 py-1 rounded-full uppercase tracking-widest">{{ $item['category']->name }}</span>
                            <h2 class="text-3xl font-black text-gray-900">{{ $item['winner']->name }}</h2>
                            <div class="flex gap-4 items-center justify-center xl:justify-start">
                                <div class="bg-gray-50 px-4 py-2 rounded-xl">
                                    <p class="text-[10px] text-gray-400 font-bold uppercase">Total Votes</p>
                                    <p class="text-xl font-black text-gray-900">{{ $item['total_votes'] }}</p>
                                </div>
                                <div class="bg-gray-50 px-4 py-2 rounded-xl">
                                    <p class="text-[10px] text-gray-400 font-bold uppercase">Faculty</p>
                                    <p class="text-xl font-black text-gray-900">{{ $item['winner']->faculty }}</p>
                                </div>
                            </div>
                            <button onclick="downloadCert('cert-{{ $item['winner']->id }}', '{{ $item['winner']->name }}')" class="w-full bg-black text-white py-4 rounded-2xl font-bold hover:bg-primary transition-all flex items-center justify-center gap-3 shadow-xl">
                                <i class="fas fa-file-pdf"></i> Download Certificate
                            </button>
                        </div>

                        <!-- Certificate Frame (Hidden from view mostly, used for export) -->
                        <div class="w-full xl:w-2/3 flex justify-center overflow-x-auto p-4">
                            <div id="cert-{{ $item['winner']->id }}" class="certificate-container scale-75 xl:scale-100 transform origin-top xl:origin-center">
                                <div class="certificate-inner">
                                    <div class="cert-border"></div>
                                    
                                    <img src="https://iuea.ac.ug/sitepad-data/uploads/2024/07/IUEA-Logo-official-1.png" class="h-16 mb-6" alt="IUEA">
                                    
                                    <h3 class="cinzel text-xl text-gold-dark font-bold tracking-widest mb-2">INTERNATIONAL UNIVERSITY OF EAST AFRICA</h3>
                                    <p class="text-xs text-gray-500 font-bold tracking-[0.2em] mb-8">GUILD COUNCIL ELECTIONS 2026</p>
                                    
                                    <h1 class="cursive text-6xl text-gray-800 mb-6">Certificate of Honor</h1>
                                    
                                    <p class="text-gray-600 italic">This certificate is proudly awarded to</p>
                                    
                                    <p class="cinzel text-4xl text-primary font-bold my-6 uppercase border-b-2 border-primary/20 pb-2 px-10">{{ $item['winner']->name }}</p>
                                    
                                    <p class="text-gray-600 max-w-lg">
                                        For their triumphant election to the position of <br>
                                        <span class="font-bold text-gray-900 uppercase">{{ $item['category']->name }}</span>
                                        <br>with a massive support of {{ $item['total_votes'] }} votes cast.
                                    </p>

                                    <div class="mt-auto w-full flex justify-between items-end px-10 pb-4">
                                        <div class="text-left">
                                            <p class="font-bold text-gray-900 border-t border-gray-300 pt-2 px-4">Electoral Commission</p>
                                            <p class="text-[10px] text-gray-400 px-4 uppercase tracking-widest">Signed on {{ now()->format('M d, Y') }}</p>
                                        </div>
                                        
                                        <div class="seal">
                                            <i class="fas fa-certificate text-white text-3xl"></i>
                                        </div>

                                        <div class="text-right">
                                            <p class="font-bold text-gray-900 border-t border-gray-300 pt-2 px-4">General Secretariat</p>
                                            <p class="text-[10px] text-gray-400 px-4 uppercase tracking-widest">Official Seal</p>
                                        </div>
                                    </div>
                                </div>
                            </div>
                        </div>
                    </div>
                    @endforeach
                </div>
            @else
                <div class="text-center py-20 bg-white rounded-3xl border border-dashed border-gray-300">
                    <div class="w-20 h-20 bg-gray-50 rounded-full flex items-center justify-center mx-auto mb-6">
                        <i class="fas fa-trophy text-4xl text-gray-300"></i>
                    </div>
                    <h2 class="text-2xl font-bold text-gray-900">No winners available yet</h2>
                    <p class="text-gray-500 mt-2 max-w-sm mx-auto">Please wait for the voting to end and results to be finalized to view winners.</p>
                    <a href="{{ route('admin.index') }}" class="mt-8 inline-block bg-primary text-white px-8 py-3 rounded-xl font-bold hover:shadow-lg transition">Back to Dashboard</a>
                </div>
            @endif
        </div>
    </main>

    <script>
        (function() {
            // Mobile sidebar toggle logic
            const sidebar = document.getElementById('sidebar');
            const backdrop = document.getElementById('sidebarBackdrop');
            const mobileBtn = document.getElementById('mobileMenuBtn');

            function openSidebar() {
                sidebar.classList.remove('-translate-x-full');
                backdrop.classList.remove('hidden');
                document.body.classList.add('overflow-hidden');
            }

            function closeSidebar() {
                sidebar.classList.add('-translate-x-full');
                backdrop.classList.add('hidden');
                document.body.classList.remove('overflow-hidden');
            }

            if (mobileBtn) mobileBtn.addEventListener('click', openSidebar);
            if (backdrop) backdrop.addEventListener('click', closeSidebar);

            // Close sidebar on resize if switching to desktop
            window.addEventListener('resize', () => {
                if (window.innerWidth >= 768) {
                    sidebar.classList.remove('-translate-x-full');
                    backdrop.classList.add('hidden');
                    document.body.classList.remove('overflow-hidden');
                } else {
                    sidebar.classList.add('-translate-x-full');
                }
            });

            window.downloadCert = function(elementId, winnerName) {
                const element = document.getElementById(elementId);
                const opt = {
                    margin:       0,
                    filename:     `Certificat_IUEA_${winnerName.replace(/\s+/g, '_')}.pdf`,
                    image:        { type: 'jpeg', quality: 1.0 },
                    html2canvas:  { scale: 2, useCORS: true },
                    jsPDF:        { unit: 'px', format: [800, 600], orientation: 'landscape' }
                };

                const originalTransform = element.style.transform;
                element.style.transform = 'none';
                
                html2pdf().set(opt).from(element).save().then(() => {
                    element.style.transform = originalTransform;
                });
            }
        })();
    </script>
</body>
</html>
