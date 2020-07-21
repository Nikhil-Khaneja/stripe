<?php

namespace App\Http\Controllers;

use App\Plan;
use Illuminate\Http\Request;

class PlansController extends Controller
{
    public function index() {
        $plans = Plan::all();
        return view('plans.index', compact('plans'));
    }

    public function show(Plan $plan) {
        
        $intent = Auth::user()->createSetupIntent();
        return view('plans.show', compact('plan', 'intent'));
    }

}
