@extends('layouts.admin')

@section('title', 'Super Admin Overview')
@section('header', 'System Command Center')

@section('content')
<div class="space-y-12">
    <!-- Header with Shortcuts -->
    <div class="flex flex-col md:flex-row md:items-center justify-between gap-6 px-4">
        <div>
            <h2 class="text-3xl font-black text-gray-900 uppercase tracking-tighter leading-none mb-2">System Oversight</h2>
            <p class="text-gray-500 font-medium text-xs uppercase tracking-widest">Authenticated Session • Super Admin Console</p>
        </div>
        <div class="flex items-center gap-4">
            <a href="{{ route('voter.entry') }}" target="_blank" class="px-8 py-4 bg-gray-900 text-white rounded-2xl font-black text-[10px] uppercase tracking-widest shadow-xl flex items-center gap-3 hover:scale-105 transition-all">
                <i class="fas fa-desktop"></i>
                Security Kiosk Simulator
            </a>
        </div>
    </div>

    <!-- Main Stats Grid -->
    <div class="grid grid-cols-1 md:grid-cols-2 lg:grid-cols-5 gap-6">
        <div class="stat-card bg-white p-6 rounded-[2.5rem] border border-gray-100 shadow-2xl">
            <div class="w-10 h-10 bg-red-50 text-primary rounded-xl flex items-center justify-center mb-4">
                <i class="fas fa-box-archive text-sm"></i>
            </div>
            <p class="text-[9px] font-black text-gray-400 uppercase tracking-widest mb-1">Total Ballots</p>
            <h3 class="text-2xl font-black text-gray-900 tracking-tighter">{{ number_format($stats['total_votes']) }}</h3>
        </div>

        <div class="stat-card bg-white p-6 rounded-[2.5rem] border border-gray-100 shadow-2xl">
            <div class="w-10 h-10 bg-blue-50 text-blue-600 rounded-xl flex items-center justify-center mb-4">
                <i class="fas fa-crown text-sm"></i>
            </div>
            <p class="text-[9px] font-black text-gray-400 uppercase tracking-widest mb-1">Presidential</p>
            <h3 class="text-2xl font-black text-gray-900 tracking-tighter">{{ number_format($stats['president_votes']) }}</h3>
        </div>

        <div class="stat-card bg-white p-6 rounded-[2.5rem] border border-gray-100 shadow-2xl">
            <div class="w-10 h-10 bg-orange-50 text-orange-600 rounded-xl flex items-center justify-center mb-4">
                <i class="fas fa-graduation-cap text-sm"></i>
            </div>
            <p class="text-[9px] font-black text-gray-400 uppercase tracking-widest mb-1">Faculty Reps</p>
            <h3 class="text-2xl font-black text-gray-900 tracking-tighter">{{ number_format($stats['faculty_votes']) }}</h3>
        </div>

        <div class="stat-card bg-white p-6 rounded-[2.5rem] border border-gray-100 shadow-2xl">
            <div class="w-10 h-10 bg-purple-50 text-purple-600 rounded-xl flex items-center justify-center mb-4">
                <i class="fas fa-server text-sm"></i>
            </div>
            <p class="text-[9px] font-black text-gray-400 uppercase tracking-widest mb-1">Active Kiosks</p>
            <h3 class="text-2xl font-black text-gray-900 tracking-tighter">{{ $stats['active_kiosks'] }}</h3>
        </div>

        <div class="stat-card bg-white p-6 rounded-[2.5rem] border border-gray-100 shadow-2xl">
            <div class="w-10 h-10 bg-green-50 text-green-600 rounded-xl flex items-center justify-center mb-4">
                <i class="fas fa-heart-pulse text-sm"></i>
            </div>
            <p class="text-[9px] font-black text-gray-400 uppercase tracking-widest mb-1">System Health</p>
            <h3 class="text-2xl font-black text-green-600 tracking-tighter">{{ $stats['system_status'] }}</h3>
        </div>
    </div>

    <!-- Live Progress & Recent Activity -->
    <div class="grid grid-cols-1 lg:grid-cols-3 gap-12">
        <!-- Vote Distribution -->
        <div class="lg:col-span-2 bg-white rounded-[3rem] border border-gray-100 shadow-2xl p-10">
            <div class="flex items-center justify-between mb-10">
                <h4 class="text-lg font-black text-gray-900 uppercase tracking-tight">Vote Distribution by Category</h4>
                <a href="{{ route('public.results') }}" target="_blank" class="px-6 py-2 bg-gray-900 text-white text-[10px] font-black rounded-full uppercase tracking-widest hover:bg-primary transition-all">
                    Public View <i class="fas fa-external-link-alt ml-2"></i>
                </a>
            </div>
            
            <div class="space-y-8">
                @foreach($categoryStats as $cat)
                <div class="space-y-4">
                    <div class="flex items-center justify-between">
                        <span class="text-xs font-black text-gray-600 uppercase tracking-widest">{{ $cat->name }}</span>
                        <span class="text-xs font-black text-primary">{{ $cat->votes_count }} votes</span>
                    </div>
                    <div class="w-full bg-gray-50 h-4 rounded-full overflow-hidden border border-gray-100">
                        @php $percent = $stats['total_votes'] > 0 ? ($cat->votes_count / $stats['total_votes']) * 100 : 0; @endphp
                        <div class="bg-gradient-to-r from-primary to-orange-500 h-full rounded-full transition-all duration-1000" style="width: {{ $percent }}%"></div>
                    </div>
                </div>
                @endforeach
            </div>
        </div>

        <!-- Audit Trail Snapshot -->
        <div class="bg-white rounded-[3rem] border border-gray-100 shadow-2xl p-10">
            <h4 class="text-lg font-black text-gray-900 uppercase tracking-tight mb-10">Security Audit Logs</h4>
            <div class="space-y-6">
                @foreach($recentActivity as $log)
                <div class="flex gap-4 group">
                    <div class="w-1 h-12 bg-gray-100 group-hover:bg-primary rounded-full transition-all"></div>
                    <div>
                        <p class="text-[10px] font-black text-gray-400 uppercase tracking-widest">{{ $log->created_at->diffForHumans() }}</p>
                        <h5 class="text-xs font-black text-gray-800 uppercase tracking-tight">{{ str_replace('_', ' ', $log->action) }}</h5>
                        <p class="text-[10px] text-gray-500 font-medium leading-relaxed">{{ $log->description }}</p>
                    </div>
                </div>
                @endforeach
            </div>
            <div class="mt-10 pt-8 border-t border-gray-50">
                <button class="w-full text-[10px] font-black text-gray-300 uppercase tracking-widest hover:text-primary transition-all">View Full Security Logs</button>
            </div>
        </div>
    </div>
</div>

<script>
    gsap.from(".stat-card", { 
        y: 30, 
        opacity: 0, 
        stagger: 0.1, 
        duration: 0.8, 
        ease: "power4.out" 
    });
</script>
@endsection
