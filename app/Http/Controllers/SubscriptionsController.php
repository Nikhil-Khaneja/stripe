<?php

namespace App\Http\Controllers;

use App\Plan;
use Illuminate\Support\Facades\Auth;
use Illuminate\Http\Request;


class SubscriptionsController extends Controller
{
    public function create(Request $request) {

        $plan = Plan::findOrFail($request->plan);

        
        if(\auth()->user()->subscribedToPlan($plan->stripe_plan, $plan->name)) {
            return redirect()->route('home')->with('success', 'You have already subscribed to this plan!');
        } 

        $user = $request->user();

        $paymentMethod = $request->paymentMethod;

        $user->createOrGetStripeCustomer();
        $user->updateDefaultPaymentMethod($paymentMethod);

        $user
            ->newSubscription($plan->name, $plan->stripe_plan)
            ->create($paymentMethod, [
                'email'=>$user->email
            ]);
           
        session()->flash('success', 'Your plan subscribed successfully!');
        return redirect()->route('home');
    }
}
