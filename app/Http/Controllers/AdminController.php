<?php

namespace App\Http\Controllers;

use Illuminate\Http\Request;
use App\Models\User;
use Illuminate\Support\Facades\Auth;

class AdminController extends Controller
{
    /**
     * Display the admin dashboard.
     */
    public function index()
    {
        // Check if user is admin
        if (!Auth::check() || Auth::user()->role !== 'admin') {
            return redirect()->route('dashboard.index')->with('error', 'Unauthorized access.');
        }

        $totalVoters = \App\Models\User::where('role', 'student')->count();
        $votesCount = \App\Models\Vote::count();
        $turnout = $totalVoters > 0 ? round(($votesCount / $totalVoters) * 100, 1) : 0;
        
        $activeElections = \App\Models\Category::where('status', 'voting')->count();
        
        // Data for Elections Tab
        $categories = \App\Models\Category::withCount('candidates')->get();
        
        // Data for Candidates Tab
        $candidates = \App\Models\Candidate::with('category')->withCount('votes')->get();
        
        // Data for Voters Tab
        $voters = User::where('role', 'student')->latest()->get();

        // Data for Audit/Reports
        $categoriesStats = \App\Models\Category::withCount('votes')->get();
        $recentVotes = \App\Models\Vote::with(['user', 'category', 'candidate'])->latest()->take(10)->get();

        return view('admin.index', compact(
            'totalVoters', 
            'turnout', 
            'votesCount', 
            'activeElections',
            'categories',
            'candidates',
            'voters',
            'categoriesStats',
            'recentVotes'
        ));
    }

    /**
     * Display election management page.
     */
    public function elections()
    {
        if (!Auth::check() || Auth::user()->role !== 'admin') {
            return redirect()->route('dashboard.index')->with('error', 'Unauthorized access.');
        }

        $categories = \App\Models\Category::withCount('candidates')->get();
        return view('admin.elections', compact('categories'));
    }

    /**
     * Display candidate management page.
     */
    public function candidates()
    {
        if (!Auth::check() || Auth::user()->role !== 'admin') {
            return redirect()->route('dashboard.index')->with('error', 'Unauthorized access.');
        }

        $candidates = \App\Models\Candidate::with('category')->withCount('votes')->get();
        $categories = \App\Models\Category::all();
        return view('admin.candidates', compact('candidates', 'categories'));
    }

    /**
     * Nominate a new candidate directly.
     */
    public function storeCandidate(Request $request)
    {
        if (!Auth::check() || Auth::user()->role !== 'admin') {
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
            'faculty' => $request->faculty,
            'student_class' => $request->student_class,
            'biography' => $request->biography,
            'position_number' => $request->position_number,
            'photo_path' => $photoPath,
            'status' => 'approved' // Direct admin nominations are approved by default
        ]);

        return back()->with('success', 'Candidate "' . $request->name . '" has been nominated successfully.');
    }

    /**
     * Display voter records/audit page.
     */
    public function voters()
    {
        if (!Auth::check() || Auth::user()->role !== 'admin') {
            return redirect()->route('dashboard.index')->with('error', 'Unauthorized access.');
        }

        $voters = User::where('role', 'student')->latest()->paginate(20);
        return view('admin.voters', compact('voters'));
    }

    /**
     * Display system settings.
     */
    public function settings()
    {
        if (!Auth::check() || Auth::user()->role !== 'admin') {
            return redirect()->route('dashboard.index')->with('error', 'Unauthorized access.');
        }

        $nominationSetting = \App\Models\SystemSetting::where('key', 'nomination_enabled')->first();
        return view('admin.settings', compact('nominationSetting'));
    }

    public function updateSettings(Request $request)
    {
        if (!Auth::check() || Auth::user()->role !== 'admin') {
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
        if (!Auth::check() || Auth::user()->role !== 'admin') {
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
        if (!Auth::check() || Auth::user()->role !== 'admin') {
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
        if (!Auth::check() || Auth::user()->role !== 'admin') {
            return redirect()->route('dashboard.index')->with('error', 'Unauthorized access.');
        }

        $request->validate([
            'name' => 'required|string|max:255',
            'description' => 'required|string',
        ]);

        \App\Models\Category::create([
            'name' => $request->name,
            'description' => $request->description,
            'status' => 'closed',
            'is_active' => false,
        ]);

        return back()->with('success', 'New election category "' . $request->name . '" created successfully.');
    }
}
