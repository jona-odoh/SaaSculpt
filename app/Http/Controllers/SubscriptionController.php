<?php

namespace App\Http\Controllers;

use App\Models\Plan;
use Illuminate\Http\Request;
use Laravel\Cashier\Exceptions\IncompletePayment;

class SubscriptionController extends Controller
{
    /**
     * Show the checkout/subscription page.
     */
    public function index(Request $request)
    {
        // Typically for a tenant who just registered or is upgrading
        $tenant = $request->user()->currentTeam;
        
        if (!$tenant) {
            return redirect()->route('dashboard')->with('error', 'No tenant found.');
        }

        $plans = Plan::where('is_active', true)->get();
        
        return view('subscription.index', [
            'tenant' => $tenant,
            'plans' => $plans,
            'intent' => $tenant->createSetupIntent(),
        ]);
    }

    /**
     * Store a new subscription.
     */
    public function store(Request $request)
    {
        $request->validate([
            'plan_slug' => 'required|exists:plans,slug',
            'payment_method' => 'required', // Stripe Payment Method ID
        ]);

        $tenant = $request->user()->currentTeam;
        $plan = Plan::where('slug', $request->input('plan_slug'))->first();

        try {
            $tenant->newSubscription('default', $plan->stripe_price_id)
                ->create($request->input('payment_method'));
                
            $tenant->update(['plan_id' => $plan->id]);

            return redirect()->route('dashboard')->with('status', 'Subscription active!');
        } catch (IncompletePayment $exception) {
            return redirect()->back()->with('error', 'Payment incomplete. Please check your card.');
        } catch (\Exception $e) {
            return redirect()->back()->with('error', 'Error: ' . $e->getMessage());
        }
    }
}
