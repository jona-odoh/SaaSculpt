<?php

namespace App\Http\Controllers\Admin;

use App\Http\Controllers\Controller;
use App\Models\User;
use Illuminate\Http\Request;

class UserController extends Controller
{
    /**
     * Display a listing of the resource.
     */
    public function index(Request $request)
    {
        $search = $request->input('search');
        $users = User::when($search, function ($query, $search) {
            return $query->where('name', 'like', "%{$search}%")
                         ->orWhere('email', 'like', "%{$search}%");
        })
        ->latest()
        ->paginate(20);

        return view('admin.users.index', compact('users'));
    }

    /**
     * Display the specified resource.
     */
    public function show(User $user)
    {
        $user->load(['sessions', 'activityLogs', 'ownedTeams', 'teams']);
        return view('admin.users.show', compact('user'));
    }

    /**
     * Ban or Unban the user.
     */
    public function ban(User $user)
    {
        if ($user->id === auth()->id()) {
            return back()->with('error', 'You cannot ban yourself.');
        }

        if ($user->isBanned()) {
            $user->update(['banned_at' => null]);
            \App\Services\ActivityLogger::log('Unbanned User', "Unbanned user {$user->name} ({$user->id})");
            return back()->with('success', 'User unbanned successfully.');
        } else {
            $user->update(['banned_at' => now()]);
            \App\Services\ActivityLogger::log('Banned User', "Banned user {$user->name} ({$user->id})");
            return back()->with('success', 'User banned successfully.');
        }
    }

    /**
     * Impersonate the user.
     */
    public function impersonate(User $user)
    {
        if ($user->id === auth()->id()) {
            return back()->with('error', 'You are already logged in as yourself.');
        }

        // Guard against impersonating other Super Admins if needed, but for now allow it.
        
        session()->put('impersonator_id', auth()->id());
        auth('web')->login($user);

        return redirect()->route('dashboard')->with('success', "You are now impersonating {$user->name}");
    }

    /**
     * Stop impersonating.
     */
    public function stopImpersonating()
    {
        if (session()->has('impersonator_id')) {
            $impersonatorId = session()->pull('impersonator_id');
            auth('web')->loginUsingId($impersonatorId);
            return redirect()->route('admin.users.index')->with('success', 'Welcome back!');
        }

        return redirect()->route('dashboard');
    }
}
