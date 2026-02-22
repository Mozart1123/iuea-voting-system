@extends('layouts.admin')

@section('title', 'Election Management')
@section('content')
<div class="max-w-7xl mx-auto px-4 sm:px-6 lg:px-8 py-8">
    <div class="flex flex-col md:flex-row md:items-center md:justify-between mb-8">
        <div>
            <h1 class="text-3xl font-bold text-gray-900">Election Management</h1>
            <p class="mt-2 text-gray-600">Control election phases and categories.</p>
        </div>
        <div class="mt-4 md:mt-0">
            <button onclick="document.getElementById('create-election-modal').classList.remove('hidden')" class="bg-primary text-white px-4 py-2 rounded-lg shadow hover:bg-primary-dark transition">
                <i class="fas fa-plus mr-2"></i> Create New Election
            </button>
        </div>
    </div>

    @if(session('success'))
        <div class="mb-6 p-4 bg-green-50 border-l-4 border-green-500 text-green-700">
            {{ session('success') }}
        </div>
    @endif

    <div class="grid grid-cols-1 md:grid-cols-2 lg:grid-cols-3 gap-6">
        @foreach($categories as $category)
            <div class="bg-white rounded-xl shadow-md overflow-hidden border border-gray-100 flex flex-col h-full">
                <div class="p-6 flex-grow">
                    <div class="flex justify-between items-start mb-4">
                        <h3 class="text-xl font-bold text-gray-900">{{ $category->name }}</h3>
                        <span class="px-2 py-1 text-xs font-semibold rounded-full 
                            {{ $category->status === 'voting' ? 'bg-green-100 text-green-800' : 
                               ($category->status === 'nomination' ? 'bg-blue-100 text-blue-800' : 'bg-gray-100 text-gray-800') }}">
                            {{ ucfirst($category->status) }}
                        </span>
                    </div>
                    <p class="text-gray-600 text-sm mb-4">{{ $category->description ?? 'No description provided.' }}</p>
                    
                    <div class="space-y-2 text-sm text-gray-500">
                        <div class="flex items-center justify-between">
                            <span><i class="fas fa-users mr-2"></i> Candidates:</span>
                            <span class="font-medium text-gray-900">{{ $category->candidates_count }}</span>
                        </div>
                        <div class="flex items-center justify-between">
                            <span><i class="fas fa-vote-yea mr-2"></i> Total Votes:</span>
                            <span class="font-medium text-gray-900">{{ $category->votes_count ?? 0 }}</span>
                        </div>
                    </div>
                </div>

                <div class="bg-gray-50 px-6 py-4 border-t border-gray-100">
                    <h4 class="text-xs font-semibold text-gray-500 uppercase tracking-wider mb-3">Change Status</h4>
                    <div class="grid grid-cols-3 gap-2">
                        <!-- Set to Nomination (Open Registration) -->
                        <form action="{{ route('admin.categories.status', $category) }}" method="POST">
                            @csrf
                            <input type="hidden" name="status" value="nomination">
                            <input type="hidden" name="days" value="7"> <!-- Default 7 days -->
                            <button type="submit" class="w-full py-2 px-1 text-xs font-medium rounded border 
                                {{ $category->status === 'nomination' ? 'bg-blue-600 text-white border-blue-600' : 'bg-white text-gray-700 border-gray-300 hover:bg-gray-50' }}">
                                Registration
                            </button>
                        </form>

                        <!-- Set to Voting (Start Election) -->
                        <form action="{{ route('admin.categories.status', $category) }}" method="POST">
                            @csrf
                            <input type="hidden" name="status" value="voting">
                            <input type="hidden" name="days" value="1"> <!-- Default 1 day -->
                            <button type="submit" class="w-full py-2 px-1 text-xs font-medium rounded border 
                                {{ $category->status === 'voting' ? 'bg-green-600 text-white border-green-600' : 'bg-white text-gray-700 border-gray-300 hover:bg-gray-50' }}">
                                Start Voting
                            </button>
                        </form>

                        <!-- Close Election -->
                        <form action="{{ route('admin.categories.status', $category) }}" method="POST">
                            @csrf
                            <input type="hidden" name="status" value="closed">
                            <button type="submit" class="w-full py-2 px-1 text-xs font-medium rounded border 
                                {{ $category->status === 'closed' ? 'bg-red-600 text-white border-red-600' : 'bg-white text-gray-700 border-gray-300 hover:bg-gray-50' }}">
                                Close
                            </button>
                        </form>
                    </div>
                </div>
            </div>
        @endforeach
    </div>
</div>

<!-- Create Election Modal -->
<div id="create-election-modal" class="hidden fixed inset-0 z-50 overflow-y-auto" aria-labelledby="modal-title" role="dialog" aria-modal="true">
    <div class="flex items-end justify-center min-h-screen pt-4 px-4 pb-20 text-center sm:block sm:p-0">
        <div class="fixed inset-0 bg-gray-500 bg-opacity-75 transition-opacity" aria-hidden="true" onclick="document.getElementById('create-election-modal').classList.add('hidden')"></div>
        <span class="hidden sm:inline-block sm:align-middle sm:h-screen" aria-hidden="true">&#8203;</span>
        <div class="inline-block align-bottom bg-white rounded-lg text-left overflow-hidden shadow-xl transform transition-all sm:my-8 sm:align-middle sm:max-w-lg sm:w-full">
            <div class="bg-white px-4 pt-5 pb-4 sm:p-6 sm:pb-4">
                <h3 class="text-lg leading-6 font-medium text-gray-900 mb-4" id="modal-title">Create New Election Category</h3>
                <form action="{{ route('admin.categories.store') }}" method="POST">
                    @csrf
                    <div class="mb-4">
                        <label class="block text-gray-700 text-sm font-bold mb-2" for="name">
                            Category Name
                        </label>
                        <input class="shadow appearance-none border rounded w-full py-2 px-3 text-gray-700 leading-tight focus:outline-none focus:shadow-outline" id="name" name="name" type="text" placeholder="e.g. Guild President" required>
                    </div>
                    <div class="mb-6">
                        <label class="block text-gray-700 text-sm font-bold mb-2" for="description">
                            Description
                        </label>
                        <textarea class="shadow appearance-none border rounded w-full py-2 px-3 text-gray-700 leading-tight focus:outline-none focus:shadow-outline" id="description" name="description" placeholder="Short description of the role..." required></textarea>
                    </div>
                    <div class="flex items-center justify-end">
                        <button type="button" onclick="document.getElementById('create-election-modal').classList.add('hidden')" class="bg-white hover:bg-gray-100 text-gray-800 font-semibold py-2 px-4 border border-gray-400 rounded shadow mr-2">
                            Cancel
                        </button>
                        <button type="submit" class="bg-primary hover:bg-primary-dark text-white font-bold py-2 px-4 rounded focus:outline-none focus:shadow-outline">
                            Create Election
                        </button>
                    </div>
                </form>
            </div>
        </div>
    </div>
</div>
@endsection
