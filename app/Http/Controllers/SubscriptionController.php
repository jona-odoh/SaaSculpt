<?php

namespace App\Http\Controllers;

use App\Contracts\BillingService;
use App\Models\Plan;
use App\Services\CurrentTenant;
use Illuminate\Http\Request;
use Laravel\Cashier\Exceptions\IncompletePayment;

class SubscriptionController extends Controller
{
    protected $billing;

    public function __construct(BillingService $billing)
    {
        $this->billing = $billing;
    }

    /**
     * Show the checkout/subscription page.
     */
    public function index(Request $request)
    {
        $tenant = app(CurrentTenant::class)->get();
        
        // Fallback or mismatch check handled by middleware/global scope ideally, 
        // but here we ensure we have a tenant context.
        if (!$tenant) {
            return redirect()->route('dashboard')->with('error', 'No tenant context found.');
        }

        $plans = Plan::where('is_active', true)->get();
        $intent = $tenant->createSetupIntent();
        
        return view('subscription.index', compact('tenant', 'plans', 'intent'));
    }

    /**
     * Store a new subscription or Swap.
     */
    public function store(Request $request)
    {
        $request->validate([
            'plan_slug' => 'required|exists:plans,slug',
            'payment_method' => 'required', 
        ]);

        $tenant = app(CurrentTenant::class)->get();
        $plan = Plan::where('slug', $request->input('plan_slug'))->first();

        try {
            // Check if already subscribed
            if ($tenant->subscribed('default')) {
                // If simple swap
                $this->billing->swapPlan($tenant, $plan);
                return redirect()->route('subscription.index')->with('status', 'Plan changed successfully!');
            }

            $this->billing->createSubscription($tenant, $plan, $request->input('payment_method'));
            
            return redirect()->route('subscription.index')->with('status', 'Subscription active!');
        } catch (IncompletePayment $exception) {
            return redirect()->back()->with('error', 'Payment incomplete. Please check your card.');
        } catch (\Exception $e) {
            return redirect()->back()->with('error', 'Error: ' . $e->getMessage());
        }
    }

    /**
     * Cancel the subscription.
     */
    public function destroy(Request $request)
    {
        $tenant = app(CurrentTenant::class)->get();

        try {
            $this->billing->cancelSubscription($tenant);
            return redirect()->route('subscription.index')->with('status', 'Subscription cancelled.');
        } catch (\Exception $e) {
            return redirect()->back()->with('error', 'Error cancelling: ' . $e->getMessage());
        }
    }
}
