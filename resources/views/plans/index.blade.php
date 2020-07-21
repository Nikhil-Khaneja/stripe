@extends('layouts.app')

@section('content')
    <div class="container">
        <div class="row justify-content-center">
            <div class="col-md-12">
                <div class="card">
                    <div class="card-header">Plans</div>
                    <div class="card-body">
                        <ul class="list-group">
                            @foreach($plans as $plan)
                                <li class="list-group-item">
                                    <div>
                                        <h5>{{ $plan->name }}</h5>
                                        <h5>INR {{ number_format($plan->cost, 2)}}</h5>
                                        <h5>{{ $plan->description }}</h5>

                                        @if(!auth()->user()->subscribedToPlan($plan->stripe_plan, $plan->name))
                                            <a href="{{ route('plans.show', $plan->slug) }}" class="btn btn-outline-dark">Choose</a>
                                        @endif
                                    </div>
                                </li>
                            @endforeach
                        </ul>
                    </div>
                </div>
            </div>
        </div>
    </div>
@endsection
