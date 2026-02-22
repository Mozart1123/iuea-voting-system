@extends('layouts.student')

@section('title', 'Active Elections')
@section('page-title', 'Active Elections')

@section('content')
<div class="space-y-12">
    <!-- Header Info -->
    <div class="bg-gradient-to-br from-[#8b0000] to-[#660000] rounded-[2.5rem] p-8 sm:p-12 text-white overflow-hidden relative">
        <div class="relative z-10 flex flex-col md:flex-row md:items-center justify-between gap-8">
            <div>
                <h3 class="text-3xl font-black mb-4 uppercase tracking-tighter">Cast Your Vote</h3>
                <p class="text-white/70 max-w-xl font-medium leading-relaxed">Choose your representatives wisely. You can only vote once per category. All votes are anonymous and securely recorded.</p>
            </div>
            @if($electionEndTime)
            <div class="bg-white/10 backdrop-blur-md rounded-[2rem] p-6 border border-white/20 flex flex-col items-center gap-2 min-w-[200px]">
                <span class="text-[10px] font-black uppercase tracking-widest opacity-70">Polls Close In</span>
                <div class="flex items-center gap-3 font-black text-2xl" id="electionCountdown">
                    <div class="flex flex-col items-center">
                        <span id="cd-days">00</span>
                        <span class="text-[8px] opacity-50 uppercase">Days</span>
                    </div>
                    <span class="opacity-30">:</span>
                    <div class="flex flex-col items-center">
                        <span id="cd-hours">00</span>
                        <span class="text-[8px] opacity-50 uppercase">Hrs</span>
                    </div>
                    <span class="opacity-30">:</span>
                    <div class="flex flex-col items-center">
                        <span id="cd-minutes">00</span>
                        <span class="text-[8px] opacity-50 uppercase">Min</span>
                    </div>
                    <span class="opacity-30">:</span>
                    <div class="flex flex-col items-center">
                        <span id="cd-seconds">00</span>
                        <span class="text-[8px] opacity-50 uppercase">Sec</span>
                    </div>
                </div>
            </div>
            @endif
        </div>
        <div class="absolute right-0 bottom-0 opacity-10 translate-x-12 translate-y-12">
            <i class="fas fa-check-to-slot text-[15rem]"></i>
        </div>
    </div>

    @if($categories->isEmpty())
        <div class="bg-white rounded-[2rem] p-16 text-center border border-gray-100 shadow-xl">
            <div class="w-20 h-20 bg-gray-50 rounded-3xl flex items-center justify-center mx-auto mb-6 text-gray-300">
                <i class="fas fa-calendar-times text-4xl"></i>
            </div>
            <h4 class="text-xl font-black text-gray-900 uppercase tracking-tight">No Active Elections</h4>
            <p class="text-gray-400 font-medium mt-2">There are no categories currently open for voting. Please check back later.</p>
        </div>
    @else
        @foreach($categories as $category)
            <div class="space-y-6">
                <div class="flex items-center justify-between">
                    <div class="flex items-center gap-4">
                        <div class="w-2 h-8 bg-[#8b0000] rounded-full"></div>
                        <h4 class="text-xl font-black text-gray-900 uppercase tracking-tight">{{ $category->name }}</h4>
                    </div>
                    @if(in_array($category->id, $votedCategoryIds))
                        <span class="px-4 py-2 bg-green-50 text-green-600 rounded-xl text-[10px] font-black uppercase tracking-widest border border-green-100 flex items-center gap-2">
                            <i class="fas fa-check-circle"></i> Vote Cast
                        </span>
                    @else
                        <span class="px-4 py-2 bg-blue-50 text-blue-600 rounded-xl text-[10px] font-black uppercase tracking-widest border border-blue-100 flex items-center gap-2">
                            <i class="fas fa-clock"></i> Pending
                        </span>
                    @endif
                </div>

                <div class="grid grid-cols-1 md:grid-cols-2 lg:grid-cols-3 gap-8">
                    @foreach($category->candidates as $candidate)
                        <div class="group bg-white rounded-[2.5rem] p-8 border border-gray-100 shadow-xl hover:shadow-2xl transition-all hover:-translate-y-2 relative overflow-hidden">
                            <!-- Background Decoration -->
                            <div class="absolute top-0 right-0 w-32 h-32 bg-gray-50 rounded-full -mr-16 -mt-16 group-hover:bg-red-50 transition-colors"></div>
                            
                            <div class="relative z-10 flex flex-col items-center text-center h-full">
                                <div class="mb-6 relative">
                                    <div class="w-56 h-56 rounded-[2.5rem] p-1 border-4 border-gray-100 group-hover:border-[#8b0000] transition-colors overflow-hidden">
                                        <img src="{{ $candidate->photo_path ? (filter_var($candidate->photo_path, FILTER_VALIDATE_URL) ? $candidate->photo_path : Storage::url($candidate->photo_path)) : 'https://ui-avatars.com/api/?name='.urlencode($candidate->name).'&background=8b0000&color=fff&size=200' }}" 
                                             class="w-full h-full object-cover rounded-[2.2rem]" alt="{{ $candidate->name }}">
                                    </div>
                                    <div class="absolute bottom-2 right-4 w-10 h-10 bg-white rounded-xl shadow-lg flex items-center justify-center text-[#8b0000] font-black text-sm">
                                        #{{ $candidate->position_number ?? $loop->iteration }}
                                    </div>
                                </div>

                                <h5 class="text-lg font-black text-gray-900 uppercase tracking-tight mb-1">{{ $candidate->name }}</h5>
                                <div class="flex flex-wrap justify-center gap-2 mb-4">
                                    <span class="text-[10px] font-black text-[#8b0000] uppercase tracking-widest bg-red-50 px-2 py-1 rounded-md border border-red-100">{{ $candidate->faculty }}</span>
                                    <span class="text-[10px] font-black text-gray-500 uppercase tracking-widest bg-gray-50 px-2 py-1 rounded-md border border-gray-100">{{ $candidate->student_class }}</span>
                                </div>
                                
                                <div class="flex-grow">
                                    <p class="text-sm text-gray-500 font-medium leading-relaxed italic line-clamp-3">
                                        "{{ $candidate->biography ?? 'Committed to excellence and student representation.' }}"
                                    </p>
                                </div>

                                <div class="mt-8 w-full pt-6 border-t border-gray-50">
                                    @if(in_array($category->id, $votedCategoryIds))
                                        <button disabled class="w-full py-4 bg-gray-100 text-gray-400 rounded-2xl font-black text-[10px] uppercase tracking-widest cursor-not-allowed">
                                            Status: Locked
                                        </button>
                                    @else
                                        <form action="{{ route('dashboard.vote') }}" method="POST">
                                            @csrf
                                            <input type="hidden" name="candidate_id" value="{{ $candidate->id }}">
                                            <button type="submit" onclick="return confirm('Are you sure you want to vote for {{ $candidate->name }}? This action cannot be undone.')" 
                                                    class="w-full py-4 bg-[#8b0000] text-white rounded-2xl font-black text-[10px] uppercase tracking-widest shadow-lg shadow-red-900/20 hover:scale-[1.05] active:scale-[0.95] transition-all flex items-center justify-center gap-2">
                                                <i class="fas fa-vote-yea"></i> Vote for Candidate
                                            </button>
                                        </form>
                                    @endif
                                </div>
                            </div>
                        </div>
                    @endforeach
                </div>
            </div>
        @endforeach
    @endif
</div>
@endsection

@section('scripts')
<script>
    (function() {
        const endTimeStr = '{{ $electionEndTime }}';
        if (!endTimeStr) return;

        const endTime = new Date(endTimeStr).getTime();
        
        const timer = setInterval(() => {
            const now = new Date().getTime();
            const distance = endTime - now;

            if (distance < 0) {
                clearInterval(timer);
                const el = document.getElementById('electionCountdown');
                if (el) el.innerHTML = "<span class='text-red-400'>POLLS CLOSED</span>";
                document.querySelectorAll('button[type="submit"]').forEach(btn => btn.disabled = true);
                return;
            }

            const days = Math.floor(distance / (1000 * 60 * 60 * 24));
            const hours = Math.floor((distance % (1000 * 60 * 60 * 24)) / (1000 * 60 * 60));
            const minutes = Math.floor((distance % (1000 * 60 * 60)) / (1000 * 60));
            const seconds = Math.floor((distance % (1000 * 60)) / 1000);

            const d_el = document.getElementById('cd-days');
            const h_el = document.getElementById('cd-hours');
            const m_el = document.getElementById('cd-minutes');
            const s_el = document.getElementById('cd-seconds');

            if (d_el) d_el.textContent = days.toString().padStart(2, '0');
            if (h_el) h_el.textContent = hours.toString().padStart(2, '0');
            if (m_el) m_el.textContent = minutes.toString().padStart(2, '0');
            if (s_el) s_el.textContent = seconds.toString().padStart(2, '0');
        }, 1000);
    })();
</script>
@endsection

