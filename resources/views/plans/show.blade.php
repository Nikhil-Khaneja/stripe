@extends('layouts.app')

@section('content')
    <div class="container">
        <div class="row justify-content-center">
            <div class="col-md-12">
                <div>
                    <p>You will be charged INR {{ number_format($plan->cost, 2) }} for {{ $plan->name }} Plan.</p>
                </div>
                <div class="card">

                        <form action="{{ route('subscriptions.create') }}" method="POST" id="payment-form">
                            @csrf
                            <div class="form-group">
                                <div class="card-header">
                                    <label for="card-element">
                                        Enter your card details...
                                    </label>
                                </div>
                                <div class="card-body">
                                    <div id="card-element">
                                        <!--A Stripe Element Will be Inserted Here-->
                                    </div>
                                    <!--The following element will be used to display errors if any-->
                                    <div id="card-errors"></div>
                                    <input type="hidden" name="plan" value="{{ $plan->id }}">
                                </div>
                                <div class="card-footer">
                                    <button id="card-button" class="btn btn-dark" type="submit" data-secret="{{ $intent->client_secret }}">Pay</button>
                                </div>
                            </div>
                        </form>
                    </div>
                </div>
            </div>
        </div>
    </div>
@endsection