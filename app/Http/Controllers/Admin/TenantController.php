<?php

namespace App\Http\Controllers\Admin;

use App\Http\Controllers\Controller;
use Illuminate\Http\Request;

class TenantController extends Controller
{
    /**
     * Display a listing of the resource.
     */
    public function index(Request $request)
    {
        $status = $request->query('status');
        
        $tenants = \App\Models\Tenant::when($status, function ($query, $status) {
                return $query->where('status', $status);
            })
            ->with(['owner', 'plan']) // Eager load
            ->latest()
            ->paginate(10);
            
        return view('admin.tenants.index', compact('tenants'));
    }

    public function create()
    {
        return view('admin.tenants.create');
    }

    public function store(Request $request)
    {
        $request->validate([
            'name' => 'required|string|max:255',
            'slug' => 'required|string|max:255|unique:tenants,slug',
            'email' => 'required|email|exists:users,email', // Only existing users for now
        ]);
        
        $owner = \App\Models\User::where('email', $request->email)->first();

        $tenant = \App\Models\Tenant::create([
            'user_id' => $owner->id,
            'name' => $request->name,
            'slug' => $request->slug,
            'personal_team' => false,
            'status' => 'active',
        ]);
        
        // Attach user to tenant
        $owner->tenants()->attach($tenant, ['role' => 'admin']);

        return redirect()->route('admin.tenants.index')->with('success', 'Tenant created and assigned to user.');
    }

    public function export()
    {
        $filename = "tenants-" . date('Y-m-d') . ".csv";
        $tenants = \App\Models\Tenant::all();

        $headers = [
            "Content-type"        => "text/csv",
            "Content-Disposition" => "attachment; filename=$filename",
            "Pragma"              => "no-cache",
            "Cache-Control"       => "must-revalidate, post-check=0, pre-check=0",
            "Expires"             => "0"
        ];

        $callback = function() use($tenants) {
            $file = fopen('php://output', 'w');
            fputcsv($file, ['ID', 'Name', 'Slug', 'Status', 'Created At']);

            foreach ($tenants as $tenant) {
                fputcsv($file, [
                    $tenant->id, 
                    $tenant->name, 
                    $tenant->slug, 
                    $tenant->status, 
                    $tenant->created_at
                ]);
            }

            fclose($file);
        };

        return response()->stream($callback, 200, $headers);
    }

    /**
     * Display the specified resource.
     */
    public function show(string $id)
    {
        //
    }

    /**
     * Show the form for editing the specified resource.
     */
    public function edit(string $id)
    {
        $tenant = \App\Models\Tenant::findOrFail($id);
        $plans = \App\Models\Plan::where('is_active', true)->get();
        
        return view('admin.tenants.edit', compact('tenant', 'plans'));
    }

    /**
     * Update the specified resource in storage.
     */
    public function update(Request $request, string $id)
    {
        $tenant = \App\Models\Tenant::findOrFail($id);

        $request->validate([
            'name' => 'required|string|max:255',
            'slug' => 'required|string|max:255|unique:tenants,slug,' . $tenant->id,
            'plan_id' => 'nullable|exists:plans,id',
            'status' => 'required|in:active,pending,suspended',
        ]);

        $tenant->update([
            'name' => $request->name,
            'slug' => $request->slug,
            'plan_id' => $request->plan_id,
            'status' => $request->status,
        ]);

        return redirect()->route('admin.tenants.index')->with('success', 'Organization updated successfully.');
    }

    /**
     * Remove the specified resource from storage.
     */
    public function destroy(string $id)
    {
        //
    }
}
