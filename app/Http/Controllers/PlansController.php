<?php

namespace App\Http\Controllers;

use App\Plan;
use App\Http\Controllers\Auth;
use Illuminate\Http\Request;
use Illuminate\Support\Facades\Auth as FacadesAuth;

class PlansController extends Controller
{
    public function index() {
        $plans = Plan::all();
        return view('plans.index', compact('plans'));
    }

    public function show(Plan $plan) {
        if(\auth()->user()->subscribedToPlan($plan->stripe_plan, $plan->name)) {
            return redirect()->route('home')->with('success', 'You have already subscribed to this plan!');
        }
        $intent = FacadesAuth::user()->createSetupIntent();
        return view('plans.show', compact('plan', 'intent'));
    }

}
