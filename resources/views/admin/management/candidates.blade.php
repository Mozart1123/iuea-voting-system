@extends('layouts.admin')

@section('title', 'Candidate Vetting')
@section('header', 'Candidate Management')

@section('content')
<div class="grid grid-cols-1 lg:grid-cols-3 gap-12">
    <!-- Registration Form -->
    <div class="bg-white p-10 rounded-[3rem] border border-gray-100 shadow-2xl h-fit">
        <h3 class="text-xl font-black text-gray-900 uppercase tracking-tight mb-8">Register Candidate</h3>
        <form action="{{ route('admin.manage.candidates.store') }}" method="POST" enctype="multipart/form-data" class="space-y-6">
            @csrf
            
            <div class="space-y-2">
                <label class="text-[10px] font-black text-gray-400 uppercase tracking-widest ml-1">Candidate Photo</label>
                <div class="relative">
                    <input type="file" name="photo" accept="image/*" class="w-full px-6 py-4 bg-gray-50 border-2 border-dashed border-gray-200 rounded-2xl focus:border-primary outline-none transition-all text-xs font-bold file:mr-4 file:py-2 file:px-4 file:rounded-full file:border-0 file:text-[10px] file:font-black file:bg-primary file:text-white hover:file:bg-primary-dark">
                </div>
            </div>

            <div class="space-y-2">
                <label class="text-[10px] font-black text-gray-400 uppercase tracking-widest ml-1">Full Name</label>
                <input type="text" name="name" required placeholder="e.g. NAMONO SARAH" class="w-full px-6 py-4 bg-gray-50 border-2 border-transparent rounded-2xl focus:bg-white focus:border-primary outline-none transition-all text-sm font-bold">
            </div>

            <div class="space-y-2">
                <label class="text-[10px] font-black text-gray-400 uppercase tracking-widest ml-1">Registration Number</label>
                <input type="text" name="registration_number" required placeholder="e.g. 21/U/101/PS" class="w-full px-6 py-4 bg-gray-50 border-2 border-transparent rounded-2xl focus:bg-white focus:border-primary outline-none transition-all text-sm font-bold uppercase">
            </div>

            <div class="space-y-2">
                <label class="text-[10px] font-black text-gray-400 uppercase tracking-widest ml-1">Election Category</label>
                <select name="category_id" required class="w-full px-6 py-4 bg-gray-50 border-2 border-transparent rounded-2xl focus:bg-white focus:border-primary outline-none transition-all text-sm font-bold">
                    @foreach($categories as $cat)
                        <option value="{{ $cat->id }}">{{ $cat->name }}</option>
                    @endforeach
                </select>
            </div>

            <div class="space-y-2">
                <label class="text-[10px] font-black text-gray-400 uppercase tracking-widest ml-1">Faculty</label>
                <select name="faculty" required class="w-full px-6 py-4 bg-gray-50 border-2 border-transparent rounded-2xl focus:bg-white focus:border-primary outline-none transition-all text-sm font-bold">
                    <option value="">Select Faculty</option>
                    <option value="IT">Faculty of Information Technology (IT)</option>
                    <option value="Engineering">Faculty of Engineering</option>
                    <option value="Business">Faculty of Business</option>
                    <option value="Law">Faculty of Law</option>
                    <option value="Social Sciences">Faculty of Social Sciences</option>
                </select>
            </div>

            <button type="submit" class="w-full py-4 bg-primary text-white font-black text-[10px] uppercase tracking-widest rounded-2xl shadow-lg shadow-red-900/20 hover:scale-[1.02] transition-all">
                Vette Candidate
            </button>
        </form>
    </div>

    <!-- Candidate List -->
    <div class="lg:col-span-2 bg-white rounded-[3rem] border border-gray-100 shadow-2xl overflow-hidden">
        <div class="p-10 border-b border-gray-50">
            <h3 class="text-xl font-black text-gray-900 uppercase tracking-tight">Verified Candidate List</h3>
        </div>
        <div class="overflow-x-auto">
            <table class="w-full text-left border-collapse">
                <thead>
                    <tr class="bg-gray-50/50">
                        <th class="px-10 py-6 text-[10px] font-black text-gray-400 uppercase tracking-widest">Candidate</th>
                        <th class="px-10 py-6 text-[10px] font-black text-gray-400 uppercase tracking-widest">Category</th>
                        <th class="px-10 py-6 text-[10px] font-black text-gray-400 uppercase tracking-widest">Reg Num</th>
                        <th class="px-10 py-6 text-[10px] font-black text-gray-400 uppercase tracking-widest text-right">Actions</th>
                    </tr>
                </thead>
                <tbody class="divide-y divide-gray-50">
                    @foreach($candidates as $cand)
                    <tr class="hover:bg-gray-50/30 transition-all">
                        <td class="px-10 py-8">
                            <div class="flex items-center gap-4">
                                <div class="w-12 h-12 rounded-xl overflow-hidden bg-gray-100 flex items-center justify-center text-gray-300 font-black border border-gray-100 shadow-sm relative group">
                                    @if($cand->image_path)
                                        <img src="{{ asset('storage/' . $cand->image_path) }}" alt="{{ $cand->name }}" class="w-full h-full object-cover">
                                    @else
                                        <i class="fas fa-user text-xs"></i>
                                    @endif
                                </div>
                                <div>
                                    <p class="text-sm font-black text-gray-900 uppercase leading-none mb-1">{{ $cand->name }}</p>
                                    <p class="text-[9px] text-gray-400 font-black uppercase tracking-widest">{{ $cand->faculty }}</p>
                                </div>
                            </div>
                        </td>
                        <td class="px-10 py-8 text-xs font-black text-primary uppercase tracking-tight">
                            {{ $cand->category->name }}
                        </td>
                        <td class="px-10 py-8 text-xs font-mono text-gray-500">
                            {{ $cand->registration_number }}
                        </td>
                        <td class="px-10 py-8 text-right">
                            <button class="text-gray-300 hover:text-red-500 transition-colors">
                                <i class="fas fa-trash-can"></i>
                            </button>
                        </td>
                    </tr>
                    @endforeach
                </tbody>
            </table>
        </div>
    </div>
</div>
@endsection
