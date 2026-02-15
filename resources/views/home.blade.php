@extends('layouts.main_layout')

@section('content')
    <div class="home-body">
        <div class="home-box">
            <div class="wallet-container">
                <div class="wallet-header">
                    <h1>Wallet</h1>
                    <button id="wallet-option-btn"><img src="/images/dots.png" alt=""></button>
                </div>
                <div class="wallet-info">
                    <h4>Total Balance</h4>
                    <span id="balance-info"><h1>&#x20B1;</h1><h1 id="balance-amount">{{ isset($student) && isset($student->Balance) ? number_format($student->Balance, 2) : '0.00' }}</h1></span>
                    <p>&#9733;&#9733;&#9733;&#9733;&#9733;</p>
                    <span id="wallet-more-info">
                        <p id="rfid-holder">
                            {{ (isset($student) && !empty($student->LRN)) ? $student->LRN : '0000000000' }}
                        </p>
                        <p>
                            @if(isset($student) && !empty($student->DateCreated))
                                {{ \Carbon\Carbon::parse($student->DateCreated)->format('F Y') }}
                            @else
                                January 2024
                            @endif
                        </p>
                    </span>
                    <span class="peso-icon">&#x20B1;</span>
                </div>
                <div class="card-container">
                    <div class="card-details">
                        <h4>Card Details</h4>
                        <div class="card-header">
                        <div class="card-header-info">
                            <span class="card-name">
                                @if(isset($student))
                                    {{ $student->FirstName }} {{ $student->LastName ?? '' }} {{ $student->MiddleInitial ?? '' }}
                                @else
                                    Who are You?
                                @endif
                            </span>
                            <span class="card-number">
                                @if(isset($student) && !empty($student->RFIDTag))
                                    {{ $student->RFIDTag }}
                                @else
                                    0000000000
                                @endif
                            </span>
                        </div>
                        <img src="/images/chip.png" class="chip-icon" alt="chip">
                        </div>
                        <div id="card-more-info" class="lock-card-row">
                            <div class="lock-card-left">
                                <img src="/images/lock.png" class="lock-icon" alt="Lock">
                                <div class="lock-card-text">
                                    <h4>Lock Card</h4>
                                    <p>Block transactions made with this card</p>
                                </div>
                            </div>
                            <label class="switch">
                                <input type="checkbox" id="lock-switch" data-student-id="{{ isset($student) ? $student->StudentID : '' }}" {{ (isset($student) && $student->IsBlocked) ? 'checked' : '' }}>
                                <span class="slider"></span>
                            </label>
                        </div>
                    </div>
                </div>
            </div>
        </div>
        <div class="home-box">
            <h2>WELCOME,</h2>
            <h1>
                @if(isset($student))
                    {{ $student->FirstName }} {{ $student->LastName ?? '' }} {{ $student->MiddleInitial ?? '' }}
                @else
                    GIVEN SURNAME M.I.
                @endif
            </h1>
            <span><img src="/images/lrn.png" id="lrn_icon"><p>{{ (isset($student) && !empty($student->LRN)) ? $student->LRN : '0000000000' }}</p></span>
        </div>
        <div class="home-box">
            <div class="spending-container">
                <h2>My Invoice &nbsp;<img src="/images/invoice.png" id="invoice-icn"></h2>
                <div class="invoice-container">
                    <p>Today's Spending</p>
                    <h1>&#x20B1; {{ isset($dailySpending) ? number_format($dailySpending, 2) : '0.00' }}</h1>
                    <h3>Recent Purchases</h3>
                    <table class="invoice-table">
                        <thead>
                            <tr>
                                <th style="width:50%">Purchased</th>
                                <th style="width:25%">Price</th>
                                <th style="width:30%">Date</th>
                            </tr>
                        </thead>
                        <tbody>
                        @if(isset($recentOrders) && count($recentOrders) > 0)
                            @foreach($recentOrders as $orderIndex => $order)
                                <tr>
                                    <td class="purchased-cell" style="width:50%">
                                        <div class="purchased-scroll">
                                            @foreach($order['items'] as $item)
                                                <span style="display:inline-block; margin-bottom:4px;">
                                                    {{ $item['ProductName'] }} ({{ $item['Quantity'] }})@if(!$loop->last), @endif
                                                </span>
                                            @endforeach
                                        </div>
                                    </td>
                                    <td style="width:25%">₱{{ number_format($order['total'], 2) }}</td>
                                    <td style="width:30%">{{ \Carbon\Carbon::parse($order['date'])->format('Y-m-d') }}</td>
                                </tr>
                            @endforeach
                        @else
                            <tr>
                                <td colspan="3">No recent purchases found.</td>
                            </tr>
                        @endif
                        </tbody>
                    </table>
                </div>
                
            </div>
        </div>
      
    </div>
@endsection
