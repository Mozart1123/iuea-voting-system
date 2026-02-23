@extends('layouts.admin')

@section('title', 'System Audit Logs')
@section('header', 'Security Activity Tracking')

@section('content')
<div class="space-y-8">
    <div class="bg-white p-8 rounded-[2.5rem] border border-gray-100 shadow-2xl flex items-center justify-between">
        <div>
            <h3 class="text-xl font-black text-gray-900 uppercase tracking-tight">Access & Action Audit</h3>
            <p class="text-[10px] font-black text-gray-400 uppercase tracking-widest mt-1">Immutable security ledger for all administrative actions</p>
        </div>
        <div class="flex items-center gap-4">
            <span class="px-6 py-2 bg-primary text-white text-[9px] font-black rounded-full uppercase tracking-widest border border-primary/20">
                Encrypted History
            </span>
        </div>
    </div>

    <div class="bg-white rounded-[2.5rem] border border-gray-100 shadow-2xl overflow-hidden">
        <div class="overflow-x-auto">
            <table class="w-full text-left border-collapse">
                <thead>
                    <tr class="bg-gray-50/50">
                        <th class="px-10 py-6 text-[10px] font-black text-gray-400 uppercase tracking-widest">Timestamp</th>
                        <th class="px-10 py-6 text-[10px] font-black text-gray-400 uppercase tracking-widest">Admin User</th>
                        <th class="px-10 py-6 text-[10px] font-black text-gray-400 uppercase tracking-widest">Action</th>
                        <th class="px-10 py-6 text-[10px] font-black text-gray-400 uppercase tracking-widest">Description</th>
                        <th class="px-10 py-6 text-[10px] font-black text-gray-400 uppercase tracking-widest">Client IP</th>
                    </tr>
                </thead>
                <tbody class="divide-y divide-gray-50">
                    @forelse($logs as $log)
                    <tr class="hover:bg-gray-50/30 transition-all">
                        <td class="px-10 py-8">
                            <p class="text-xs font-black text-gray-900 uppercase tracking-tight">{{ $log->created_at->format('M d, H:i:s') }}</p>
                            <p class="text-[9px] text-gray-400 font-medium">{{ $log->created_at->diffForHumans() }}</p>
                        </td>
                        <td class="px-10 py-8">
                            <div class="flex items-center gap-3">
                                <div class="w-8 h-8 rounded-lg bg-gray-100 flex items-center justify-center text-gray-400 font-black text-[10px]">
                                    {{ substr($log->user?->name ?? 'SYS', 0, 1) }}
                                </div>
                                <span class="text-xs font-black text-gray-700 uppercase tracking-tight">{{ $log->user?->name ?? 'System' }}</span>
                            </div>
                        </td>
                        <td class="px-10 py-8">
                            <span class="px-4 py-1.5 bg-gray-100 border border-gray-200 rounded-full text-[9px] font-black uppercase tracking-widest text-gray-600">
                                {{ str_replace('_', ' ', $log->action) }}
                            </span>
                        </td>
                        <td class="px-10 py-8 text-xs font-medium text-gray-500 max-w-sm">
                            {{ $log->description }}
                        </td>
                        <td class="px-10 py-8 text-[10px] font-mono text-gray-400">
                            {{ $log->ip_address }}
                        </td>
                    </tr>
                    @empty
                    <tr>
                        <td colspan="5" class="px-10 py-24 text-center">
                            <div class="w-16 h-16 bg-gray-50 rounded-full flex items-center justify-center mx-auto mb-4 text-gray-200">
                                <i class="fas fa-file-invoice text-2xl"></i>
                            </div>
                            <p class="text-[10px] font-black text-gray-300 uppercase tracking-widest">No audit data recorded in this period</p>
                        </td>
                    </tr>
                    @endforelse
                </tbody>
            </table>
        </div>
        
        <div class="p-10 bg-gray-50/50 border-t border-gray-100">
            {{ $logs->links() }}
        </div>
    </div>
</div>
@endsection
