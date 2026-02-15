@extends('layouts.main_layout')


@section('content')
    <div class="credit-body">
		<div class="alert-window">
			@if(session('success'))
				<div class="alert alert-success">
                    <h1>Payment Successful!</h1>
                    <p>{{ session('success') }}</p>
                </div>
			@endif
			@if(session('error'))
				<div class="alert alert-danger">
                    <h1>{{ session('error') }}</h1>
                    <p>Failed! Please Try Again!</p>
                </div>
			@endif
		</div>
    </div>
    <div class="credit-header"> 
        <h1>Choose Payment Method</h1>
        <p>You can now top-up credit on your own through online</p>
    </div>
    <div class="payment-methods">
        <button id="visa-btn"><img src="/images/visa.png" alt="VISA" class="pay-icns">VISA</button>
        <button id="mastercard-btn"><img src="/images/mastercard.png" alt="MASTERCARD" class="pay-icns">MASTERCARD</button>
        <button id="gcash-btn"><img src="/images/gcash.png" alt="GCASH" class="pay-icns">GCASH</button>
    </div>
    <div class="the-credit-window">
        <div class="moving-window visa-active">
            <div class="credit-card-window ww">
                <form method="POST" action="/payment/credit">
                    @csrf
                    <h2>Visa</h2>
                    <div class="w-input">
                        <label for="visa_amount">Amount</label>
                        <input type="number" id="visa_amount" name="amount" required min="1" step="0.01" placeholder="Enter amount">
                    </div>
                    <div class="w-input">
                        <label for="visa_cardholder_name">Cardholder Name</label>
                        <input type="text" id="visa_cardholder_name" name="cardholder_name" required>
                    </div>
                    <div class="w-input">
                        <label for="visa_card_number">Card Number</label>
                        <input type="text" id="visa_card_number" name="card_number" required maxlength="19" placeholder="1234 5678 9012 3456">
                    </div>
                    <div class="w-input">
                        <label for="visa_exp_month">Expiry Month</label>
                        <input type="text" id="visa_exp_month" name="exp_month" required maxlength="2" placeholder="MM">
                    </div>
                    <div class="w-input">
                        <label for="visa_exp_year">Expiry Year</label>
                        <input type="text" id="visa_exp_year" name="exp_year" required maxlength="4" placeholder="YYYY">
                    </div>
                    <div class="w-input">
                        <label for="visa_cvc">CVC</label>
                        <input type="text" id="visa_cvc" name="cvc" required maxlength="4" placeholder="CVC">
                    </div>
                    <div class="w-input">
                        <label for="visa_email">Email</label>
                        <input type="email" id="visa_email" name="email" required placeholder="Email">
                    </div>
                    <button type="submit" class="credit-design-btn">Proceed Payment</button>
                </form>
            </div>
            <div class="credit-card-window ww">
                <form method="POST" action="/payment/credit">
                    @csrf
                    <h2>Mastercard</h2>
                    <div class="w-input">
                        <label for="mc_amount">Amount</label>
                        <input type="number" id="mc_amount" name="amount" required min="1" step="0.01" placeholder="Enter amount">
                    </div>
                    <div class="w-input">
                        <label for="mc_cardholder_name">Cardholder Name</label>
                        <input type="text" id="mc_cardholder_name" name="cardholder_name" required>
                    </div >
                    <div class="w-input">
                        <label for="mc_card_number">Card Number</label>
                        <input type="text" id="mc_card_number" name="card_number" required maxlength="19" placeholder="1234 5678 9012 3456">
                    </div>
                    <div class="w-input">
                        <label for="mc_exp_month">Expiry Month</label>
                        <input type="text" id="mc_exp_month" name="exp_month" required maxlength="2" placeholder="MM">
                    </div>
                    <div class="w-input">
                        <label for="mc_exp_year">Expiry Year</label>
                        <input type="text" id="mc_exp_year" name="exp_year" required maxlength="4" placeholder="YYYY">
                    </div>
                    <div class="w-input">
                        <label for="mc_cvc">CVC</label>
                        <input type="text" id="mc_cvc" name="cvc" required maxlength="4" placeholder="CVC">
                    </div>
                    <div class="w-input">
                        <label for="mc_email">Email</label>
                        <input type="email" id="mc_email" name="email" required placeholder="Email">
                    </div>
                    <button type="submit" class="credit-design-btn">Proceed Payment</button>
                </form>
            </div>
            <div class="gcash-card-window ww">
                <form method="POST" action="/payment/gcash">
                    @csrf
                    <h2>GCash Payment</h2>
                    <div class="w-input">
                        <label for="gcash_amount">Amount</label>
                        <input type="number" id="gcash_amount" name="amount" required min="1" step="0.01" placeholder="Enter amount">
                    </div>
                    <div class="w-input">
                        <label for="gcash_name">Name</label>
                        <input type="text" id="gcash_name" name="name" required placeholder="Full Name">
                    </div>
                    <div class="w-input">
                        <label for="gcash_email">Email</label>
                        <input type="email" id="gcash_email" name="email" required placeholder="Email">
                    </div>
                    <button type="submit" class="credit-design-btn">Pay with GCash</button>
                </form>
            </div>
        </div>
    </div>
	<div class="footer">
        <p>All Rights Reserved &#xA9; 2026</p>
    </div>
@endsection
