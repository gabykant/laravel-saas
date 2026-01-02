<?php

namespace App\Http\Controllers\Api;

use App\Http\Controllers\Controller;
use Illuminate\Http\Request;
use App\Models\Plan;

class BillingController extends Controller
{
    public function subscribe(Request $request)
    {
        $request->validate([
            'payment_method' => 'required|string',
            'plan'           => 'required|string', // ex: price_xxx
        ]);

        //$tenant = tenant(); // Get the current tenant
        $tenant = app('tenant');

        if (!$tenant) {
            return response()->json(['message' => 'Tenant not found'], 404);
        }

        // Create stripe client if necessary
        if (! $tenant->stripe_id) {
            $tenant->createAsStripeCustomer();
        }

        // Attach the payment method
        $tenant->updateDefaultPaymentMethod($request->payment_method);

        // Create the client subscription
        $tenant->newSubscription('default', $request->plan)
                ->trialDays(14)
                ->create($request->payment_method);

        return response()->json([
            'message' => 'Subscription created successfully',
            'tenant'  => $tenant,
        ]);
    }

    public function changePlan(Request $request)
    {
        $request->validate([
            'plan_id' => 'required|exists:plans,id',
        ]);

        $newPlan = Plan::findOrFail($request->plan_id);
        $priceId = $newPlan->stripe_price_id;

        // In your controller handling the change-plan request
try {
    // Log or debug the price ID you're using
    \Log::info('Attempting to change to price: ' . $priceId);
    
    // Try to retrieve the price directly to verify it exists
    $stripe = new \Stripe\StripeClient(config('services.stripe.secret'));
    $price = $stripe->prices->retrieve($priceId);
    \Log::info('Price retrieved successfully', ['price' => $price->id]);
    
    $user = tenant();
    // Proceed with your change plan logic
    $user->subscription('default')->swap($price);
} catch (\Exception $e) {
    \Log::error('Error changing plan: ' . $e->getMessage());
    // Handle the error appropriately
    return back()->withErrors(['message' => 'Unable to change plan: ' . $e->getMessage()]);
}

        $tenant = tenant();
        $newPlan = Plan::findOrFail($request->plan_id);

        // Check if a subscription exists already
        if (! $tenant->subscribed('default')) {
            return response()->json(['message' => 'No active subscription'], 403);
        }

        // Change Stripe Plan
        $tenant->subscription('default')->swapAndInvoice($newPlan->stripe_price_id);

        return response()->json([
            'message' => 'Plan changed successfully',
            'subscription' => $tenant->subscription('default'),
        ]);
    }

    public function invoices()
    {
        $tenant = tenant();

        // Get Stripe Invoices
        $invoices = $tenant->invoices();

        return response()->json([
            'invoices' => $invoices,
        ]);
    }

    public function cancel()
    {
        $tenant = tenant();

        if (! $tenant->subscribed('default')) {
            return response()->json(['message' => 'No active subscription'], 403);
        }

        $tenant->subscription('default')->cancel();

        return response()->json([
            'message' => 'Subscription canceled successfully',
        ]);
    }
}
