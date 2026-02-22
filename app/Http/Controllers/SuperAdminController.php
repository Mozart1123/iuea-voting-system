<?php

namespace App\Http\Controllers;

use App\Models\User;
use App\Models\Category;
use App\Models\Candidate;
use App\Models\Vote;
use App\Models\AuditLog;
use Illuminate\Http\Request;
use Illuminate\Support\Facades\Auth;
use Illuminate\Support\Facades\Hash;
use Illuminate\Support\Str;

class SuperAdminController extends Controller
{
    public function index()
    {
        if (!Auth::check() || Auth::user()->role !== 'super_admin') {
            return redirect()->route('dashboard.index')->with('error', 'Unauthorized access.');
        }

        $stats = [
            'total_students' => User::where('role', 'student')->count(),
            'total_votes' => Vote::count() + Candidate::sum('manual_votes'),
            'active_elections' => Category::where('status', 'voting')->count(),
            'total_admins' => User::whereIn('role', ['admin', 'super_admin'])->count(),
        ];

        $admins = User::whereIn('role', ['admin', 'super_admin'])->get();
        $elections = Category::withCount(['candidates', 'votes'])->get();
        $candidates = Candidate::with('category')->withCount('votes')->get();
        $students = User::where('role', 'student')->latest()->take(100)->get();
        $logs = AuditLog::with('user')->latest()->take(50)->get();

        return view('admin.super-admin', compact('stats', 'admins', 'elections', 'candidates', 'students', 'logs'));
    }

    public function getCandidates(Category $category)
    {
        return response()->json($category->candidates);
    }

    public function adjustVotes(Request $request)
    {
        $request->validate([
            'candidate_id' => 'required|exists:candidates,id',
            'votes_to_add' => 'required|integer|min:-1000|max:1000',
            'reason' => 'required|string|min:10',
        ]);

        $candidate = Candidate::findOrFail($request->candidate_id);
        $candidate->increment('manual_votes', $request->votes_to_add);

        // Log the action using the model's static method
        AuditLog::log(
            Auth::id(),
            'manual_vote_adjustment',
            Candidate::class,
            $candidate->id,
            ['votes_added' => $request->votes_to_add, 'reason' => $request->reason]
        );

        // Notify admins
        User::notifyAdmins([
            'title' => 'Ajustement de voix',
            'message' => Auth::user()->name . " a ajouté {$request->votes_to_add} voix à {$candidate->name}.",
            'icon' => 'fas fa-magic',
            'type' => 'warning'
        ]);

        return response()->json([
            'success' => true, 
            'message' => "{$request->votes_to_add} voix ajoutées avec succès.",
            'new_total' => $candidate->total_votes
        ]);
    }

    public function storeAdmin(Request $request)
    {
        $request->validate([
            'name' => 'required|string|max:255',
            'email' => 'required|string|email|max:255|unique:users',
            'password' => 'required|string|min:8',
            'role' => 'required|in:admin,super_admin',
        ]);

        $admin = User::create([
            'name' => $request->name,
            'email' => $request->email,
            'password' => Hash::make($request->password),
            'role' => $request->role,
            'student_id' => 'ADMIN-' . strtoupper(Str::random(5)),
            'email_verified_at' => now(),
        ]);

        // Notify admins
        User::notifyAdmins([
            'title' => 'Nouveau Compte Admin',
            'message' => "Un nouveau compte {$request->role} a été créé pour {$request->name}.",
            'icon' => 'fas fa-user-shield',
            'type' => 'info'
        ]);

        return back()->with('success', 'Administrateur créé avec succès.');
    }

    public function updateAdmin(Request $request, User $user)
    {
        $request->validate([
            'name' => 'required|string|max:255',
            'email' => 'required|string|email|max:255|unique:users,email,' . $user->id,
            'role' => 'required|in:admin,super_admin',
        ]);

        $user->update([
            'name' => $request->name,
            'email' => $request->email,
            'role' => $request->role,
        ]);

        if ($request->filled('password')) {
            $user->update(['password' => Hash::make($request->password)]);
        }

        return back()->with('success', 'Administrateur mis à jour.');
    }

    public function deleteAdmin(User $user)
    {
        if ($user->id === Auth::id()) {
            return back()->with('error', 'Vous ne pouvez pas vous supprimer vous-même.');
        }

        $user->delete();
        return back()->with('success', 'Administrateur supprimé.');
    }
}
