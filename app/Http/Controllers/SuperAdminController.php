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

    public function storeCategory(Request $request)
    {
        $request->validate([
            'name' => 'required|string|max:255',
            'starts_at' => 'nullable|date',
            'ends_at' => 'nullable|date|after:starts_at',
        ]);
        
        Category::create($request->all());
        
        AuditLog::create([
            'user_id' => auth()->id(),
            'action' => 'CREATE_CATEGORY',
            'description' => "Created election category: {$request->name}",
            'ip_address' => $request->ip()
        ]);

        return back()->with('success', 'Category created successfully.');
    }

    /**
     * Manage Candidates.
     */
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
            'description' => "Registered candidate: {$request->name} for category {$request->category_id} (Faculty: {$request->faculty})",
            'ip_address' => $request->ip()
        ]);

        return back()->with('success', 'Candidate registered successfully.');
    }

    /**
     * Manage Users (System Admins & Normal Admins).
     */
    public function users()
    {
        $users = User::with('role')->whereNot('id', auth()->id())->get();
        $roles = Role::whereNot('name', 'super_admin')->get();
        return view('admin.management.users', compact('users', 'roles'));
    }

    public function storeUser(Request $request)
    {
        $request->validate([
            'name' => 'required|string',
            'email' => 'required|email|unique:users',
            'password' => 'required|min:8',
            'role_id' => 'required|exists:roles,id',
        ]);

        User::create([
            'name' => $request->name,
            'email' => $request->email,
            'password' => Hash::make($request->password),
            'role_id' => $request->role_id,
        ]);

        AuditLog::create([
            'user_id' => auth()->id(),
            'action' => 'CREATE_ADMIN',
            'description' => "Created new admin/supervisor: {$request->email}",
            'ip_address' => $request->ip()
        ]);

        return back()->with('success', 'Admin user created successfully.');
    }

    /**
     * View Audit Logs.
     */
    public function auditLogs()
    {
        $logs = AuditLog::with('user')->latest()->paginate(50);
        return view('admin.management.audit', compact('logs'));
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
            \App\Models\SystemSetting::set($key, $value);
        }

        AuditLog::create([
            'user_id' => auth()->id(),
            'action' => 'UPDATE_SETTINGS',
            'description' => "Updated global system configurations.",
            'ip_address' => $request->ip()
        ]);

        return back()->with('success', 'System settings updated successfully.');
    }
}
