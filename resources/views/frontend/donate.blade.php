@extends('layouts.frontend.master')

@section('title')
    Donate
@endsection

@section('content')
    <section class="page-header" style="background-image: url({{asset('frontend/images/banner/search-page-banner.jpg')}});">
        <div class="container">
            <h2>Donate</h2>
        </div>
    </section>

    <section class="donate-one">
        <div class="container">
            <div class="row">
                <div class="col-xl-4">
                    <div class="donate-one__block">
                        <div class="block-title">
                            <p>Help Us Grow</p>
                            <h3>Make a Donation</h3>
                        </div>
                        <p>As a family member searching for Jewish History or a student researching Jewish History you will find a tremendous amount of research and Jewish historical data available. Millions of Jewish History records have been compiled including Jewish Cemetery Records, Jewish Tombstone Inscriptions with Hebrew headstone translations. See many photos of Jewish Cemeteries, Jewish Tombstones and read inscriptions dating back hundreds of years. Whether you are building a family tree or simply researching your Jewish History you will find Jewish death records, Ellis Island Jewish Passenger Lists and Social Security historical data. Research your Jewish History and discover your Jewish ancestry. Visit Jewish Cemeteries around the world and see original Hebrew inscriptions and translations. Learn, know and pass down your Jewish History to the next generation.</p>
                    </div>
                </div>
                <div class="col-xl-8">
                    <div class="donate-one__form">
                        <!-- Success message -->
                        @if(Session::has('success'))
                            <div class="alert alert-success">
                                {{Session::get('success')}}
                            </div>
                        @endif
                        <form action="{{ route('donate.store') }}" method="post" id="payment_form">
                            @csrf
                            <h3 class="donate-one__title">Make a Donation Today</h3>
                            <div class="donate-one__amount">
                                <select id="select_currency" class="selectpicker" onchange="getCurrency()">
                                    <option value="$">$</option>
                                    <option value="₤">₤</option>
                                    <option value="¥">¥</option>
                                </select>
                                <input type="text" id="donate_money" name="donation-money" onchange="getMoney()" value="70">
                            </div>
                            <div class="donate-one__selected-amount">
                                <a id="70" class="active" onclick="get70()"><span id="currency_70">$</span> 70</a>
                                <a id="80" onclick="get80()"><span id="currency_80">$</span> 80</a>
                                <a id="90" onclick="get90()"><span id="currency_90">$</span> 90</a>
                                <a id="100" onclick="get100()"><span id="currency_100">$</span> 100</a>
                            </div>
                            <h4 class="donate-one__subtitle">Personal Info</h4>
                            <div class="row">
                                <div class="col-lg-6">
                                    <div class="donate-one__input">
                                        <label>First Name <span>*</span></label>
                                        <input name="fname" type="text" placeholder="First name" required>
                                    </div>
                                </div>
                                <div class="col-lg-6">
                                    <div class="donate-one__input">
                                        <label>Last Name</label>
                                        <input type="text" name="lname" placeholder="Last name" required>
                                    </div>
                                </div>
                                <div class="col-lg-12">
                                    <div class="donate-one__input">
                                        <label>Email Address <span>*</span></label>
                                        <input type="text" placeholder="Email address" name="email" required>
                                    </div>
                                </div>
                                <div class="col-lg-12">
                                    <div class="donate-one__input">
                                        <label for="card-element">Credit Card Number <span>*</span></label>
                                        <div id="card-element">
                                            <!-- A Stripe Element will be inserted here. -->
                                        </div>
                                    
                                        <!-- Used to display form errors. -->
                                        <div id="card-errors" role="alert"></div>
                                    </div>
                                </div>
                                <div class="col-lg-12">
                                    <div class="donate_total">
                                        Total Donation: <span id="total_currency">$</span><input id="donate_amount" type="text" disabled value="70">
                                    </div>

                                    <button type="submit" class="thm-btn donate-one__btn">Donate Now</button>
                                </div>
                            </div>
                        </form>
                    </div>
                </div>
            </div>
        </div>
    </section>

    <style>

        .donate_total {
            border: 1px solid #e5e5e5;
            width: auto;
            max-width: 286px;
            font-size: 18px;
            color: #252930;
            padding-left: 15px;
            margin-bottom: 20px;
        }

        .donate_total span{
            background-color: #dadada !important;
            height: 100% !important;
            line-height: 50px;
            padding: 15px;
            margin-left: 20px;
        }


        .donate_total input{
            border: 0 !important;
            width: 80px !important;
            height: 50px !important;
            line-height: 50px !important;
        }
        /* Add some basic styling */
        .form-row {
            margin-bottom: 15px;
        }
        #card-element {
            border: 1px solid #ccc;
            border-radius: 4px;
            padding: 10px;
        }
        #card-errors {
            color: red;
            margin-top: 10px;
        }
    </style>

    <!-- Stripe JS -->
    <script src="https://js.stripe.com/v3/"></script>
    <script>
        // Create a Stripe client.
        var stripe = Stripe('{{ env('STRIPE_KEY')}}');

        // Create an instance of Elements.
        var elements = stripe.elements();

        // Create an instance of the card Element.
        var card = elements.create('card');

        // Add an instance of the card Element into the `card-element` div.
        card.mount('#card-element');

        // Handle real-time validation errors from the card Element.
        card.addEventListener('change', function(event) {
            var displayError = document.getElementById('card-errors');
            if (event.error) {
                displayError.textContent = event.error.message;
            } else {
                displayError.textContent = '';
            }
        });

        // Handle form submission.
        var form = document.getElementById('payment_form');
        form.addEventListener('submit', function(event) {
            event.preventDefault();

            stripe.createToken(card).then(function(result) {
                if (result.error) {
                    // Inform the user if there was an error.
                    var errorElement = document.getElementById('card-errors');
                    errorElement.textContent = result.error.message;
                } else {
                    // Send the token to your server.
                    stripeTokenHandler(result.token);
                }
            });
        });

        // Submit the form with the token ID.
        function stripeTokenHandler(token) {
            // Insert the token ID into the form so it gets submitted to the server
            var form = document.getElementById('payment_form');
            var hiddenInput = document.createElement('input');
            hiddenInput.setAttribute('type', 'hidden');
            hiddenInput.setAttribute('name', 'stripeToken');
            hiddenInput.setAttribute('value', token.id);
            form.appendChild(hiddenInput);

            // Submit the form
            form.submit();
        }

    </script>
@endsection