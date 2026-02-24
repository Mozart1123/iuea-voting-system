<?php

namespace App\Http\Controllers;

use App\Models\Category;
use App\Models\Candidate;
use App\Models\Voter;
use App\Models\Vote;
use App\Models\AuditLog;
use Illuminate\Http\Request;
use Illuminate\Support\Facades\Auth;

class AdminController extends Controller
{
    /**
     * Common Dashboard for Admins.
     */
    public function dashboard()
    {
        $user = Auth::user();

        // Check if user is admin or super_admin using the relation/helper
        if (!$user->hasRole(['normal_admin', 'system_admin', 'super_admin'])) {
            return redirect()->route('dashboard.index')->with('error', 'Unauthorized access.');
        }

        $totalVoters = \App\Models\User::whereHas('role', function($q) { $q->where('name', 'student'); })->count();
        $votesCount = \App\Models\Vote::count();
        $turnout = $totalVoters > 0 ? round(($votesCount / $totalVoters) * 100, 1) : 0;
        
        // Dynamic aggregation for Super Admin cards
        $presidentCategory = Category::where('name', 'LIKE', '%President%')->first();
        $facultyCategory = Category::where('name', 'LIKE', '%Representative%')->first();

        $stats = [
            'total_votes' => Voter::count(),
            'president_votes' => $presidentCategory ? $presidentCategory->votes()->count() : 0,
            'faculty_votes' => $facultyCategory ? $facultyCategory->votes()->count() : 0,
            'active_kiosks' => \App\Models\SystemSetting::get('authorized_kiosks', 5),
            'system_status' => 'OPTIMAL',
            'turnout' => Voter::count(),
            'active_positions' => Category::where('is_active', true)->count(),
            'total_candidates' => Candidate::count(),
        ];

        // Fetch category stats for bars
        $categoryStats = Category::where('is_active', true)
            ->withCount('votes')
            ->get();

        $recentActivity = AuditLog::with('user')->latest()->take(10)->get();

        // Return view based on role
        if ($user->hasRole('super_admin')) {
            return view('admin.dashboards.super', compact('stats', 'categoryStats', 'recentActivity'));
        } elseif ($user->hasRole('system_admin')) {
            return view('admin.dashboards.system', compact('stats', 'categoryStats', 'recentActivity'));
        } else {
            return view('admin.dashboards.normal', compact('stats', 'categoryStats', 'recentActivity'));
        }
    }

    /**
     * Live Vote Feed (Read-only for Normal Admins)
     */
    public function activityFeed()
    {
        if (!Auth::check() || !Auth::user()->hasRole(['normal_admin', 'system_admin', 'super_admin'])) {
            return redirect()->route('dashboard.index')->with('error', 'Unauthorized access.');
        }

        $categories = \App\Models\Category::withCount('candidates')->get();
        return view('admin.elections', compact('categories'));
    }

    /**
     * Preview the high-fidelity ballot design with dummy/live data.
     */
    public function ballotPreview()
    {
        if (!Auth::check() || !Auth::user()->hasRole(['normal_admin', 'system_admin', 'super_admin'])) {
            return redirect()->route('dashboard.index')->with('error', 'Unauthorized access.');
        }

        $categories = Category::where('is_active', true)
            ->with('candidates')
            ->get();

        // Simulate session data for design preview
        session()->flash('voter_name', 'DESIGN PREVIEW MODE');
        session()->flash('voter_reg', 'EC/2026/PROTOTYPE');

        return view('voter.ballot', compact('categories'));
    }

    /**
     * Nominate a new candidate directly.
     */
    public function storeCandidate(Request $request)
    {
        if (!Auth::check() || !Auth::user()->hasRole(['normal_admin', 'system_admin', 'super_admin'])) {
            return redirect()->route('dashboard.index')->with('error', 'Unauthorized access.');
        }

        $request->validate([
            'name' => 'required|string|max:255',
            'category_id' => 'required|exists:categories,id',
            'faculty' => 'required|string',
            'student_class' => 'required|string',
            'biography' => 'required|string',
            'position_number' => 'nullable|integer',
            'photo' => 'nullable|image|max:2048',
        ]);

        $photoPath = null;
        if ($request->hasFile('photo')) {
            $photoPath = $request->file('photo')->store('candidates', 'public');
        }

        \App\Models\Candidate::create([
            'category_id' => $request->category_id,
            'name' => $request->name,
            'registration_number' => 'REG-' . strtoupper(\Illuminate\Support\Str::random(6)), // Default if missing
            'faculty' => $request->faculty,
            'student_class' => $request->student_class,
            'biography' => $request->biography,
            'position_number' => $request->position_number,
            'photo_path' => $photoPath,
            'status' => 'approved' // Direct admin nominations are approved by default
        ]);

        // Use User model directly if it has notifyAdmins, or manual notification
        AuditLog::create([
            'user_id' => auth()->id(),
            'action' => 'CREATE_CANDIDATE',
            'description' => "Candidate {$request->name} has been registered by admin.",
            'ip_address' => $request->ip()
        ]);

        return back()->with('success', 'Candidate "' . $request->name . '" has been nominated successfully.');
    }

    /**
     * Display voter records/audit page.
     */
    public function voters()
    {
        if (!Auth::check() || !Auth::user()->hasRole(['normal_admin', 'system_admin', 'super_admin'])) {
            return redirect()->route('dashboard.index')->with('error', 'Unauthorized access.');
        }

        $voters = User::whereHas('role', function($q) { $q->where('name', 'student'); })->latest()->paginate(20);
        return view('admin.voters', compact('voters'));
    }

    /**
     * Display system settings.
     */
    public function settings()
    {
        if (!Auth::check() || !Auth::user()->hasRole(['normal_admin', 'system_admin', 'super_admin'])) {
            return redirect()->route('dashboard.index')->with('error', 'Unauthorized access.');
        }

        $settings = \App\Models\SystemSetting::pluck('value', 'key')->toArray();
        return view('admin.settings', compact('settings'));
    }

    public function updateSettings(Request $request)
    {
        if (!Auth::check() || !Auth::user()->hasRole(['normal_admin', 'system_admin', 'super_admin'])) {
            return redirect()->route('dashboard.index')->with('error', 'Unauthorized access.');
        }

        $request->validate([
            'settings' => 'required|array',
        ]);

        foreach ($request->settings as $key => $value) {
            \App\Models\SystemSetting::updateOrCreate(
                ['key' => $key],
                ['value' => $value]
            );
        }

        return back()->with('success', 'System settings synchronized successfully.');
    }

    /**
     * Update category status and election timing.
     */
    public function updateCategoryStatus(Request $request, \App\Models\Category $category)
    {
        if (!Auth::check() || !Auth::user()->hasRole(['normal_admin', 'system_admin', 'super_admin'])) {
            return response()->json(['error' => 'Unauthorized'], 403);
        }

        $request->validate([
            'status' => 'required|in:nomination,voting,closed',
            'days' => 'nullable|integer|min:1|max:30',
        ]);

        $updateData = ['status' => $request->status];

        if ($request->status === 'voting' && $request->days) {
            $updateData['start_time'] = now();
            $updateData['end_time'] = now()->addDays($request->days);
            $updateData['is_active'] = true;
        } elseif ($request->status === 'nomination' && $request->days) {
            $updateData['start_time'] = now();
            $updateData['end_time'] = now()->addDays($request->days);
            $updateData['is_active'] = true;
        } elseif ($request->status === 'closed') {
            $updateData['is_active'] = false;
        }

        $category->update($updateData);

        return back()->with('success', 'Election status for ' . $category->name . ' updated to ' . $request->status);
    }

    /**
     * Approve or reject a candidate.
     */
    public function updateCandidateStatus(Request $request, \App\Models\Candidate $candidate)
    {
        if (!Auth::check() || !Auth::user()->hasRole(['normal_admin', 'system_admin', 'super_admin'])) {
            return response()->json(['error' => 'Unauthorized'], 403);
        }

        $request->validate([
            'status' => 'required|in:approved,rejected',
        ]);

        $candidate->update(['status' => $request->status]);

        return back()->with('success', 'Candidate ' . $candidate->name . ' has been ' . $request->status);
    }

    /**
     * Create a new election category.
     */
    public function storeCategory(Request $request)
    {
        if (!Auth::check() || !Auth::user()->hasRole(['normal_admin', 'system_admin', 'super_admin'])) {
            return redirect()->route('dashboard.index')->with('error', 'Unauthorized access.');
        }

        $request->validate([
            'name' => 'required|string|max:255',
            'description' => 'required|string',
            'faculty_restriction' => 'nullable|string',
        ]);

        \App\Models\Category::create([
            'name' => $request->name,
            'description' => $request->description,
            'faculty_restriction' => $request->faculty_restriction,
            'status' => 'closed',
            'is_active' => false,
        ]);

        return back()->with('success', 'New election category "' . $request->name . '" created successfully.');
    }

    /**
     * Get all notifications for the authenticated admin.
     */
    public function getNotifications()
    {
        $notifications = Auth::user()->notifications()->latest()->take(20)->get();
        $unreadCount = Auth::user()->unreadNotifications->count();
        
        return response()->json([
            'notifications' => $notifications,
            'unread_count' => $unreadCount
        ]);
    }

    /**
     * Mark all notifications as read.
     */
    public function markNotificationsRead()
    {
        Auth::user()->unreadNotifications->markAsRead();
        return response()->json(['success' => true]);
    }

    /**
     * Finalize the entire vote globally.
     */
    public function finalizeVote(Request $request)
    {
        if (!Auth::check() || !in_array(Auth::user()->role, ['admin', 'super_admin'])) {
            return response()->json(['error' => 'Unauthorized'], 403);
        }

        \App\Models\SystemSetting::updateOrCreate(
            ['key' => 'election_ended'],
            ['value' => '1']
        );

        // Also close all active categories
        \App\Models\Category::where('status', '!=', 'closed')->update([
            'status' => 'closed',
            'is_active' => false,
            'end_time' => now()
        ]);

        User::notifyAdmins([
            'title' => 'Election Finalized',
            'message' => "The election has been officially closed by " . Auth::user()->name . ". Results are now final.",
            'icon' => 'fas fa-flag-checkered',
            'type' => 'success'
        ]);

        return back()->with('success', 'The election has been successfully finalized.');
    }

    /**
     * Display the winners and certificates page.
     */
    public function winners()
    {
        if (!Auth::check() || !in_array(Auth::user()->role, ['admin', 'super_admin'])) {
            return redirect()->route('dashboard.index')->with('error', 'Unauthorized access.');
        }

        $isEnded = (\App\Models\SystemSetting::where('key', 'election_ended')->first()->value ?? '0') == '1';
        
        $winners = [];
        if ($isEnded) {
            $categories = \App\Models\Category::with(['candidates' => function($q) {
                $q->withCount('votes');
            }])->get();

            foreach ($categories as $category) {
                $winner = $category->candidates->sortByDesc(function($candidate) {
                    return $candidate->total_votes;
                })->first();
                
                if ($winner) {
                    $winners[] = [
                        'category' => $category,
                        'winner' => $winner,
                        'total_votes' => $winner->total_votes
                    ];
                }
            }
        }

        return view('admin.winners', compact('winners', 'isEnded'));
    }
}
