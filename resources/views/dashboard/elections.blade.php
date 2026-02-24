@extends('layouts.student')

@section('title', 'Active Elections')
@section('page-title', 'Active Elections')

@section('content')
<style>
    /* Candidate Card Selection Styles */
    .candidate-radio { display: none; }

    .candidate-card {
        cursor: pointer;
        transition: all 0.35s cubic-bezier(0.34, 1.56, 0.64, 1);
        border: 3px solid transparent;
        position: relative;
        user-select: none;
    }

    .candidate-card:hover {
        transform: translateY(-6px);
        box-shadow: 0 20px 60px rgba(139, 0, 0, 0.15);
        border-color: rgba(139, 0, 0, 0.3);
    }

    .candidate-radio:checked + .candidate-card {
        border-color: #8b0000;
        box-shadow: 0 0 0 4px rgba(139, 0, 0, 0.15), 0 20px 60px rgba(139, 0, 0, 0.25);
        transform: translateY(-8px) scale(1.02);
        background: linear-gradient(135deg, #fff 0%, #fff8f8 100%);
    }

    .candidate-radio:checked + .candidate-card .select-indicator {
        opacity: 1;
        transform: scale(1);
    }

    .candidate-radio:checked + .candidate-card .candidate-photo-border {
        border-color: #8b0000;
    }

    .candidate-radio:checked + .candidate-card .vote-overlay {
        opacity: 1;
    }

    .select-indicator {
        position: absolute;
        top: 16px;
        right: 16px;
        width: 32px;
        height: 32px;
        background: #8b0000;
        border-radius: 50%;
        display: flex;
        align-items: center;
        justify-content: center;
        color: white;
        font-size: 14px;
        opacity: 0;
        transform: scale(0.5);
        transition: all 0.3s cubic-bezier(0.34, 1.56, 0.64, 1);
        z-index: 20;
    }

    .vote-overlay {
        position: absolute;
        inset: 0;
        background: linear-gradient(135deg, rgba(139,0,0,0.03), rgba(139,0,0,0.06));
        border-radius: inherit;
        opacity: 0;
        transition: opacity 0.3s ease;
        pointer-events: none;
    }

    /* Submit Button Animation */
    .submit-vote-btn {
        background: linear-gradient(135deg, #8b0000 0%, #660000 50%, #8b0000 100%);
        background-size: 200% auto;
        transition: all 0.4s ease;
        position: relative;
        overflow: hidden;
    }

    .submit-vote-btn::before {
        content: '';
        position: absolute;
        top: 0; left: -100%;
        width: 100%; height: 100%;
        background: linear-gradient(90deg, transparent, rgba(255,255,255,0.15), transparent);
        transition: left 0.5s ease;
    }

    .submit-vote-btn:hover:not(:disabled)::before {
        left: 100%;
    }

    .submit-vote-btn:hover:not(:disabled) {
        background-position: right center;
        transform: translateY(-3px);
        box-shadow: 0 20px 45px rgba(139, 0, 0, 0.35);
    }

    .submit-vote-btn:active:not(:disabled) {
        transform: translateY(0);
    }

    .submit-vote-btn:disabled {
        cursor: not-allowed;
        opacity: 0.5;
        filter: grayscale(0.3);
    }

    /* Pulse animation for the submit button when a candidate is selected */
    @keyframes pulse-glow {
        0%, 100% { box-shadow: 0 8px 25px rgba(139, 0, 0, 0.3); }
        50% { box-shadow: 0 12px 35px rgba(139, 0, 0, 0.55); }
    }

    .submit-vote-btn.active-pulse {
        animation: pulse-glow 2s ease-in-out infinite;
    }

    /* Category Section */
    .category-section {
        background: white;
        border-radius: 2rem;
        padding: 2.5rem;
        border: 1px solid #f3f4f6;
        box-shadow: 0 4px 20px rgba(0,0,0,0.05);
    }

    label.candidate-card-label {
        display: block;
        position: relative;
    }
</style>

<div class="space-y-12">
    <!-- Header Info -->
    <div class="bg-gradient-to-br from-[#8b0000] to-[#4a0000] rounded-[2.5rem] p-8 sm:p-12 text-white overflow-hidden relative">
        <div class="relative z-10 flex flex-col md:flex-row md:items-center justify-between gap-8">
            <div>
                <span class="text-[10px] font-black uppercase tracking-[0.3em] opacity-60 mb-3 block">IUEA Guild Election</span>
                <h3 class="text-3xl font-black mb-4 uppercase tracking-tighter">Cast Your Vote</h3>
                <p class="text-white/70 max-w-xl font-medium leading-relaxed">
                    <i class="fas fa-shield-alt mr-2 opacity-60"></i>
                    Select a candidate in each category, then click <strong>"Submit Your Vote"</strong>. Each vote is securely recorded and cannot be changed.
                </p>
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
            <div class="category-section">

                {{-- Category Header --}}
                <div class="flex items-center justify-between mb-8">
                    <div class="flex items-center gap-4">
                        <div class="w-2 h-10 bg-[#8b0000] rounded-full"></div>
                        <div>
                            <h4 class="text-xl font-black text-gray-900 uppercase tracking-tight">{{ $category->name }}</h4>
                            <p class="text-xs text-gray-400 font-semibold mt-0.5">{{ $category->candidates->count() }} candidate(s) — Select one below</p>
                        </div>
                    </div>
                    @if(in_array($category->id, $votedCategoryIds))
                        <span class="px-4 py-2 bg-green-50 text-green-600 rounded-xl text-[10px] font-black uppercase tracking-widest border border-green-100 flex items-center gap-2">
                            <i class="fas fa-check-circle"></i> Vote Cast
                        </span>
                    @else
                        <span class="pending-badge-{{ $category->id }} px-4 py-2 bg-blue-50 text-blue-600 rounded-xl text-[10px] font-black uppercase tracking-widest border border-blue-100 flex items-center gap-2">
                            <i class="fas fa-hand-pointer"></i> Select a Candidate
                        </span>
                    @endif
                </div>

                @if(!in_array($category->id, $votedCategoryIds))
                {{-- Voting Form for this category --}}
                <form action="{{ route('dashboard.vote') }}" method="POST" id="vote-form-{{ $category->id }}">
                    @csrf
                @endif

                {{-- Candidate Cards Grid --}}
                <div class="grid grid-cols-1 md:grid-cols-2 lg:grid-cols-3 gap-8 mb-8">
                    @foreach($category->candidates as $candidate)
                        @if(in_array($category->id, $votedCategoryIds))
                            {{-- Already voted - show locked cards --}}
                            <div class="bg-gray-50 rounded-[2.5rem] p-8 border border-gray-100 relative overflow-hidden opacity-70">
                                <div class="absolute top-0 right-0 w-32 h-32 bg-gray-100 rounded-full -mr-16 -mt-16"></div>
                                <div class="relative z-10 flex flex-col items-center text-center">
                                    <div class="mb-6">
                                        <div class="w-40 h-40 rounded-[2rem] p-1 border-4 border-gray-200 overflow-hidden mx-auto">
                                            <img src="{{ $candidate->photo_path ? (filter_var($candidate->photo_path, FILTER_VALIDATE_URL) ? $candidate->photo_path : Storage::url($candidate->photo_path)) : 'https://ui-avatars.com/api/?name='.urlencode($candidate->name).'&background=8b0000&color=fff&size=200' }}"
                                                 class="w-full h-full object-cover rounded-[1.8rem]" alt="{{ $candidate->name }}">
                                        </div>
                                    </div>
                                    <h5 class="text-lg font-black text-gray-700 uppercase tracking-tight mb-1">{{ $candidate->name }}</h5>
                                    <div class="flex flex-wrap justify-center gap-2 mb-4">
                                        <span class="text-[10px] font-black text-gray-500 uppercase tracking-widest bg-gray-100 px-2 py-1 rounded-md">{{ $candidate->faculty }}</span>
                                        <span class="text-[10px] font-black text-gray-400 uppercase tracking-widest bg-gray-50 px-2 py-1 rounded-md border border-gray-100">{{ $candidate->student_class }}</span>
                                    </div>
                                    <p class="text-sm text-gray-400 font-medium leading-relaxed italic line-clamp-3">
                                        "{{ $candidate->biography ?? 'Committed to excellence and student representation.' }}"
                                    </p>
                                    <div class="mt-6 w-full pt-4 border-t border-gray-100">
                                        <div class="py-3 bg-gray-100 text-gray-400 rounded-2xl text-[10px] font-black uppercase tracking-widest flex items-center justify-center gap-2">
                                            <i class="fas fa-lock"></i> Locked
                                        </div>
                                    </div>
                                </div>
                            </div>
                        @else
                            {{-- Selectable candidate card --}}
                            <label class="candidate-card-label" for="candidate-{{ $category->id }}-{{ $candidate->id }}">
                                <input
                                    type="radio"
                                    name="candidate_id"
                                    id="candidate-{{ $category->id }}-{{ $candidate->id }}"
                                    value="{{ $candidate->id }}"
                                    class="candidate-radio"
                                    data-category="{{ $category->id }}"
                                    onchange="onCandidateSelect({{ $category->id }}, '{{ addslashes($candidate->name) }}')"
                                >
                                <div class="candidate-card bg-white rounded-[2.5rem] p-8 border border-gray-100 shadow-xl relative overflow-hidden">
                                    <div class="vote-overlay rounded-[2.5rem]"></div>
                                    <div class="absolute top-0 right-0 w-32 h-32 bg-gray-50 rounded-full -mr-16 -mt-16 transition-colors"></div>

                                    {{-- Selected Checkmark --}}
                                    <div class="select-indicator">
                                        <i class="fas fa-check"></i>
                                    </div>

                                    {{-- Position Badge --}}
                                    <div class="absolute top-4 left-4 w-9 h-9 bg-white rounded-xl shadow-md flex items-center justify-center text-[#8b0000] font-black text-sm border border-gray-50">
                                        #{{ $candidate->position_number ?? $loop->iteration }}
                                    </div>

                                    <div class="relative z-10 flex flex-col items-center text-center h-full">
                                        {{-- Photo --}}
                                        <div class="mb-6 mt-4">
                                            <div class="candidate-photo-border w-44 h-44 rounded-[2rem] p-1 border-4 border-gray-100 overflow-hidden mx-auto transition-all duration-300">
                                                <img src="{{ $candidate->photo_path ? (filter_var($candidate->photo_path, FILTER_VALIDATE_URL) ? $candidate->photo_path : Storage::url($candidate->photo_path)) : 'https://ui-avatars.com/api/?name='.urlencode($candidate->name).'&background=8b0000&color=fff&size=200' }}"
                                                     class="w-full h-full object-cover rounded-[1.8rem]" alt="{{ $candidate->name }}">
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

                                        {{-- Click to select hint --}}
                                        <div class="mt-6 w-full pt-4 border-t border-gray-50">
                                            <div class="text-[10px] font-black text-gray-400 uppercase tracking-widest flex items-center justify-center gap-2">
                                                <i class="fas fa-hand-pointer"></i> Click to Select
                                            </div>
                                        </div>
                                    </div>
                                </div>
                            </label>
                        @endif
                    @endforeach
                </div>

                @if(!in_array($category->id, $votedCategoryIds))
                {{-- Submit Vote Button --}}
                <div class="flex flex-col items-center gap-4 mt-4 pt-6 border-t border-gray-100">
                    {{-- Selection status indicator --}}
                    <div id="selection-status-{{ $category->id }}" class="text-sm text-gray-400 font-semibold flex items-center gap-2">
                        <i class="fas fa-info-circle"></i>
                        <span id="selection-text-{{ $category->id }}">No candidate selected yet. Please select one above.</span>
                    </div>

                    <button
                        type="submit"
                        id="submit-btn-{{ $category->id }}"
                        disabled
                        class="submit-vote-btn w-full max-w-md py-5 text-white rounded-2xl font-black text-sm uppercase tracking-widest shadow-lg flex items-center justify-center gap-3"
                        onclick="return confirmVote({{ $category->id }})"
                    >
                        <i class="fas fa-vote-yea text-lg"></i>
                        Submit Your Vote
                        <i class="fas fa-arrow-right"></i>
                    </button>

                    <p class="text-[10px] text-gray-300 font-semibold uppercase tracking-widest text-center">
                        <i class="fas fa-lock mr-1"></i> Your vote is final and cannot be changed after submission
                    </p>
                </div>

                </form>
                @endif

            </div>
        @endforeach
    @endif
</div>

{{-- Confirmation Modal --}}
<div id="voteConfirmModal" class="fixed inset-0 z-50 hidden items-center justify-center p-4">
    <div class="absolute inset-0 bg-black/60 backdrop-blur-sm" onclick="closeConfirmModal()"></div>
    <div class="relative bg-white rounded-[2.5rem] p-10 max-w-md w-full shadow-2xl text-center z-10 transform transition-all" id="modalContent">
        <div class="w-20 h-20 bg-red-50 rounded-3xl flex items-center justify-center mx-auto mb-6">
            <i class="fas fa-vote-yea text-4xl text-[#8b0000]"></i>
        </div>
        <h3 class="text-2xl font-black text-gray-900 uppercase tracking-tight mb-2">Confirm Your Vote</h3>
        <p class="text-gray-500 font-medium mb-2">You are about to vote for:</p>
        <p class="text-xl font-black text-[#8b0000] uppercase mb-6" id="modalCandidateName">—</p>
        <p class="text-sm text-gray-400 font-medium mb-8 leading-relaxed">
            <i class="fas fa-exclamation-triangle text-amber-400 mr-1"></i>
            This action is <strong>permanent and cannot be undone</strong>. Are you absolutely sure?
        </p>
        <div class="flex gap-4">
            <button onclick="closeConfirmModal()" class="flex-1 py-4 bg-gray-100 text-gray-600 rounded-2xl font-black text-sm uppercase tracking-widest hover:bg-gray-200 transition-all">
                Cancel
            </button>
            <button onclick="submitConfirmedVote()" class="flex-1 py-4 bg-[#8b0000] text-white rounded-2xl font-black text-sm uppercase tracking-widest hover:bg-[#660000] transition-all shadow-lg shadow-red-900/30 flex items-center justify-center gap-2">
                <i class="fas fa-check"></i> Confirm Vote
            </button>
        </div>
    </div>
</div>
@endsection

@section('scripts')
<script>
    // ─── VOTE SELECTION LOGIC ───────────────────────────────────────────────
    let activeFormId = null;

    function onCandidateSelect(categoryId, candidateName) {
        const submitBtn = document.getElementById('submit-btn-' + categoryId);
        const selectionText = document.getElementById('selection-text-' + categoryId);
        const statusDiv = document.getElementById('selection-status-' + categoryId);

        if (submitBtn) {
            submitBtn.disabled = false;
            submitBtn.classList.add('active-pulse');
        }

        if (selectionText) {
            selectionText.innerHTML = '<i class="fas fa-check-circle text-green-500"></i> Selected: <strong class="text-gray-700">' + candidateName + '</strong>';
        }

        if (statusDiv) {
            statusDiv.classList.remove('text-gray-400');
            statusDiv.classList.add('text-green-600');
        }
    }

    // ─── CONFIRMATION MODAL ─────────────────────────────────────────────────
    function confirmVote(categoryId) {
        const radios = document.querySelectorAll('input[name="candidate_id"][data-category="' + categoryId + '"]');
        let selectedCandidate = null;

        radios.forEach(radio => {
            if (radio.checked) {
                const label = radio.nextElementSibling;
                const nameEl = label ? label.querySelector('h5') : null;
                selectedCandidate = nameEl ? nameEl.textContent.trim() : 'your candidate';
            }
        });

        if (!selectedCandidate) return false;

        activeFormId = categoryId;
        document.getElementById('modalCandidateName').textContent = selectedCandidate;

        const modal = document.getElementById('voteConfirmModal');
        modal.classList.remove('hidden');
        modal.classList.add('flex');

        // Animate modal in
        setTimeout(() => {
            document.getElementById('modalContent').style.transform = 'scale(1)';
        }, 10);

        return false; // Prevent form from submitting immediately
    }

    function closeConfirmModal() {
        const modal = document.getElementById('voteConfirmModal');
        modal.classList.add('hidden');
        modal.classList.remove('flex');
        activeFormId = null;
    }

    function submitConfirmedVote() {
        if (activeFormId !== null) {
            const form = document.getElementById('vote-form-' + activeFormId);
            if (form) {
                // Show loading state
                const btn = document.querySelector('#voteConfirmModal button:last-child');
                if (btn) {
                    btn.innerHTML = '<i class="fas fa-spinner fa-spin"></i> Submitting...';
                    btn.disabled = true;
                }
                form.submit();
            }
        }
    }

    // Close modal on Escape key
    document.addEventListener('keydown', function(e) {
        if (e.key === 'Escape') closeConfirmModal();
    });

    // ─── COUNTDOWN TIMER ────────────────────────────────────────────────────
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
                if (el) el.innerHTML = "<span class='text-red-400 text-lg'>POLLS CLOSED</span>";
                document.querySelectorAll('.submit-vote-btn').forEach(btn => {
                    btn.disabled = true;
                    btn.classList.remove('active-pulse');
                });
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
