
@extends('layouts.main_layout')

@section('content')
    <div class="transaction-body">
        <div class="myWallet">
            <img src="/images/wallet.png" id="myWallet-icn">
            <h2>
                @if(isset($student))
                    {{ $student->FirstName }} {{ $student->LastName ?? '' }}
                @else
                    GIVEN SURNAME
                @endif
            </h2>
            <p>Available Balance</p>
            <h1>&#8369; {{ isset($student) && isset($student->Balance) ? number_format($student->Balance, 2) : '00.00' }}</h1>
        </div>
        <div class="recent-transaction">
            <h1>Recent Transaction</h1>
            <table class="transaction-table">
                <thead>
                    <tr>
                        <th>Item Name</th>
                        <th>Quantity</th>
                        <th>Price</th>
                    </tr>
                </thead>
                <tbody>
                    @if(isset($recentOrder) && count($recentOrder) > 0)
                        @foreach($recentOrder as $item)
                            <tr>
                                <td>{{ $item['ProductName'] }}</td>
                                <td>{{ $item['Quantity'] }}</td>
                                <td class="credit">&#8369; {{ number_format($item['Price'], 2) }}</td>
                            </tr>
                        @endforeach
                    @else
                        <tr>
                            <td colspan="3" style="text-align:center;">No recent transaction found.</td>
                        </tr>
                    @endif
                </tbody>
            </table>
            <div class="transaction-footer">
                <a href="">View Statement <img src="/images/arrow-right.png"></a>
            </div>
        </div>
        <div class="transaction-history">
            <div class="transaction-menu">
                <form method="GET" id="transactionMenuForm" style="display: flex; gap: 10px; align-items: center;">
                    <input type="text" name="search" class="search-input" placeholder="Search history" value="{{ request('search') }}">
                    <select name="sort" class="sortby" onchange="this.form.submit()">
                        <option value="" disabled selected hidden>Sort By</option>
                        <option value="none" {{ request('sort') == 'none' ? 'selected' : '' }}>None</option>
                        <option value="date_desc" {{ request('sort') == 'date_desc' ? 'selected' : '' }}>Date: Newest to Oldest</option>
                        <option value="date_asc" {{ request('sort') == 'date_asc' ? 'selected' : '' }}>Date: Oldest to Newest</option>
                        <option value="price_asc" {{ request('sort') == 'price_asc' ? 'selected' : '' }}>Price: Low to High</option>
                        <option value="price_desc" {{ request('sort') == 'price_desc' ? 'selected' : '' }}>Price: High to Low</option>
                    </select>
                    <select name="filter" class="filter" onchange="this.form.submit()">
                        <option value="" disabled selected hidden>Filter</option>
                        <option value="none" {{ request('filter') == 'none' ? 'selected' : '' }}>None</option>
                        <option value="daily" {{ request('filter') == 'daily' ? 'selected' : '' }}>Daily</option>
                        <option value="monthly" {{ request('filter') == 'monthly' ? 'selected' : '' }}>Monthly</option>
                        <option value="yearly" {{ request('filter') == 'yearly' ? 'selected' : '' }}>Yearly</option>
                    </select>
                    <button type="submit" style="display:none;"></button>
                </form>
            </div>
            <table class="super-invoice-table">
                <thead>
                    <tr>
                        <th>Date</th>
                        <th>Receipt No.</th>
                        <th>Product Name</th>
                        <th>Qty</th>
                        <th>Product Price</th>
                    </tr>
                </thead>
                <tbody>
                    @if(isset($allPurchases) && count($allPurchases) > 0)
                        @foreach($allPurchases as $purchase)
                            <tr>
                                <td>{{ $purchase['Date'] }}</td>
                                <td>{{ $purchase['ReceiptNo'] }}</td>
                                <td>{{ $purchase['ProductName'] }}</td>
                                <td>{{ $purchase['Quantity'] }}</td>
                                <td class="credit">&#8369; {{ number_format($purchase['Price'], 2) }}</td>
                            </tr>
                        @endforeach
                    @else
                        <tr>
                            <td colspan="5" style="text-align:center;">No purchases found.</td>
                        </tr>
                    @endif
                </tbody>
            </table>
        </div>
    </div>
@endsection
