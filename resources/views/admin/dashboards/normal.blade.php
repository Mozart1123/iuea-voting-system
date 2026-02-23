@extends('layouts.admin')

@section('title', 'Supervisor Panel')
@section('header', 'Kiosk Supervision')

@section('content')
<div class="space-y-12">
    <!-- Active Kiosk Status -->
    <div class="bg-gradient-to-r from-gray-900 to-primary p-12 rounded-[3rem] text-white shadow-2xl flex items-center justify-between">
        <div>
            <h3 class="text-3xl font-black tracking-tighter mb-4 uppercase">Kiosk Management Mode</h3>
            <p class="text-white/60 text-lg max-w-xl">You are currently supervising the physical voting process. Ensure you verify student IDs and payment eligibility before allowing them to approach the voting PCs.</p>
        </div>
        <div class="hidden lg:block">
            <i class="fas fa-shield-halved text-[8rem] opacity-20"></i>
        </div>
    </div>

    <div class="grid grid-cols-1 lg:grid-cols-3 gap-12">
        <!-- Live Vote Count -->
        <div class="bg-white p-10 rounded-[3rem] border border-gray-100 shadow-2xl text-center">
            <div class="w-20 h-20 bg-red-50 text-primary rounded-3xl flex items-center justify-center mx-auto mb-8 shadow-inner">
                <i class="fas fa-check-to-slot text-3xl"></i>
            </div>
            <h4 class="text-lg font-black text-gray-900 uppercase tracking-tight mb-2">Live Vote Count</h4>
            <p class="text-6xl font-black text-gray-900 tracking-tighter mb-4">{{ number_format($stats['total_votes']) }}</p>
            <p class="text-[10px] font-black text-gray-400 uppercase tracking-widest">Confirmed ballots processed</p>
        </div>

        <!-- Monitoring Feed -->
        <div class="lg:col-span-2 bg-white rounded-[3rem] border border-gray-100 shadow-2xl p-10">
            <div class="flex items-center justify-between mb-10">
                <h4 class="text-lg font-black text-gray-900 uppercase tracking-tight flex items-center gap-3">
                    <i class="fas fa-satellite-dish text-primary"></i>
                    Live activity feed
                </h4>
                <div class="px-4 py-1.5 bg-green-50 text-green-700 text-[9px] font-black rounded-full border border-green-100 animate-pulse">
                    AUTO-REFRESHING
                </div>
            </div>
            
            <div class="space-y-6">
                @forelse($recentActivity->where('action', 'VOTE_SUBMITTED') as $log)
                <div class="p-6 bg-gray-50 rounded-2xl flex items-center justify-between border border-gray-100">
                    <div class="flex items-center gap-4">
                        <div class="w-10 h-10 bg-white rounded-full flex items-center justify-center shadow-sm">
                            <i class="fas fa-fingerprint text-primary text-sm"></i>
                        </div>
                        <div>
                            <p class="text-[10px] font-black text-gray-400 uppercase tracking-widest">{{ $log->created_at->diffForHumans() }}</p>
                            <h5 class="text-xs font-black text-gray-800 uppercase tracking-tight">{{ $log->description }}</h5>
                        </div>
                    </div>
                </div>
                @empty
                <div class="py-12 text-center text-gray-300">
                    <i class="fas fa-box-open text-4xl mb-4"></i>
                    <p class="text-xs font-black uppercase tracking-widest">No recent votes detected</p>
                </div>
                @endforelse
            </div>
        </div>
    </div>
</div>
@endsection
