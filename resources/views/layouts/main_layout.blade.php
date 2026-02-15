<!DOCTYPE html>
<html lang="en">
<head>
    <meta charset="UTF-8">
    <meta name="viewport" content="width=device-width, initial-scale=1.0">
    <meta http-equiv="X-UA-Compatible" content="ie=edge">
    <title>SMARTPAY</title>
    <meta name="csrf-token" content="{{ csrf_token() }}">
    <link rel="icon" type="image/png" href="/images/smartpay_logo.png">
    <link rel="preconnect" href="https://fonts.googleapis.com">
    <link rel="preconnect" href="https://fonts.gstatic.com" crossorigin>
    <link href="https://fonts.googleapis.com/css2?family=Montserrat:ital,wght@0,100..900;1,100..900&display=swap" rel="stylesheet">
    <link rel="preconnect" href="https://fonts.googleapis.com">
    <link rel="preconnect" href="https://fonts.gstatic.com" crossorigin>
    <link href="https://fonts.googleapis.com/css2?family=Roboto:ital,wght@0,100..900;1,100..900&display=swap" rel="stylesheet">
    
    @vite('resources/css/main_style.css')
</head>
<body>
    <div class="logo-container">
        <img src="/images/smartpay_logo.png" alt="logo" id="logo-image">
        <h1>SMARTPAY</h1>
        <button class="menu-btn">&#9776;</button>
        <div class="header-actions">
            <button class="cart-toggle-btn" title="Shopping Cart">
                <img src="/images/cart.png" alt="Cart" class="cart-icon">
                <span class="cart-count">0</span>
            </button>
            <a href="/student/logout">Log out</a>
        </div>
    </div>
    <div class="top-nav">
        <img src="/images/smartpay_logo.png" alt="" id="biglogo">
        <ul class="menu">
            <li><button><img src="/images/menu1.png" class="menu-icn">HOME</button></li>
            <li><button><img src="/images/menu3.png" class="menu-icn">SHOP</button></li>
            <li><button><img src="/images/menu3.png" class="menu-icn">CREDIT</button></li>
            <li><button><img src="/images/menu2.png" class="menu-icn">TRANSACTIONS</button></li>
            <li><button><img src="/images/menu5.png" class="menu-icn">SETTINGS</button></li>
            <li><button><img src="/images/menu2.png" class="menu-icn">PENDING</button></li>
            <li><button><img src="/images/logout.png" class="menu-icn">LOG OUT</button></li>
        </ul>
        <p class="hidden-when-mobile">All Rights Reserved &#xA9;</p>
    </div>
    @yield('content')

    <!-- Shopping Cart Sidebar -->
    <div class="cart-sidebar">
        <div class="cart-header">
            <h2>Shopping Cart</h2>
            <button class="cart-close-btn">&times;</button>
        </div>
        <div class="cart-items">
            <div class="cart-empty">
                <p>Your cart is empty</p>
            </div>
        </div>
        <div class="cart-footer">
            <div class="cart-total">
                <span>Total:</span>
                <span class="total-amount">₱0.00</span>
            </div>
            <button class="checkout-btn">Proceed to Checkout</button>
        </div>
    </div>
    <div class="cart-overlay"></div>

    <!-- Checkout Confirmation Modal -->
    <div class="checkout-modal">
        <div class="checkout-modal-content">
            <div class="modal-header">
                <h2>Confirm Your Order</h2>
                <button class="modal-close-btn">&times;</button>
            </div>
            <div class="modal-body">
                <div class="order-summary">
                    <div class="summary-items">
                        <h3>Order Items</h3>
                        <div id="modal-items-list"></div>
                    </div>
                    <div class="summary-total">
                        <p>Total Amount:</p>
                        <p class="modal-total-amount">₱0.00</p>
                    </div>
                    <div class="summary-balance">
                        <p>Your Balance:</p>
                        <p class="modal-balance-amount">₱0.00</p>
                    </div>
                </div>
            </div>
            <div class="modal-footer">
                <button class="modal-cancel-btn">Cancel</button>
                <button class="modal-confirm-btn">Confirm & Pay</button>
            </div>
        </div>
    </div>
    <div class="modal-overlay"></div>

    @vite('resources/js/main_script.js')
</body>
</html>