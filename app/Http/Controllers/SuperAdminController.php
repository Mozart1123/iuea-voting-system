<?php

namespace App\Http\Controllers;

use App\Models\Category;
use App\Models\Candidate;
use App\Models\User;
use App\Models\Role;
use App\Models\AuditLog;
use Illuminate\Http\Request;
use Illuminate\Support\Facades\Hash;

class SuperAdminController extends Controller
{
    /**
     * Manage Election Categories.
     */
    public function categories()
    {
        $categories = Category::withCount('candidates')->get();
        return view('admin.management.categories', compact('categories'));
    }

    public function storeUser(Request $request)
    {
        $request->validate([
            'name' => 'required|string|max:255',
            'email' => 'required|string|email|max:255|unique:users',
            'password' => 'required|string|min:8',
            'role_id' => 'required|exists:roles,id',
            'profile_photo' => 'nullable|image|max:2048',
        ]);

        $photoPath = null;
        if ($request->hasFile('profile_photo')) {
            $photoPath = $request->file('profile_photo')->store('profiles', 'public');
        }

        $user = User::create([
            'name' => $request->name,
            'email' => $request->email,
            'password' => Hash::make($request->password),
            'role_id' => $request->role_id,
            'profile_photo' => $photoPath,
            'email_verified_at' => now(),
        ]);

        AuditLog::create([
            'user_id' => auth()->id(),
            'action' => 'CREATE_ADMIN',
            'description' => "Created new admin/supervisor: {$request->email}",
            'ip_address' => $request->ip()
        ]);

        return back()->with('success', 'Administrator created successfully.');
    }

    public function candidates()
    {
        $candidates = Candidate::with('category')->get();
        $categories = Category::all();
        return view('admin.management.candidates', compact('candidates', 'categories'));
    }

    public function storeCandidate(Request $request)
    {
        $request->validate([
            'name' => 'required|string',
            'registration_number' => 'required|string|unique:candidates',
            'category_id' => 'required|exists:categories,id',
            'faculty' => 'required|string',
            'photo' => 'nullable|image|mimes:jpeg,png,jpg|max:2048',
        ]);

        $data = $request->all();

        if ($request->hasFile('photo')) {
            $path = $request->file('photo')->store('candidates', 'public');
            $data['image_path'] = $path;
        }

        Candidate::create($data);

        AuditLog::create([
            'user_id' => auth()->id(),
            'action' => 'CREATE_CANDIDATE',
            'description' => "Registered candidate: {$request->name} for category {$request->category_id}",
            'ip_address' => $request->ip()
        ]);

        return back()->with('success', 'Candidate registered successfully.');
    }

    /**
     * View Audit Logs.
     */
    public function auditLogs()
    {
        $logs = AuditLog::with('user')->latest()->paginate(50);
        return view('admin.management.audit', compact('logs'));
    }

    public function updateUser(Request $request, User $user)
    {
        $request->validate([
            'name' => 'required|string|max:255',
            'email' => 'required|string|email|max:255|unique:users,email,' . $user->id,
            'role_id' => 'required|exists:roles,id',
            'profile_photo' => 'nullable|image|max:2048',
        ]);

        if ($request->hasFile('profile_photo')) {
            // Delete old photo if exists
            if ($user->profile_photo && \Storage::disk('public')->exists($user->profile_photo)) {
                \Storage::disk('public')->delete($user->profile_photo);
            }
            $user->profile_photo = $request->file('profile_photo')->store('profiles', 'public');
        }

        $user->update([
            'name' => $request->name,
            'email' => $request->email,
            'role_id' => $request->role_id,
        ]);

        if ($request->filled('password')) {
            $user->update(['password' => Hash::make($request->password)]);
        }

        return back()->with('success', 'Administrator updated.');
    }

    /**
     * System Settings.
     */
    public function settings()
    {
        $settings = \App\Models\SystemSetting::all();
        return view('admin.management.settings', compact('settings'));
    }

    public function updateSettings(Request $request)
    {
        // Simple key-value update
        foreach ($request->except('_token') as $key => $value) {
            \App\Models\SystemSetting::updateOrCreate(['key' => $key], ['value' => $value]);
        }

        AuditLog::create([
            'user_id' => auth()->id(),
            'action' => 'UPDATE_SETTINGS',
            'description' => "Updated global system configurations.",
            'ip_address' => $request->ip()
        ]);

        return back()->with('success', 'System settings updated successfully.');
    }

    public function deleteUser(User $user)
    {
        if ($user->id === Auth::id()) {
            return back()->with('error', 'You cannot delete yourself.');
        }

        $user->delete();
        return back()->with('success', 'Administrator deleted.');
    }
}
