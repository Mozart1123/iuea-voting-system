@extends('layouts.admin')

@section('title', 'System Settings')
@section('header', 'Infrastructure Configuration')

@section('content')
<div class="max-w-4xl space-y-12">
    <div class="bg-gradient-to-br from-gray-900 to-primary p-12 rounded-[3.5rem] text-white shadow-2xl relative overflow-hidden">
        <i class="fas fa-microchip absolute -right-12 -bottom-12 text-[15rem] opacity-10"></i>
        <div class="relative z-10">
            <h3 class="text-3xl font-black uppercase tracking-tighter mb-4">Core Management</h3>
            <p class="text-white/60 text-lg max-w-lg">Modify global parameters for the IUEA GuildVote platform. These changes take effect immediately across all active kiosks.</p>
        </div>
    </div>

    <form action="{{ route('admin.manage.settings.update') }}" method="POST" class="space-y-8">
        @csrf
        
        <div class="grid grid-cols-1 md:grid-cols-2 gap-8">
            <!-- Election Phase -->
            <div class="bg-white p-10 rounded-[2.5rem] border border-gray-100 shadow-2xl space-y-6">
                <div class="flex items-center gap-4 border-b border-gray-50 pb-6">
                    <div class="w-10 h-10 bg-red-50 text-primary rounded-xl flex items-center justify-center">
                        <i class="fas fa-clock"></i>
                    </div>
                    <h4 class="text-sm font-black text-gray-900 uppercase tracking-widest">Election Lifecycle</h4>
                </div>
                
                <div class="space-y-2">
                    <label class="text-[9px] font-black text-gray-400 uppercase tracking-[0.2em] ml-1">Current Phase</label>
                    <select name="election_phase" class="w-full px-6 py-4 bg-gray-50 border-2 border-transparent rounded-2xl focus:bg-white focus:border-primary outline-none transition-all text-sm font-bold uppercase">
                        <option value="PRE">Pre-election (Vetting)</option>
                        <option value="LIVE">Live Voting</option>
                        <option value="PAUSED">System Paused</option>
                        <option value="CLOSED">Results Calculation</option>
                    </select>
                </div>
            </div>

            <!-- Kiosk Restrictions -->
            <div class="bg-white p-10 rounded-[2.5rem] border border-gray-100 shadow-2xl space-y-6">
                <div class="flex items-center gap-4 border-b border-gray-50 pb-6">
                    <div class="w-10 h-10 bg-orange-50 text-orange-600 rounded-xl flex items-center justify-center">
                        <i class="fas fa-desktop"></i>
                    </div>
                    <h4 class="text-sm font-black text-gray-900 uppercase tracking-widest">Kiosk Lockdown</h4>
                </div>
                
                <div class="space-y-2">
                    <label class="text-[9px] font-black text-gray-400 uppercase tracking-[0.2em] ml-1">Authorized Terminals</label>
                    <input type="number" name="authorized_kiosks" value="5" class="w-full px-6 py-4 bg-gray-50 border-2 border-transparent rounded-2xl focus:bg-white focus:border-primary outline-none transition-all text-sm font-bold">
                    <p class="text-[8px] text-gray-300 font-bold uppercase tracking-widest mt-2">* Limits active voting connections</p>
                </div>
            </div>

            <!-- Security Parameters -->
            <div class="bg-white p-10 rounded-[2.5rem] border border-gray-100 shadow-2xl space-y-6 md:col-span-2">
                <div class="flex items-center gap-4 border-b border-gray-50 pb-6">
                    <div class="w-10 h-10 bg-purple-50 text-purple-600 rounded-xl flex items-center justify-center">
                        <i class="fas fa-user-lock"></i>
                    </div>
                    <h4 class="text-sm font-black text-gray-900 uppercase tracking-widest">Privacy & Integrity</h4>
                </div>
                
                <div class="grid grid-cols-1 md:grid-cols-2 gap-8">
                    <div class="space-y-4">
                        <div class="flex items-center justify-between">
                            <span class="text-xs font-black text-gray-600 uppercase tracking-tight">Voter Personal IDs</span>
                            <label class="relative inline-flex items-center cursor-pointer">
                                <input type="checkbox" name="mask_ids" value="1" checked class="sr-only peer">
                                <div class="w-11 h-6 bg-gray-200 peer-focus:outline-none rounded-full peer peer-checked:after:translate-x-full peer-checked:after:border-white after:content-[''] after:absolute after:top-[2px] after:left-[2px] after:bg-white after:border-gray-300 after:border after:rounded-full after:h-5 after:w-5 after:transition-all peer-checked:bg-primary"></div>
                            </label>
                        </div>
                        <p class="text-[9px] text-gray-400 font-medium">When enabled, registration numbers are masked in public activity feeds.</p>
                    </div>

                    <div class="space-y-4">
                        <div class="flex items-center justify-between">
                            <span class="text-xs font-black text-gray-600 uppercase tracking-tight">Real-time Public Feed</span>
                            <label class="relative inline-flex items-center cursor-pointer">
                                <input type="checkbox" name="public_feed_active" value="1" checked class="sr-only peer">
                                <div class="w-11 h-6 bg-gray-200 peer-focus:outline-none rounded-full peer peer-checked:after:translate-x-full peer-checked:after:border-white after:content-[''] after:absolute after:top-[2px] after:left-[2px] after:bg-white after:border-gray-300 after:border after:rounded-full after:h-5 after:w-5 after:transition-all peer-checked:bg-primary"></div>
                            </label>
                        </div>
                        <p class="text-[9px] text-gray-400 font-medium">Controls the availability of the projection screen data stream.</p>
                    </div>
                </div>
            </div>
        </div>

        <button type="submit" class="w-full py-6 bg-gray-900 border-4 border-gray-900 hover:bg-white hover:text-gray-900 transition-all text-white rounded-[2rem] font-black text-sm uppercase tracking-[0.3em] shadow-2xl">
            Authorize Global Deployment
        </button>
    </form>
</div>
@endsection
