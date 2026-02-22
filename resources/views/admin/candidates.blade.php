@extends('layouts.admin')

@section('title', 'Candidate Management')
@section('content')
<div class="max-w-7xl mx-auto px-4 sm:px-6 lg:px-8 py-8">
    <div class="flex flex-col md:flex-row md:items-center md:justify-between mb-8">
        <div>
            <h1 class="text-3xl font-bold text-gray-900">Manage Candidates</h1>
            <p class="mt-2 text-gray-600">Review and approve candidate applications.</p>
        </div>
        <div class="mt-4 md:mt-0">
             <button onclick="document.getElementById('add-candidate-modal').classList.remove('hidden')" class="bg-primary text-white px-4 py-2 rounded-lg shadow hover:bg-primary-dark transition">
                <i class="fas fa-plus mr-2"></i> Add Candidate
            </button>
        </div>
    </div>

    @if(session('success'))
        <div class="mb-6 p-4 bg-green-50 border-l-4 border-green-500 text-green-700">
            {{ session('success') }}
        </div>
    @endif

    <!-- Pending Applications Section -->
    @php
        $pendingCandidates = $candidates->where('status', 'pending');
    @endphp

    @if($pendingCandidates->count() > 0)
        <div class="mb-10">
            <h2 class="text-xl font-bold text-gray-800 mb-4 flex items-center">
                <i class="fas fa-clock text-yellow-500 mr-2"></i> Pending Approvals
                <span class="ml-2 bg-yellow-100 text-yellow-800 text-xs font-semibold px-2.5 py-0.5 rounded-full">{{ $pendingCandidates->count() }}</span>
            </h2>
            <div class="bg-white shadow-lg rounded-xl overflow-hidden border border-yellow-200">
                <table class="min-w-full divide-y divide-gray-200">
                    <thead class="bg-gray-50">
                        <tr>
                            <th class="px-6 py-3 text-left text-xs font-medium text-gray-500 uppercase tracking-wider">Candidate</th>
                            <th class="px-6 py-3 text-left text-xs font-medium text-gray-500 uppercase tracking-wider">Position</th>
                            <th class="px-6 py-3 text-left text-xs font-medium text-gray-500 uppercase tracking-wider">Details</th>
                            <th class="px-6 py-3 text-right text-xs font-medium text-gray-500 uppercase tracking-wider">Actions</th>
                        </tr>
                    </thead>
                    <tbody class="bg-white divide-y divide-gray-200">
                        @foreach($pendingCandidates as $candidate)
                            <tr>
                                <td class="px-6 py-4 whitespace-nowrap">
                                    <div class="flex items-center">
                                        <div class="flex-shrink-0 h-10 w-10">
                                            @if($candidate->photo_path)
                                                <img class="h-10 w-10 rounded-full object-cover" src="{{ asset('storage/'.$candidate->photo_path) }}" alt="">
                                            @else
                                                <div class="h-10 w-10 rounded-full bg-gray-200 flex items-center justify-center text-gray-400">
                                                    <i class="fas fa-user"></i>
                                                </div>
                                            @endif
                                        </div>
                                        <div class="ml-4">
                                            <div class="text-sm font-medium text-gray-900">{{ $candidate->name }}</div>
                                            <div class="text-sm text-gray-500">{{ $candidate->student_class }}</div>
                                        </div>
                                    </div>
                                </td>
                                <td class="px-6 py-4 whitespace-nowrap">
                                    <span class="px-2 inline-flex text-xs leading-5 font-semibold rounded-full bg-blue-100 text-blue-800">
                                        {{ $candidate->category->name }}
                                    </span>
                                </td>
                                <td class="px-6 py-4">
                                    <div class="text-sm text-gray-900 truncate max-w-xs" title="{{ $candidate->biography }}">
                                        {{ Str::limit($candidate->biography, 50) }}
                                    </div>
                                    <div class="text-xs text-gray-500 mt-1">{{ $candidate->faculty }}</div>
                                </td>
                                <td class="px-6 py-4 whitespace-nowrap text-right text-sm font-medium space-x-2">
                                    <form action="{{ route('admin.candidates.status', $candidate) }}" method="POST" class="inline">
                                        @csrf
                                        <input type="hidden" name="status" value="approved">
                                        <button type="submit" class="text-green-600 hover:text-green-900 bg-green-50 px-3 py-1 rounded-md hover:bg-green-100 transition">
                                            <i class="fas fa-check mr-1"></i> Approve
                                        </button>
                                    </form>
                                    <form action="{{ route('admin.candidates.status', $candidate) }}" method="POST" class="inline">
                                        @csrf
                                        <input type="hidden" name="status" value="rejected">
                                        <button type="submit" class="text-red-600 hover:text-red-900 bg-red-50 px-3 py-1 rounded-md hover:bg-red-100 transition">
                                            <i class="fas fa-times mr-1"></i> Reject
                                        </button>
                                    </form>
                                </td>
                            </tr>
                        @endforeach
                    </tbody>
                </table>
            </div>
        </div>
    @endif

    <!-- Approved Candidates Listing -->
    <div class="bg-white shadow rounded-lg overflow-hidden">
        <div class="px-6 py-4 border-b border-gray-200 flex justify-between items-center bg-gray-50">
            <h3 class="text-lg font-medium text-gray-900">Approved Candidates</h3>
            <div class="text-sm text-gray-500">{{ $candidates->where('status', 'approved')->count() }} total</div>
        </div>
        <ul role="list" class="divide-y divide-gray-200">
            @forelse($candidates->where('status', 'approved') as $candidate)
                <li class="px-6 py-4 hover:bg-gray-50">
                    <div class="flex items-center justify-between">
                        <div class="flex items-center">
                            <div class="flex-shrink-0 h-10 w-10">
                                @if($candidate->photo_path)
                                    <img class="h-10 w-10 rounded-full object-cover" src="{{ asset('storage/'.$candidate->photo_path) }}" alt="">
                                @else
                                    <div class="h-10 w-10 rounded-full bg-gray-200 flex items-center justify-center text-gray-400">
                                        <i class="fas fa-user"></i>
                                    </div>
                                @endif
                            </div>
                            <div class="ml-4">
                                <p class="text-sm font-medium text-gray-900">{{ $candidate->name }}</p>
                                <p class="text-sm text-gray-500">{{ $candidate->category->name ?? 'Unassigned' }}</p>
                            </div>
                        </div>
                        <div class="flex items-center space-x-4">
                            <span class="px-2 inline-flex text-xs leading-5 font-semibold rounded-full bg-green-100 text-green-800">
                                Active
                            </span>
                            <div class="text-sm text-gray-500">
                                {{ $candidate->votes_count }} votes
                            </div>
                             <form action="{{ route('admin.candidates.status', $candidate) }}" method="POST" class="inline" onsubmit="return confirm('Are you sure you want to deactivate/reject this candidate?');">
                                @csrf
                                <input type="hidden" name="status" value="rejected">
                                <button type="submit" class="text-red-400 hover:text-red-600">
                                    <i class="fas fa-ban"></i>
                                </button>
                            </form>
                        </div>
                    </div>
                </li>
            @empty
                <li class="px-6 py-12 text-center text-gray-500">
                    No approved candidates yet. approvals pending or create manual candidates.
                </li>
            @endforelse
        </ul>
    </div>
</div>

<!-- Add Candidate Modal (Simplified) -->
<div id="add-candidate-modal" class="hidden fixed inset-0 z-50 overflow-y-auto" aria-labelledby="modal-title" role="dialog" aria-modal="true">
    <div class="flex items-end justify-center min-h-screen pt-4 px-4 pb-20 text-center sm:block sm:p-0">
        <div onclick="document.getElementById('add-candidate-modal').classList.add('hidden')" class="fixed inset-0 bg-gray-500 bg-opacity-75 transition-opacity" aria-hidden="true"></div>
        <span class="hidden sm:inline-block sm:align-middle sm:h-screen" aria-hidden="true">&#8203;</span>
        <div class="inline-block align-bottom bg-white rounded-lg text-left overflow-hidden shadow-xl transform transition-all sm:my-8 sm:align-middle sm:max-w-lg sm:w-full">
            <div class="bg-white px-4 pt-5 pb-4 sm:p-6 sm:pb-4">
                <h3 class="text-lg leading-6 font-medium text-gray-900 mb-4" id="modal-title">Add New Candidate</h3>
                <form action="{{ route('admin.candidates.store') }}" method="POST" enctype="multipart/form-data">
                    @csrf
                    <div class="grid grid-cols-1 gap-4">
                        <input type="text" name="name" placeholder="Candidate Name" class="w-full rounded border-gray-300" required>
                        <select name="category_id" class="w-full rounded border-gray-300" required>
                            @foreach($categories as $cat)
                                <option value="{{ $cat->id }}">{{ $cat->name }}</option>
                            @endforeach
                        </select>
                        <input type="text" name="faculty" placeholder="Faculty" class="w-full rounded border-gray-300" required>
                        <input type="text" name="student_class" placeholder="Year/Class" class="w-full rounded border-gray-300" required>
                        <textarea name="biography" placeholder="Biography" class="w-full rounded border-gray-300" required></textarea>
                        <input type="file" name="photo" class="w-full text-sm text-gray-500">
                    </div>
                    <div class="bg-gray-50 px-4 py-3 sm:px-6 sm:flex sm:flex-row-reverse mt-6 -mx-6 -mb-4">
                        <button type="submit" class="w-full inline-flex justify-center rounded-md border border-transparent shadow-sm px-4 py-2 bg-primary text-base font-medium text-white hover:bg-primary-dark sm:ml-3 sm:w-auto sm:text-sm">
                            Add Candidate
                        </button>
                        <button type="button" onclick="document.getElementById('add-candidate-modal').classList.add('hidden')" class="mt-3 w-full inline-flex justify-center rounded-md border border-gray-300 shadow-sm px-4 py-2 bg-white text-base font-medium text-gray-700 hover:bg-gray-50 sm:mt-0 sm:ml-3 sm:w-auto sm:text-sm">
                            Cancel
                        </button>
                    </div>
                </form>
            </div>
        </div>
    </div>
</div>
@endsection
