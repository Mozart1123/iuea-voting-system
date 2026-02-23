@extends('layouts.admin')

@section('title', 'Live Activity Feed')
@section('header', 'Real-time Participation')

@section('content')
<div class="space-y-12">
    <!-- Summary Header -->
    <div class="bg-white p-10 rounded-[3rem] border border-gray-100 shadow-2xl flex flex-col md:flex-row items-center justify-between gap-8">
        <div>
            <h3 class="text-2xl font-black text-gray-900 uppercase tracking-tighter mb-2">Participation Stream</h3>
            <p class="text-gray-500 font-medium text-sm">Monitoring every verified ballot entering the system across all 5 kiosk terminals.</p>
        </div>
        <div class="flex items-center gap-4">
            <div class="text-right">
                <p class="text-[10px] font-black text-gray-400 uppercase tracking-widest">Live Ballots</p>
                <p class="text-3xl font-black text-primary">{{ $votes->total() }}</p>
            </div>
            <div class="w-px h-12 bg-gray-100"></div>
            <div class="px-6 py-2 bg-green-50 text-green-700 text-[10px] font-black rounded-full border border-green-100 animate-pulse">
                MONITORING ACTIVE
            </div>
        </div>
    </div>

    <!-- Feed Table -->
    <div class="bg-white rounded-[3rem] border border-gray-100 shadow-2xl overflow-hidden">
        <div class="overflow-x-auto">
            <table class="w-full text-left border-collapse">
                <thead>
                    <tr class="bg-gray-50/50">
                        <th class="px-10 py-6 text-[10px] font-black text-gray-400 uppercase tracking-widest">Timestamp</th>
                        <th class="px-10 py-6 text-[10px] font-black text-gray-400 uppercase tracking-widest">Student Information</th>
                        <th class="px-10 py-6 text-[10px] font-black text-gray-400 uppercase tracking-widest">Kiosk IP</th>
                        <th class="px-10 py-6 text-[10px] font-black text-gray-400 uppercase tracking-widest">Status</th>
                    </tr>
                </thead>
                <tbody class="divide-y divide-gray-50">
                    @forelse($votes as $voter)
                    <tr class="hover:bg-gray-50/30 transition-all group">
                        <td class="px-10 py-8">
                            <p class="text-xs font-black text-gray-900 uppercase tracking-tight">{{ $voter->voted_at->format('H:i:s') }}</p>
                            <p class="text-[10px] text-gray-400 font-medium">{{ $voter->voted_at->format('M d, Y') }}</p>
                        </td>
                        <td class="px-10 py-8">
                            <div class="flex items-center gap-4">
                                <div class="w-10 h-10 rounded-full bg-red-50 text-primary flex items-center justify-center font-black text-sm">
                                    {{ substr($voter->full_name, 0, 1) }}
                                </div>
                                <div>
                                    <p class="text-sm font-black text-gray-900 uppercase">{{ $voter->full_name }}</p>
                                    <p class="text-[10px] font-mono text-gray-400 uppercase tracking-widest">{{ $voter->registration_number }}</p>
                                </div>
                            </div>
                        </td>
                        <td class="px-10 py-8">
                            <span class="text-xs font-mono text-gray-500 bg-gray-100 px-3 py-1 rounded-lg">
                                {{ $voter->ip_address }}
                            </span>
                        </td>
                        <td class="px-10 py-8">
                            <div class="flex items-center gap-2">
                                <div class="w-2 h-2 rounded-full bg-green-500 shadow-[0_0_10px_rgba(34,197,94,0.5)]"></div>
                                <span class="text-[10px] font-black text-green-700 uppercase tracking-widest">VERIFIED BALLOT</span>
                            </div>
                        </td>
                    </tr>
                    @empty
                    <tr>
                        <td colspan="4" class="px-10 py-24 text-center">
                            <div class="w-20 h-20 bg-gray-50 rounded-full flex items-center justify-center mx-auto mb-6 text-gray-200">
                                <i class="fas fa-satellite-dish text-3xl"></i>
                            </div>
                            <p class="text-xs font-black text-gray-300 uppercase tracking-[0.3em]">Waiting for first participation data...</p>
                        </td>
                    </tr>
                    @endforelse
                </tbody>
            </table>
        </div>
        
        @if($votes->hasPages())
        <div class="p-10 border-t border-gray-50 bg-gray-50/30">
            {{ $votes->links() }}
        </div>
        @endif
    </div>
</div>

<script>
    gsap.from("tr", { 
        y: 20, 
        opacity: 0, 
        stagger: 0.05, 
        duration: 0.8, 
        ease: "power4.out" 
    });
</script>
@endsection
