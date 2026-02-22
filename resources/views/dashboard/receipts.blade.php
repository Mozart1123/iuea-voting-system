@extends('layouts.student')

@section('title', 'My Vote Receipts')
@section('page-title', 'My Vote Receipts')

@section('content')
<div class="space-y-12">
    <!-- Header Info -->
    <div class="bg-gradient-to-br from-[#1a1a1a] to-[#333333] rounded-[2.5rem] p-8 sm:p-12 text-white overflow-hidden relative">
        <div class="relative z-10">
            <h3 class="text-3xl font-black mb-4">Voting Records</h3>
            <p class="text-white/70 max-w-xl font-medium leading-relaxed">This is your digital proof of participation. These receipts verify that your vote was successfully counted in the election system.</p>
        </div>
        <div class="absolute right-0 bottom-0 opacity-10 translate-x-12 translate-y-12">
            <i class="fas fa-receipt text-[15rem]"></i>
        </div>
    </div>

    @if($votes->isEmpty())
        <div class="bg-white rounded-[2rem] p-16 text-center border border-gray-100 shadow-xl">
            <div class="w-20 h-20 bg-gray-50 rounded-3xl flex items-center justify-center mx-auto mb-6 text-gray-300">
                <i class="fas fa-box-open text-4xl"></i>
            </div>
            <h4 class="text-xl font-black text-gray-900 uppercase tracking-tight">No Receipts Found</h4>
            <p class="text-gray-400 font-medium mt-2">You haven't cast any votes yet. Once you do, your digital receipts will appear here.</p>
            <a href="{{ route('dashboard.elections') }}" class="mt-8 inline-flex items-center gap-2 px-8 py-4 bg-[#8b0000] text-white rounded-2xl font-black text-xs uppercase tracking-widest hover:scale-[1.05] transition-all">
                <i class="fas fa-vote-yea"></i> Go to Elections
            </a>
        </div>
    @else
        <div class="grid grid-cols-1 lg:grid-cols-2 gap-8">
            @foreach($votes as $vote)
                <div class="bg-white rounded-[2.5rem] p-1 border border-gray-100 shadow-xl overflow-hidden group">
                    <div class="p-8">
                        <div class="flex justify-between items-start mb-10">
                            <div>
                                <span class="text-[10px] font-black text-[#8b0000] uppercase tracking-widest bg-red-50 px-3 py-1 rounded-full border border-red-100 mb-3 inline-block">Official Receipt</span>
                                <h4 class="text-xl font-black text-gray-900 uppercase tracking-tight">{{ $vote->category->name }}</h4>
                            </div>
                            <div class="w-12 h-12 bg-gray-50 rounded-2xl flex items-center justify-center text-gray-400 group-hover:text-[#8b0000] group-hover:bg-red-50 transition-all">
                                <i class="fas fa-shield-check text-xl"></i>
                            </div>
                        </div>

                        <div class="space-y-6">
                            <div class="flex items-center gap-4 p-4 bg-gray-50 rounded-2xl border border-gray-100">
                                <div class="w-12 h-12 rounded-full overflow-hidden border-2 border-white shadow-sm shrink-0">
                                    <img src="{{ $vote->candidate->photo_path ? (filter_var($vote->candidate->photo_path, FILTER_VALIDATE_URL) ? $vote->candidate->photo_path : Storage::url($vote->candidate->photo_path)) : 'https://ui-avatars.com/api/?name='.urlencode($vote->candidate->name).'&background=8b0000&color=fff' }}" 
                                         class="w-full h-full object-cover" alt="{{ $vote->candidate->name }}">
                                </div>
                                <div class="flex-1 min-w-0">
                                    <p class="text-[10px] font-black text-gray-400 uppercase tracking-widest">Candidate Selected</p>
                                    <p class="text-sm font-black text-gray-900 truncate uppercase">{{ $vote->candidate->name }}</p>
                                </div>
                            </div>

                            <div class="grid grid-cols-2 gap-4">
                                <div class="p-4 bg-gray-50 rounded-2xl border border-gray-100">
                                    <p class="text-[10px] font-black text-gray-400 uppercase tracking-widest mb-1">Date Cast</p>
                                    <p class="text-xs font-black text-gray-900">{{ $vote->created_at->format('M d, Y') }}</p>
                                </div>
                                <div class="p-4 bg-gray-50 rounded-2xl border border-gray-100">
                                    <p class="text-[10px] font-black text-gray-400 uppercase tracking-widest mb-1">Time Cast</p>
                                    <p class="text-xs font-black text-gray-900">{{ $vote->created_at->format('H:i:s') }}</p>
                                </div>
                            </div>

                            <div class="p-4 bg-gray-100/50 rounded-2xl border border-dashed border-gray-200">
                                <p class="text-[10px] font-black text-gray-400 uppercase tracking-widest mb-1">Receipt Reference</p>
                                <p class="text-[10px] font-mono font-medium text-gray-600 break-all">{{ hash('sha256', $vote->id . $vote->created_at) }}</p>
                            </div>
                        </div>
                        
                        <div class="mt-8 pt-6 border-t border-gray-50 flex items-center justify-between">
                            <div class="flex items-center gap-2">
                                <div class="w-2 h-2 rounded-full bg-green-500 animate-pulse"></div>
                                <span class="text-[10px] font-black text-green-600 uppercase tracking-widest">Verified on Blockchain</span>
                            </div>
                            <button onclick="window.print()" class="text-[10px] font-black text-gray-400 hover:text-gray-900 uppercase tracking-widest flex items-center gap-2">
                                <i class="fas fa-print"></i> Print
                            </button>
                        </div>
                    </div>
                </div>
            @endforeach
        </div>
    @endif
</div>
@endsection
