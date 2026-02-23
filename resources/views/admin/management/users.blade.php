@extends('layouts.admin')

@section('title', 'Staff Management')
@section('header', 'System Access Control')

@section('content')
<div class="grid grid-cols-1 lg:grid-cols-3 gap-12">
    <!-- Account Creation Form -->
    <div class="bg-white p-10 rounded-[3rem] border border-gray-100 shadow-2xl h-fit">
        <h3 class="text-xl font-black text-gray-900 uppercase tracking-tight mb-8">Authorise Staff</h3>
        <form action="{{ route('admin.manage.users.store') }}" method="POST" class="space-y-6">
            @csrf
            <div class="space-y-2">
                <label class="text-[10px] font-black text-gray-400 uppercase tracking-widest ml-1">Staff Name</label>
                <input type="text" name="name" required placeholder="e.g. Supervisor 01" class="w-full px-6 py-4 bg-gray-50 border-2 border-transparent rounded-2xl focus:bg-white focus:border-primary outline-none transition-all text-sm font-bold">
            </div>
            <div class="space-y-2">
                <label class="text-[10px] font-black text-gray-400 uppercase tracking-widest ml-1">Official Email</label>
                <input type="email" name="email" required placeholder="staff@iuea.ac.ug" class="w-full px-6 py-4 bg-gray-50 border-2 border-transparent rounded-2xl focus:bg-white focus:border-primary outline-none transition-all text-sm font-bold">
            </div>
            <div class="space-y-2">
                <label class="text-[10px] font-black text-gray-400 uppercase tracking-widest ml-1">Secure Password</label>
                <input type="password" name="password" required placeholder="••••••••" class="w-full px-6 py-4 bg-gray-50 border-2 border-transparent rounded-2xl focus:bg-white focus:border-primary outline-none transition-all text-sm font-bold">
            </div>
            <div class="space-y-2">
                <label class="text-[10px] font-black text-gray-400 uppercase tracking-widest ml-1">Administrative Role</label>
                <select name="role_id" required class="w-full px-6 py-4 bg-gray-50 border-2 border-transparent rounded-2xl focus:bg-white focus:border-primary outline-none transition-all text-sm font-bold">
                    @foreach($roles as $role)
                        <option value="{{ $role->id }}">{{ $role->display_name }}</option>
                    @endforeach
                </select>
                <p class="text-[9px] text-gray-400 font-medium italic mt-2">* Supervisor role allows kiosk monitoring only.</p>
            </div>
            <button type="submit" class="w-full py-4 bg-primary text-white font-black text-[10px] uppercase tracking-widest rounded-2xl shadow-lg shadow-red-900/20 hover:scale-[1.02] transition-all">
                Grant Access
            </button>
        </form>
    </div>

    <!-- Staff List -->
    <div class="lg:col-span-2 bg-white rounded-[3rem] border border-gray-100 shadow-2xl overflow-hidden">
        <div class="p-10 border-b border-gray-50">
            <h3 class="text-xl font-black text-gray-900 uppercase tracking-tight">Active Administrative Staff</h3>
        </div>
        <div class="overflow-x-auto">
            <table class="w-full text-left border-collapse">
                <thead>
                    <tr class="bg-gray-50/50">
                        <th class="px-10 py-6 text-[10px] font-black text-gray-400 uppercase tracking-widest">Account Holder</th>
                        <th class="px-10 py-6 text-[10px] font-black text-gray-400 uppercase tracking-widest">Tier/Role</th>
                        <th class="px-10 py-6 text-[10px] font-black text-gray-400 uppercase tracking-widest">Status</th>
                        <th class="px-10 py-6 text-[10px] font-black text-gray-400 uppercase tracking-widest">Access Profile</th>
                    </tr>
                </thead>
                <tbody class="divide-y divide-gray-50">
                    @foreach($users as $user)
                    <tr class="hover:bg-gray-50/30 transition-all">
                        <td class="px-10 py-8">
                            <p class="text-sm font-black text-gray-900 uppercase">{{ $user->name }}</p>
                            <p class="text-xs text-gray-400 font-medium">{{ $user->email }}</p>
                        </td>
                        <td class="px-10 py-8">
                            <span class="px-4 py-1 {{ $user->hasRole('super_admin') ? 'bg-red-50 text-primary border-primary/20' : 'bg-blue-50 text-blue-700 border-blue-200' }} rounded-full text-[9px] font-black uppercase tracking-widest border">
                                {{ $user->role->display_name }}
                            </span>
                        </td>
                        <td class="px-10 py-8">
                            <span class="flex items-center gap-2">
                                <span class="w-2 h-2 rounded-full bg-green-500"></span>
                                <span class="text-[10px] font-black text-green-700 uppercase tracking-widest">GRANTED</span>
                            </span>
                        </td>
                        <td class="px-10 py-8 text-xs font-black text-gray-400 uppercase tracking-widest">
                            {!! $user->hasRole('super_admin') ? '<i class="fas fa-key text-yellow-500 mr-2"></i> Master' : '<i class="fas fa-eye text-blue-500 mr-2"></i> Monitor' !!}
                        </td>
                    </tr>
                    @endforeach
                </tbody>
            </table>
        </div>
    </div>
</div>
@endsection
