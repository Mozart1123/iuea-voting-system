@extends('layouts.admin')

@section('title', 'System Settings')
@section('admin-content')
<div class="max-w-4xl">
    <div class="space-y-8">
        <div>
            <h3 class="text-xl font-black text-gray-900 uppercase tracking-tight">System Configuration</h3>
            <p class="text-xs font-black text-gray-400 uppercase tracking-widest">Global parameters and feature toggles for the election system.</p>
        </div>

        <form action="{{ route('admin.settings.update') }}" method="POST">
            @csrf
            
            <div class="grid grid-cols-1 gap-8">
                <!-- Nomination Toggle -->
                <div class="bg-white rounded-[2.5rem] p-8 border border-gray-100 shadow-xl flex flex-col sm:flex-row items-center justify-between gap-6">
                    <div class="flex items-center gap-6">
                        <div class="w-14 h-14 bg-red-50 rounded-2xl flex items-center justify-center text-[#8b0000]">
                            <i class="fas fa-id-card text-xl"></i>
                        </div>
                        <div>
                            <h4 class="text-sm font-black text-gray-900 uppercase tracking-tight">Candidate Nominations</h4>
                            <p class="text-xs font-medium text-gray-400 max-w-sm">Allow students to submit themselves as candidates for open positions.</p>
                        </div>
                    </div>
                    <div>
                        <input type="hidden" name="settings[nomination_enabled]" value="0">
                        <label class="relative inline-flex items-center cursor-pointer">
                            <input type="checkbox" name="settings[nomination_enabled]" value="1" 
                                   {{ (isset($settings['nomination_enabled']) && $settings['nomination_enabled'] === '1') ? 'checked' : '' }} 
                                   class="sr-only peer">
                            <div class="w-14 h-8 bg-gray-100 peer-focus:outline-none rounded-full peer peer-checked:after:translate-x-full after:content-[''] after:absolute after:top-[4px] after:left-[4px] after:bg-white after:rounded-full after:h-6 after:w-6 after:transition-all peer-checked:bg-[#8b0000] shadow-inner"></div>
                        </label>
                    </div>
                </div>

                <!-- Election Transparency -->
                <div class="bg-white rounded-[2.5rem] p-8 border border-gray-100 shadow-xl flex flex-col sm:flex-row items-center justify-between gap-6">
                    <div class="flex items-center gap-6">
                        <div class="w-14 h-14 bg-blue-50 rounded-2xl flex items-center justify-center text-blue-600">
                            <i class="fas fa-chart-line text-xl"></i>
                        </div>
                        <div>
                            <h4 class="text-sm font-black text-gray-900 uppercase tracking-tight">Real-time Stats</h4>
                            <p class="text-xs font-medium text-gray-400 max-w-sm">Show current vote percentages to students during the live election.</p>
                        </div>
                    </div>
                    <div>
                        <input type="hidden" name="settings[show_realtime_stats]" value="0">
                        <label class="relative inline-flex items-center cursor-pointer">
                            <input type="checkbox" name="settings[show_realtime_stats]" value="1" 
                                   {{ (isset($settings['show_realtime_stats']) && $settings['show_realtime_stats'] === '1') ? 'checked' : '' }} 
                                   class="sr-only peer">
                            <div class="w-14 h-8 bg-gray-100 peer-focus:outline-none rounded-full peer peer-checked:after:translate-x-full after:content-[''] after:absolute after:top-[4px] after:left-[4px] after:bg-white after:rounded-full after:h-6 after:w-6 after:transition-all peer-checked:bg-blue-600 shadow-inner"></div>
                        </label>
                    </div>
                </div>

                <!-- Election Countdown -->
                <div class="bg-white rounded-[2.5rem] p-8 border border-gray-100 shadow-xl flex flex-col sm:flex-row items-center justify-between gap-6">
                    <div class="flex items-center gap-6">
                        <div class="w-14 h-14 bg-purple-50 rounded-2xl flex items-center justify-center text-purple-600">
                            <i class="fas fa-hourglass-half text-xl"></i>
                        </div>
                        <div>
                            <h4 class="text-sm font-black text-gray-900 uppercase tracking-tight">Election End Date</h4>
                            <p class="text-xs font-medium text-gray-400 max-w-sm">Set the date and time when voting will automatically close.</p>
                        </div>
                    </div>
                    <div class="w-full sm:w-auto">
                        <input type="datetime-local" name="settings[election_end_time]" 
                               value="{{ $settings['election_end_time'] ?? '' }}"
                               class="w-full px-4 py-3 bg-gray-50 border border-gray-100 rounded-xl text-sm font-bold focus:ring-2 focus:ring-purple-500 outline-none transition-all">
                    </div>
                </div>

                <!-- System Maintenance -->
                <div class="bg-white rounded-[2.5rem] p-8 border border-gray-100 shadow-xl flex flex-col sm:flex-row items-center justify-between gap-6">
                    <div class="flex items-center gap-6">
                        <div class="w-14 h-14 bg-orange-50 rounded-2xl flex items-center justify-center text-orange-600">
                            <i class="fas fa-tools text-xl"></i>
                        </div>
                        <div>
                            <h4 class="text-sm font-black text-gray-900 uppercase tracking-tight">Maintenance Mode</h4>
                            <p class="text-xs font-medium text-gray-400 max-w-sm">Disable all student access temporarily for system updates.</p>
                        </div>
                    </div>
                    <div>
                        <input type="hidden" name="settings[maintenance_mode]" value="0">
                        <label class="relative inline-flex items-center cursor-pointer">
                            <input type="checkbox" name="settings[maintenance_mode]" value="1" 
                                   {{ (isset($settings['maintenance_mode']) && $settings['maintenance_mode'] === '1') ? 'checked' : '' }} 
                                   class="sr-only peer">
                            <div class="w-14 h-8 bg-gray-100 peer-focus:outline-none rounded-full peer peer-checked:after:translate-x-full after:content-[''] after:absolute after:top-[4px] after:left-[4px] after:bg-white after:rounded-full after:h-6 after:w-6 after:transition-all peer-checked:bg-orange-600 shadow-inner"></div>
                        </label>
                    </div>
                </div>
            </div>

            <div class="mt-12 flex justify-end">
                <button type="submit" class="px-12 py-4 bg-gray-900 text-white rounded-2xl font-black text-xs uppercase tracking-widest hover:scale-[1.02] shadow-xl hover:shadow-black/20 transition-all flex items-center gap-3">
                    <i class="fas fa-sync-alt"></i>
                    Synchronize Configuration
                </button>
            </div>
        </form>
    </div>
</div>
@endsection
