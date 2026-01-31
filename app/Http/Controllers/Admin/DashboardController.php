<?php

namespace App\Http\Controllers\Admin;

use App\Http\Controllers\Controller;
use App\Models\Tenant;
use Illuminate\Http\Request;
use Illuminate\Support\Facades\DB;

class DashboardController extends Controller
{
    public function index()
    {
        // 1. Total Tenants
        $totalTenants = Tenant::count();

        // 2. Growth (New Tenants per Month for the last 12 months)
        $growthData = Tenant::select(
            DB::raw('DATE_FORMAT(created_at, "%Y-%m") as month'),
            DB::raw('count(*) as count')
        )
        ->groupBy('month')
        ->orderBy('month', 'asc')
        ->limit(12)
        ->get();

        $growthLabels = $growthData->pluck('month');
        $growthValues = $growthData->pluck('count');

        // 3. Retention (Active vs Suspended vs Pending)
        $statusDistribution = Tenant::select('status', DB::raw('count(*) as count'))
            ->groupBy('status')
            ->get();
        
        $statusLabels = $statusDistribution->pluck('status')->map(function($status) {
            return ucfirst($status);
        });
        $statusValues = $statusDistribution->pluck('count');

        // 4. Usage (Top 5 Tenants by User count)
        // Note: usage of 'withCount' requires the relationship 'users' to be defined on Tenant model
        $topTenants = Tenant::withCount('users')
            ->orderByDesc('users_count')
            ->take(5)
            ->get();

        return view('admin.dashboard', compact(
            'totalTenants',
            'growthLabels',
            'growthValues',
            'statusLabels',
            'statusValues',
            'topTenants'
        ));
    }
}
