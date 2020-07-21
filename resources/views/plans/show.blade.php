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

@section('scripts')
    <script src="https://js.stripe.com/v3/"></script>
    <script>
        var style = {
            base: {
                iconColor: '#c4f0ff',
                color: '#32325D',
                fontWeight: 500,
                fontFamily: 'Roboto, Open Sans, Segoe UI, sans-serif',
                fontSize: '16px',
                fontSmoothing: 'antialiased',
                ':-webkit-autofill': {
                    color: '#fce883',
                },
                '::placeholder': {
                    color: '#87BBFD',
                },
            },
            invalid: {
                iconColor: '#FFC7EE',
                color: '#FFC7EE',
            },
        };
        const stripe = Stripe('{{ env('STRIPE_KEY') }}', {locale: 'en'});
        const elements = stripe.elements(); // Create and return the instance of stripe elements (card details wala fields)
        const cardElement = elements.create('card', {style: style});
        cardElement.mount('#card-element');

        const cardButton = document.getElementById('card-button');
        const clientSecret  = cardButton.dataset.secret;

        //Run  Time Validations :
        cardElement.on('change',  function (event) {
            if (event.complete) {
                // enable payment button
            } else if (event.error) {
                document.getElementById('card-errors').textContent = event.error.message;
            } else {
                document.getElementById('card-errors').textContent = ''
            }
        });

        // Handle Form Submission
        var form = document.getElementById('payment-form');
        form.addEventListener('submit', function (event) {
            event.preventDefault();

            stripe
                .confirmCardSetup(clientSecret, {
                    payment_method: {
                        card: cardElement,
                    },
                })
                .then(function(result) {
                    // Handle result.error or result.setupIntent
                    console.log(result);
                    if(result.error){
                        document.getElementById('card-errors').textContent = result.error.message;
                    }else {
                        stripeTokenHandler(result.setupIntent.payment_method)
                    }
                });
        });
        //custom made function to handle form submit
        function stripeTokenHandler(paymentMethod){
            var form = document.getElementById('payment-form');
            var hiddenInput =  document.createElement('input');
            hiddenInput.setAttribute('type', 'hidden');
            hiddenInput.setAttribute('name', 'paymentMethod');
            hiddenInput.setAttribute('value', paymentMethod);
            form.appendChild(hiddenInput);

            form.submit();
        }
    </script>
@endsection