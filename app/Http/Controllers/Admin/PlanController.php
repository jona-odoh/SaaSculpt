<?php

namespace App\Http\Controllers\Admin;

use App\Http\Controllers\Controller;
use App\Models\Plan;
use Illuminate\Http\Request;

class PlanController extends Controller
{
    /**
     * Display a listing of the resource.
     */
    public function index()
    {
        $plans = Plan::all();
        return view('admin.plans.index', compact('plans'));
    }

    /**
     * Show the form for creating a new resource.
     */
    public function create()
    {
        return view('admin.plans.create');
    }

    /**
     * Store a newly created resource in storage.
     */
    public function store(Request $request)
    {
        $data = $request->validate([
            'name' => 'required|string|max:255',
            'slug' => 'required|string|max:255|unique:plans,slug',
            'stripe_price_id' => 'required|string|max:255',
            'paystack_plan_code' => 'nullable|string|max:255',
            'price' => 'required|integer',
            'currency' => 'required|string|max:3',
            'interval' => 'required|in:monthly,yearly',
            'features' => 'nullable|string',
            'is_active' => 'boolean',
        ]);

        if ($request->has('features')) {
            $data['features'] = array_filter(array_map('trim', explode("\n", $request->input('features'))));
        }

        Plan::create($data);

        return redirect()->route('admin.plans.index')->with('success', 'Plan created successfully.');
    }

    /**
     * Show the form for editing the specified resource.
     */
    public function edit(Plan $plan)
    {
        return view('admin.plans.edit', compact('plan'));
    }

    /**
     * Update the specified resource in storage.
     */
    public function update(Request $request, Plan $plan)
    {
        $data = $request->validate([
            'name' => 'required|string|max:255',
            'slug' => 'required|string|max:255|unique:plans,slug,' . $plan->id,
            'stripe_price_id' => 'required|string|max:255',
            'paystack_plan_code' => 'nullable|string|max:255',
            'price' => 'required|integer',
            'currency' => 'required|string|max:3',
            'interval' => 'required|in:monthly,yearly',
            'features' => 'nullable|string',
            'is_active' => 'boolean',
        ]);

        if ($request->has('features')) {
            $data['features'] = array_filter(array_map('trim', explode("\n", $request->input('features'))));
        }

        $plan->update($data);

        return redirect()->route('admin.plans.index')->with('success', 'Plan updated successfully.');
    }

    /**
     * Remove the specified resource from storage.
     */
    public function destroy(Plan $plan)
    {
        $plan->delete();
        return redirect()->route('admin.plans.index')->with('success', 'Plan deleted successfully.');
    }
}
