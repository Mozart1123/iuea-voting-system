@extends('layouts.student')

@section('title', 'Candidate Nomination')
@section('page-title', 'Candidate Nomination')

@section('content')
<div class="max-w-4xl mx-auto space-y-12">
    <!-- Header -->
    <div class="bg-gradient-to-br from-[#8b0000] to-[#b22222] rounded-[2.5rem] p-8 sm:p-12 text-white relative overflow-hidden shadow-2xl">
        <div class="relative z-10">
            <h3 class="text-3xl font-black mb-4 uppercase tracking-tighter">Run for Office</h3>
            <p class="text-white/80 max-w-xl font-medium leading-relaxed">Step up and lead your fellow students. Submit your candidacy for a position in the Guild Representative Council.</p>
        </div>
        <div class="absolute right-0 bottom-0 opacity-10 translate-x-12 translate-y-12">
            <i class="fas fa-id-card text-[15rem]"></i>
        </div>
    </div>

    @if($existingNomination)
        <!-- Case: Already nominated -->
        <div class="bg-white rounded-[2.5rem] p-12 border border-gray-100 shadow-xl text-center">
            <div class="w-24 h-24 rounded-full mx-auto mb-8 relative">
                <img src="{{ $existingNomination->photo_path ? Storage::url($existingNomination->photo_path) : 'https://ui-avatars.com/api/?name='.urlencode($existingNomination->name).'&background=8b0000&color=fff' }}" 
                     class="w-full h-full object-cover rounded-full border-4 border-gray-50 shadow-lg">
                <div class="absolute -bottom-2 -right-2 w-10 h-10 bg-white rounded-xl shadow-lg flex items-center justify-center">
                    @if($existingNomination->status === 'approved')
                        <i class="fas fa-check-circle text-green-500"></i>
                    @elseif($existingNomination->status === 'pending')
                        <i class="fas fa-clock text-orange-500"></i>
                    @else
                        <i class="fas fa-times-circle text-red-500"></i>
                    @endif
                </div>
            </div>

            <h4 class="text-2xl font-black text-gray-900 uppercase tracking-tight mb-2">My Nomination Status</h4>
            <div class="inline-flex items-center gap-2 px-4 py-2 rounded-xl mb-8
                @if($existingNomination->status === 'approved') bg-green-50 text-green-600 border border-green-100
                @elseif($existingNomination->status === 'pending') bg-orange-50 text-orange-600 border border-orange-100
                @else bg-red-50 text-red-600 border border-red-100 @endif">
                <span class="text-xs font-black uppercase tracking-widest">{{ $existingNomination->status }}</span>
            </div>

            <div class="max-w-md mx-auto p-6 bg-gray-50 rounded-2xl border border-gray-100 text-left space-y-4">
                <div class="flex justify-between border-b border-gray-200 pb-2">
                    <span class="text-xs font-black text-gray-400 uppercase tracking-widest">Category</span>
                    <span class="text-sm font-bold text-gray-900">{{ $existingNomination->category->name }}</span>
                </div>
                <div class="flex justify-between border-b border-gray-200 pb-2">
                    <span class="text-xs font-black text-gray-400 uppercase tracking-widest">Faculty</span>
                    <span class="text-sm font-bold text-gray-900">{{ $existingNomination->faculty }}</span>
                </div>
                <div class="text-left">
                    <span class="text-xs font-black text-gray-400 uppercase tracking-widest block mb-2">Manifesto</span>
                    <p class="text-sm text-gray-600 italic leading-relaxed">"{{ $existingNomination->biography }}"</p>
                </div>
            </div>

            @if($existingNomination->status === 'rejected')
                <div class="mt-8">
                    <p class="text-sm text-gray-500 mb-6">Your nomination was not approved. You can resubmit if needed.</p>
                </div>
            @endif
        </div>
    @elseif($categoriesInNomination->isEmpty())
        <!-- Case: No open nominations -->
        <div class="bg-white rounded-[2.5rem] p-16 text-center border border-gray-100 shadow-xl">
            <div class="w-20 h-20 bg-gray-50 rounded-3xl flex items-center justify-center mx-auto mb-6 text-gray-300">
                <i class="fas fa-lock text-3xl"></i>
            </div>
            <h4 class="text-xl font-black text-gray-900 uppercase tracking-tight">Nominations are Closed</h4>
            <p class="text-gray-400 font-medium mt-2">The period for submitting candidacies for the current election has ended or not yet started.</p>
        </div>
    @else
        <!-- Case: Nomination Form -->
        <div class="bg-white rounded-[2.5rem] p-8 sm:p-12 border border-gray-100 shadow-xl overflow-hidden">
            <form action="{{ route('dashboard.nomination.submit') }}" method="POST" enctype="multipart/form-data" class="space-y-8">
                @csrf
                
                <div class="grid grid-cols-1 md:grid-cols-2 gap-8">
                    <div class="space-y-2">
                        <label class="text-[10px] font-black text-gray-400 uppercase tracking-widest ml-1">Election Category</label>
                        <select name="category_id" required class="w-full px-6 py-4 bg-gray-50 border border-gray-100 rounded-2xl text-sm font-bold focus:ring-2 focus:ring-[#8b0000] focus:border-transparent outline-none transition-all">
                            @foreach($categoriesInNomination as $category)
                                <option value="{{ $category->id }}">{{ $category->name }}</option>
                            @endforeach
                        </select>
                    </div>

                    <div class="space-y-2">
                        <label class="text-[10px] font-black text-gray-400 uppercase tracking-widest ml-1">Your Faculty</label>
                        <select name="faculty" required class="w-full px-6 py-4 bg-gray-50 border border-gray-100 rounded-2xl text-sm font-bold focus:ring-2 focus:ring-[#8b0000] focus:border-transparent outline-none transition-all">
                            <option value="">Select Faculty</option>
                            <option value="Science & Technology" {{ Auth::user()->faculty == 'Science & Technology' ? 'selected' : '' }}>Science & Technology</option>
                            <option value="Business & Management" {{ Auth::user()->faculty == 'Business & Management' ? 'selected' : '' }}>Business & Management</option>
                            <option value="Law" {{ Auth::user()->faculty == 'Law' ? 'selected' : '' }}>Law</option>
                            <option value="Social Sciences" {{ Auth::user()->faculty == 'Social Sciences' ? 'selected' : '' }}>Social Sciences</option>
                            <option value="Engineering" {{ Auth::user()->faculty == 'Engineering' ? 'selected' : '' }}>Engineering</option>
                        </select>
                    </div>
                </div>

                <div class="grid grid-cols-1 md:grid-cols-2 gap-8">
                    <div class="space-y-2">
                        <label class="text-[10px] font-black text-gray-400 uppercase tracking-widest ml-1">Class / Year</label>
                        <input type="text" name="student_class" required placeholder="e.g. BSIT Year 3" 
                               class="w-full px-6 py-4 bg-gray-50 border border-gray-100 rounded-2xl text-sm font-bold focus:ring-2 focus:ring-[#8b0000] focus:border-transparent outline-none transition-all">
                    </div>

                    <div class="space-y-2">
                        <label class="text-[10px] font-black text-gray-400 uppercase tracking-widest ml-1">Profile Photo</label>
                        <div class="relative group">
                            <input type="file" name="photo" accept="image/*" class="absolute inset-0 w-full h-full opacity-0 cursor-pointer z-10">
                            <div class="flex items-center gap-4 px-6 py-4 bg-gray-50 border border-gray-100 rounded-2xl group-hover:bg-gray-100 transition-all">
                                <i class="fas fa-camera text-gray-400"></i>
                                <span class="text-sm font-bold text-gray-400">Upload Professional Photo</span>
                            </div>
                        </div>
                    </div>
                </div>

                <div class="space-y-2">
                    <label class="text-[10px] font-black text-gray-400 uppercase tracking-widest ml-1">Your Manifesto / Bio</label>
                    <textarea name="biography" required rows="6" placeholder="Tell the students why they should vote for you..." 
                              class="w-full px-6 py-4 bg-gray-50 border border-gray-100 rounded-2xl text-sm font-bold focus:ring-2 focus:ring-[#8b0000] focus:border-transparent outline-none transition-all resize-none"></textarea>
                    <p class="text-[10px] text-gray-400 font-medium mt-2">Minimum 50 characters required.</p>
                </div>

                <div class="pt-4">
                    <button type="submit" class="w-full sm:w-auto px-10 py-5 bg-[#8b0000] text-white rounded-2xl font-black text-xs uppercase tracking-widest shadow-lg shadow-red-900/20 hover:scale-[1.02] active:scale-[0.98] transition-all flex items-center justify-center gap-3">
                        <i class="fas fa-paper-plane"></i> Submit Candidacy
                    </button>
                </div>
            </form>
        </div>
    @endif
</div>
@endsection
