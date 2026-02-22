@extends('layouts.admin')

@section('title', 'Voter Management')
@section('admin-content')
<div class="space-y-6">
    <div class="flex flex-col md:flex-row md:items-center justify-between gap-4">
        <div>
            <h3 class="text-xl font-black text-gray-900 uppercase tracking-tight">Voter Records</h3>
            <p class="text-xs font-black text-gray-400 uppercase tracking-widest">Audit trail of registered students and their participation status.</p>
        </div>
        <div class="flex items-center gap-3">
            <div class="relative">
                <input type="text" placeholder="Search voters..." class="pl-10 pr-4 py-3 bg-white border border-gray-100 rounded-xl text-sm focus:ring-2 focus:ring-[#8b0000] outline-none">
                <i class="fas fa-search absolute left-4 top-1/2 -translate-y-1/2 text-gray-300"></i>
            </div>
            <button class="p-3 bg-white border border-gray-100 rounded-xl text-gray-400 hover:text-[#8b0000] transition-all">
                <i class="fas fa-filter"></i>
            </button>
        </div>
    </div>

    <div class="bg-white rounded-[2.5rem] shadow-xl border border-gray-100 overflow-hidden">
        <div class="overflow-x-auto">
            <table class="w-full text-left border-collapse">
                <thead>
                    <tr class="bg-gray-50/50 border-b border-gray-100">
                        <th class="px-8 py-5 text-[10px] font-black uppercase tracking-[0.2em] text-gray-400">Student Profile</th>
                        <th class="px-8 py-5 text-[10px] font-black uppercase tracking-[0.2em] text-gray-400">Student ID</th>
                        <th class="px-8 py-5 text-[10px] font-black uppercase tracking-[0.2em] text-gray-400">Faculty</th>
                        <th class="px-8 py-5 text-[10px] font-black uppercase tracking-[0.2em] text-gray-400">Account Status</th>
                        <th class="px-8 py-5 text-[10px] font-black uppercase tracking-[0.2em] text-gray-400">Registered</th>
                    </tr>
                </thead>
                <tbody class="divide-y divide-gray-50">
                    @forelse($voters as $voter)
                    <tr class="hover:bg-gray-50/80 transition-all group">
                        <td class="px-8 py-5">
                            <div class="flex items-center gap-4">
                                <div class="w-10 h-10 rounded-xl bg-gray-100 flex items-center justify-center text-[#8b0000] font-black overflow-hidden group-hover:scale-110 transition-transform">
                                    <img src="https://ui-avatars.com/api/?name={{ urlencode($voter->name) }}&background=8b0000&color=fff" alt="">
                                </div>
                                <div>
                                    <p class="text-sm font-black text-gray-900 uppercase tracking-tight">{{ $voter->name }}</p>
                                    <p class="text-[10px] font-black text-gray-400 uppercase tracking-widest">{{ $voter->email }}</p>
                                </div>
                            </div>
                        </td>
                        <td class="px-8 py-5">
                            <span class="text-sm font-bold text-gray-700">{{ $voter->student_id ?? 'N/A' }}</span>
                        </td>
                        <td class="px-8 py-5">
                            <span class="text-xs font-black text-gray-500 uppercase tracking-widest">{{ $voter->faculty ?? 'General' }}</span>
                        </td>
                        <td class="px-8 py-5">
                            <div class="flex items-center gap-2">
                                <div class="w-2 h-2 rounded-full bg-green-500 shadow-[0_0_10px_rgba(34,197,94,0.4)]"></div>
                                <span class="text-[10px] font-black uppercase tracking-widest text-green-600">Active Account</span>
                            </div>
                        </td>
                        <td class="px-8 py-5">
                            <p class="text-xs font-black text-gray-900">{{ $voter->created_at->format('d/m/Y') }}</p>
                            <p class="text-[10px] font-bold text-gray-300">{{ $voter->created_at->format('H:i') }}</p>
                        </td>
                    </tr>
                    @empty
                    <tr>
                        <td colspan="5" class="px-8 py-12 text-center">
                            <div class="w-16 h-16 bg-gray-50 rounded-2xl flex items-center justify-center mx-auto mb-4 text-gray-300">
                                <i class="fas fa-users-slash text-2xl"></i>
                            </div>
                            <p class="text-xs font-black text-gray-400 uppercase tracking-widest">No voter records found in the system</p>
                        </td>
                    </tr>
                    @endforelse
                </tbody>
            </table>
        </div>
        
        @if($voters->hasPages())
        <div class="px-8 py-6 bg-gray-50/50 border-t border-gray-100 mt-auto">
            {{ $voters->links() }}
        </div>
        @endif
    </div>
</div>
@endsection
