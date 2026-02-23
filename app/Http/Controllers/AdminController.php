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
        $votes = Voter::latest()->paginate(20);
        return view('admin.activity-feed', compact('votes'));
    }

    /**
     * Preview the high-fidelity ballot design with dummy/live data.
     */
    public function ballotPreview()
    {
        $categories = Category::where('is_active', true)
            ->with('candidates')
            ->get();

        // Simulate session data for design preview
        session()->flash('voter_name', 'DESIGN PREVIEW MODE');
        session()->flash('voter_reg', 'EC/2026/PROTOTYPE');

        return view('voter.ballot', compact('categories'));
    }
}
