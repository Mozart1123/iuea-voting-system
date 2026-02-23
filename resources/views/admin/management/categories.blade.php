@extends('layouts.admin')

@section('title', 'Position Management')
@section('header', 'Election Categories')

@section('content')
<div class="grid grid-cols-1 lg:grid-cols-3 gap-12">
    <!-- Creation Form -->
    <div class="bg-white p-10 rounded-[3rem] border border-gray-100 shadow-2xl h-fit">
        <h3 class="text-xl font-black text-gray-900 uppercase tracking-tight mb-8">Create Position</h3>
        <form action="{{ route('admin.manage.categories.store') }}" method="POST" class="space-y-6">
            @csrf
            <div class="space-y-2">
                <label class="text-[10px] font-black text-gray-400 uppercase tracking-widest ml-1">Position Name</label>
                <input type="text" name="name" required placeholder="e.g. Guild President" class="w-full px-6 py-4 bg-gray-50 border-2 border-transparent rounded-2xl focus:bg-white focus:border-primary outline-none transition-all text-sm font-bold">
            </div>
            <div class="space-y-2">
                <label class="text-[10px] font-black text-gray-400 uppercase tracking-widest ml-1">Description</label>
                <textarea name="description" rows="3" placeholder="Brief role description..." class="w-full px-6 py-4 bg-gray-50 border-2 border-transparent rounded-2xl focus:bg-white focus:border-primary outline-none transition-all text-sm font-bold"></textarea>
            </div>
            
            <div class="grid grid-cols-1 gap-6">
                <div class="space-y-2">
                    <label class="text-[10px] font-black text-gray-400 uppercase tracking-widest ml-1">Starts At (Date & Time)</label>
                    <input type="datetime-local" name="starts_at" class="w-full px-6 py-4 bg-gray-50 border-2 border-transparent rounded-2xl focus:bg-white focus:border-primary outline-none transition-all text-sm font-bold">
                </div>
                <div class="space-y-2">
                    <label class="text-[10px] font-black text-gray-400 uppercase tracking-widest ml-1">Ends At (Date & Time)</label>
                    <input type="datetime-local" name="ends_at" class="w-full px-6 py-4 bg-gray-50 border-2 border-transparent rounded-2xl focus:bg-white focus:border-primary outline-none transition-all text-sm font-bold">
                </div>
            </div>

            <div class="space-y-2">
                <label class="text-[10px] font-black text-gray-400 uppercase tracking-widest ml-1">Faculty Restriction (Optional)</label>
                <input type="text" name="faculty_restriction" placeholder="e.g. BIT" class="w-full px-6 py-4 bg-gray-50 border-2 border-transparent rounded-2xl focus:bg-white focus:border-primary outline-none transition-all text-sm font-bold">
            </div>
            
            <button type="submit" class="w-full py-4 bg-primary text-white font-black text-[10px] uppercase tracking-widest rounded-2xl shadow-lg shadow-red-900/20 hover:scale-[1.02] transition-all">
                Finalise Category
            </button>
        </form>
    </div>

    <!-- Categories List -->
    <div class="lg:col-span-2 bg-white rounded-[3rem] border border-gray-100 shadow-2xl overflow-hidden">
        <div class="p-10 border-b border-gray-50">
            <h3 class="text-xl font-black text-gray-900 uppercase tracking-tight">Active Ballot Positions</h3>
        </div>
        <div class="overflow-x-auto">
            <table class="w-full text-left border-collapse">
                <thead>
                    <tr class="bg-gray-50/50">
                        <th class="px-10 py-6 text-[10px] font-black text-gray-400 uppercase tracking-widest">Position</th>
                        <th class="px-10 py-6 text-[10px] font-black text-gray-400 uppercase tracking-widest">Voting Window</th>
                        <th class="px-10 py-6 text-[10px] font-black text-gray-400 uppercase tracking-widest text-center">Candidates</th>
                        <th class="px-10 py-6 text-[10px] font-black text-gray-400 uppercase tracking-widest">Status</th>
                    </tr>
                </thead>
                <tbody class="divide-y divide-gray-50">
                    @foreach($categories as $cat)
                    <tr class="hover:bg-gray-50/30 transition-all">
                        <td class="px-10 py-8">
                            <p class="text-sm font-black text-gray-900 uppercase">{{ $cat->name }}</p>
                            <div class="flex items-center gap-2 mt-1">
                                <span class="px-2 py-0.5 bg-gray-100 rounded text-[8px] font-black uppercase text-gray-500 border border-gray-200">
                                    {{ $cat->faculty_restriction ?? 'UNIVERSAL' }}
                                </span>
                            </div>
                        </td>
                        <td class="px-10 py-8">
                            @if($cat->starts_at && $cat->ends_at)
                                <div class="space-y-1">
                                    <p class="text-[10px] font-black text-gray-900 uppercase">From: <span class="text-gray-500">{{ $cat->starts_at->format('M d, H:i') }}</span></p>
                                    <p class="text-[10px] font-black text-gray-900 uppercase">To: <span class="text-gray-500">{{ $cat->ends_at->format('M d, H:i') }}</span></p>
                                </div>
                            @else
                                <span class="text-[10px] font-black text-gray-300 uppercase italic">Not Scheduled</span>
                            @endif
                        </td>
                        <td class="px-10 py-8 text-center text-sm font-black text-primary">
                            {{ $cat->candidates_count }}
                        </td>
                        <td class="px-10 py-8">
                            <span class="flex items-center gap-2">
                                <span class="w-2 h-2 rounded-full {{ $cat->is_active ? 'bg-green-500' : 'bg-gray-300' }}"></span>
                                <span class="text-[10px] font-black {{ $cat->is_active ? 'text-green-700' : 'text-gray-400' }} uppercase tracking-widest">{{ $cat->is_active ? 'ACTIVE' : 'LOCKED' }}</span>
                            </span>
                        </td>
                    </tr>
                    @endforeach
                </tbody>
            </table>
        </div>
    </div>
</div>
@endsection
