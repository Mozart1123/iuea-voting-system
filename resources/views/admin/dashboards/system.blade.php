@extends('layouts.admin')

@section('title', 'System Monitoring')
@section('header', 'Infrastructure Dashboard')

@section('content')
<div class="space-y-12">
    <!-- Infrastructure Health -->
    <div class="grid grid-cols-1 md:grid-cols-3 gap-8">
        <div class="stat-card bg-white p-8 rounded-[2.5rem] border border-gray-100 shadow-2xl">
            <div class="w-12 h-12 bg-blue-50 text-blue-600 rounded-2xl flex items-center justify-center mb-4">
                <i class="fas fa-server"></i>
            </div>
            <p class="text-[10px] font-black text-gray-400 uppercase tracking-widest">Active Kiosks</p>
            <h3 class="text-3xl font-black text-gray-900">5 / 5</h3>
            <p class="text-[9px] text-green-600 font-bold mt-2 uppercase tracking-tight">All terminals responding</p>
        </div>

        <div class="stat-card bg-white p-8 rounded-[2.5rem] border border-gray-100 shadow-2xl">
            <div class="w-12 h-12 bg-green-50 text-green-600 rounded-2xl flex items-center justify-center mb-4">
                <i class="fas fa-database"></i>
            </div>
            <p class="text-[10px] font-black text-gray-400 uppercase tracking-widest">Database Sync</p>
            <h3 class="text-3xl font-black text-gray-900">Optimal</h3>
            <p class="text-[9px] text-green-600 font-bold mt-2 uppercase tracking-tight">Latency < 50ms</p>
        </div>

        <div class="stat-card bg-white p-8 rounded-[2.5rem] border border-gray-100 shadow-2xl">
            <div class="w-12 h-12 bg-purple-50 text-purple-600 rounded-2xl flex items-center justify-center mb-4">
                <i class="fas fa-shield-check"></i>
            </div>
            <p class="text-[10px] font-black text-gray-400 uppercase tracking-widest">Integrity Checks</p>
            <h3 class="text-3xl font-black text-gray-900">Passing</h3>
            <p class="text-[9px] text-purple-600 font-bold mt-2 uppercase tracking-tight">SHA-256 Validated</p>
        </div>
    </div>

    <!-- Monitoring View -->
    <div class="bg-white rounded-[3rem] border border-gray-100 shadow-2xl p-10">
        <div class="flex items-center justify-between mb-10">
            <h4 class="text-lg font-black text-gray-900 uppercase tracking-tight">System Participation Flow</h4>
            <div class="flex gap-2">
                <div class="w-3 h-3 bg-primary rounded-full animate-pulse"></div>
                <span class="text-[9px] font-black text-primary uppercase tracking-widest">Live Flow</span>
            </div>
        </div>
        
        <div class="h-64 flex items-end gap-2 border-b border-gray-100 pb-2">
            @for($i = 0; $i < 12; $i++)
                @php $h = rand(20, 100); @endphp
                <div class="flex-1 bg-gray-50 hover:bg-primary/20 transition-all rounded-t-lg relative group" style="height: {{ $h }}%">
                    <div class="absolute -top-8 left-1/2 -translate-x-1/2 bg-gray-900 text-white text-[8px] px-2 py-1 rounded opacity-0 group-hover:opacity-100 transition-opacity">
                        {{ rand(0, 50) }}
                    </div>
                </div>
            @endfor
        </div>
        <div class="flex justify-between mt-4">
            <p class="text-[9px] font-black text-gray-300 uppercase tracking-widest">08:00</p>
            <p class="text-[9px] font-black text-gray-300 uppercase tracking-widest">12:00</p>
            <p class="text-[9px] font-black text-gray-300 uppercase tracking-widest">16:00</p>
            <p class="text-[9px] font-black text-gray-300 uppercase tracking-widest">20:00</p>
        </div>
    </div>
</div>
@endsection
