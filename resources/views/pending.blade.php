@extends('layouts.main_layout')

@section('content')
<div class="pending-container">
    <h1>Pending Orders</h1>
    
    @if($pendingOrders->isEmpty())
        <div class="no-pending-orders">
            <p>You have no pending orders at the moment.</p>
        </div>
    @else
        <ul class="pending-orders-list">
            @foreach($pendingOrders as $preOrder)
                @if($preOrder->expires_at)
                <li class="pending-order-item" data-pre-order-id="{{ $preOrder->id }}" data-expires-at="{{ $preOrder->expires_at->toIso8601String() }}">
                    <div class="order-header">
                        <div class="order-info">
                            <span class="order-id"><strong>Order ID:</strong> {{ $preOrder->OrderID }}</span>
                            <span class="order-date"><strong>Date:</strong> {{ $preOrder->OrderDate->format('M d, Y - h:i A') }}</span>
                        </div>
                        <div class="order-actions">
                            <div class="timer-display" data-timer="">
                                <span class="timer-icon">⏱️</span>
                                <span class="timer-text">30:00</span>
                            </div>
                            <span class="status-badge status-{{ strtolower($preOrder->Status) }}">
                                {{ ucfirst($preOrder->Status) }}
                            </span>
                            @if(strtoupper($preOrder->Status) !== 'CANCELLED' && strtoupper($preOrder->Status) !== 'COMPLETED')
                                <button class="cancel-btn">Cancel</button>
                            @endif
                        </div>
                    </div>

                    <div class="order-items">
                        <h4>Items:</h4>
                        @if(is_array($preOrder->Items) && count($preOrder->Items) > 0)
                            <ul class="items-list">
                                @foreach($preOrder->Items as $item)
                                    <li class="item">
                                        <div class="item-details">
                                            <span class="item-name">{{ $item['ProductName'] ?? 'Unknown Product' }}</span>
                                            <span class="item-qty">Qty: {{ $item['Quantity'] ?? 0 }}</span>
                                        </div>
                                        <div class="item-price">
                                            ₱{{ number_format($item['Price'] ?? 0, 2) }}
                                        </div>
                                    </li>
                                @endforeach
                            </ul>
                        @else
                            <p class="no-items">No items in this order</p>
                        @endif
                    </div>

                    <div class="order-footer">
                        <div class="total-price">
                            <strong>Total Price:</strong> ₱{{ number_format($preOrder->TotalPrice, 2) }}
                        </div>
                    </div>
                </li>
                @endif
            @endforeach
        </ul>
    @endif
</div>

@if(isset($cancelledOrders) && $cancelledOrders->isNotEmpty())
<div class="cancelled-container">
    <h2>Cancelled Orders</h2>
    <ul class="cancelled-orders-list">
        @foreach($cancelledOrders as $corder)
            <li class="cancelled-order-item">
                <div class="order-header">
                    <div class="order-info">
                        <span class="order-id"><strong>Order ID:</strong> {{ $corder->OrderID }}</span>
                        <span class="order-date"><strong>Date:</strong> {{ optional($corder->OrderDate)->format('M d, Y - h:i A') }}</span>
                    </div>
                    <div class="order-actions">
                        <span class="status-badge status-cancelled">CANCELLED</span>
                    </div>
                </div>

                <div class="order-items">
                    <h4>Items:</h4>
                    @if(is_array($corder->Items) && count($corder->Items) > 0)
                        <ul class="items-list">
                            @foreach($corder->Items as $item)
                                <li class="item">
                                    <div class="item-details">
                                        <span class="item-name">{{ $item['ProductName'] ?? 'Unknown Product' }}</span>
                                        <span class="item-qty">Qty: {{ $item['Quantity'] ?? 0 }}</span>
                                    </div>
                                    <div class="item-price">₱{{ number_format($item['Price'] ?? 0, 2) }}</div>
                                </li>
                            @endforeach
                        </ul>
                    @else
                        <p class="no-items">No items recorded for this order</p>
                    @endif
                </div>

                <div class="order-footer">
                    <div class="total-price">
                        <strong>Total Price:</strong> ₱{{ number_format($corder->TotalPrice, 2) }}
                    </div>
                </div>
            </li>
        @endforeach
    </ul>
</div>
@endif

<style>
    .cancelled-container {
        max-width: 900px;
        margin: 2rem auto;
        padding: 1.5rem;
        background-color: #ffffff;
        border-radius: 8px;
    }

    .pending-container {
        max-width: 900px;
        margin: 2rem auto;
        padding: 1.5rem;
        background-color: #f9f9f9;
        border-radius: 8px;
    }

    .pending-container h1 {
        color: #333;
        margin-bottom: 2rem;
        text-align: center;
    }

    .no-pending-orders {
        text-align: center;
        padding: 3rem 1rem;
        color: #666;
        font-size: 1.1rem;
    }

    .pending-orders-list {
        list-style: none;
        padding: 0;
        margin: 0;
    }

    .pending-order-item {
        background: white;
        border: 1px solid #e0e0e0;
        border-radius: 8px;
        padding: 1.5rem;
        margin-bottom: 1.5rem;
        box-shadow: 0 2px 4px rgba(0, 0, 0, 0.05);
        transition: all 0.3s ease;
    }

    .pending-order-item.timer-expired {
        opacity: 0.6;
        background-color: #f5f5f5;
    }

    .order-header {
        display: flex;
        justify-content: space-between;
        align-items: center;
        margin-bottom: 1rem;
        border-bottom: 1px solid #f0f0f0;
        padding-bottom: 1rem;
        flex-wrap: wrap;
        gap: 1rem;
    }

    .order-info {
        display: flex;
        gap: 2rem;
        flex-wrap: wrap;
    }

    .order-id,
    .order-date {
        font-size: 0.95rem;
        color: #333;
    }

    .order-id strong,
    .order-date strong {
        color: #555;
    }

    .order-actions {
        display: flex;
        gap: 1rem;
        align-items: center;
    }

    .timer-display {
        display: flex;
        align-items: center;
        gap: 0.5rem;
        padding: 0.5rem 1rem;
        background-color: #fff3cd;
        border-radius: 20px;
        font-size: 0.9rem;
        font-weight: 600;
        color: #856404;
    }

    .timer-display.timer-warning {
        background-color: #ffe5e5;
        color: #dc3545;
    }

    .timer-icon {
        font-size: 1.1rem;
    }

    .timer-text {
        min-width: 45px;
        text-align: center;
        font-family: 'Courier New', monospace;
    }

    .status-badge {
        padding: 0.5rem 1rem;
        border-radius: 20px;
        font-size: 0.85rem;
        font-weight: 600;
        text-transform: uppercase;
        white-space: nowrap;
    }

    .status-pending {
        background-color: #fff3cd;
        color: #856404;
    }

    .status-confirmed {
        background-color: #d4edda;
        color: #155724;
    }

    .status-completed {
        background-color: #d1ecf1;
        color: #0c5460;
    }

    .status-cancelled {
        background-color: #f8d7da;
        color: #721c24;
    }

    .order-items {
        margin: 1.5rem 0;
    }

    .order-items h4 {
        color: #333;
        margin: 0 0 0.75rem 0;
        font-size: 1rem;
    }

    .items-list {
        list-style: none;
        padding: 0;
        margin: 0;
        background-color: #f5f5f5;
        border-radius: 6px;
        overflow: hidden;
    }

    .item {
        display: flex;
        justify-content: space-between;
        align-items: center;
        padding: 0.75rem 1rem;
        border-bottom: 1px solid #e0e0e0;
    }

    .item:last-child {
        border-bottom: none;
    }

    .item-details {
        display: flex;
        flex-direction: column;
        gap: 0.25rem;
    }

    .item-name {
        font-weight: 600;
        color: #333;
        font-size: 0.95rem;
    }

    .item-qty {
        font-size: 0.85rem;
        color: #666;
    }

    .item-price {
        font-weight: 600;
        color: #2c7a4c;
        font-size: 0.95rem;
    }

    .no-items {
        padding: 0.75rem 1rem;
        color: #999;
        font-style: italic;
    }

    .order-footer {
        margin-top: 1rem;
        border-top: 1px solid #f0f0f0;
        padding-top: 1rem;
        text-align: right;
    }

    .total-price {
        font-size: 1.1rem;
        color: #333;
    }

    .total-price strong {
        color: #2c7a4c;
    }

    @media (max-width: 600px) {
        .order-header {
            flex-direction: column;
            align-items: flex-start;
            gap: 0.75rem;
        }

        .order-info {
            flex-direction: column;
            gap: 0.5rem;
            width: 100%;
        }

        .order-actions {
            width: 100%;
            justify-content: space-between;
        }

        .item {
            flex-direction: column;
            align-items: flex-start;
            gap: 0.5rem;
        }

        .item-price {
            align-self: flex-end;
        }
    }
</style>

<script>
document.addEventListener('DOMContentLoaded', function() {
    const csrfToken = document.querySelector('meta[name="csrf-token"]').getAttribute('content');
    
    // Function to format seconds to MM:SS
    function formatTime(seconds) {
        const mins = Math.floor(seconds / 60);
        const secs = seconds % 60;
        return `${mins}:${secs.toString().padStart(2, '0')}`;
    }

    // Function to update timer for an order
    function updateTimer(orderItem) {
        const expiresAt = new Date(orderItem.dataset.expiresAt);
        const now = new Date();
        const secondsLeft = Math.floor((expiresAt - now) / 1000);

        const timerDisplay = orderItem.querySelector('.timer-display');
        const timerText = timerDisplay.querySelector('.timer-text');
        const statusBadge = orderItem.querySelector('.status-badge');

        if (secondsLeft <= 0) {
            // Timer expired - cancel the order
            timerText.textContent = '0:00';
            orderItem.classList.add('timer-expired');
            statusBadge.textContent = 'CANCELLED';
            statusBadge.className = 'status-badge status-cancelled';

            // Call API to update status
            const preOrderId = orderItem.dataset.preOrderId;
            fetch('/student/cancel-order', {
                method: 'POST',
                headers: {
                    'Content-Type': 'application/json',
                    'X-CSRF-TOKEN': csrfToken
                },
                body: JSON.stringify({ pre_order_id: preOrderId })
            }).catch(error => console.error('Error cancelling order:', error));
        } else {
            // Update timer display
            timerText.textContent = formatTime(secondsLeft);

            // Change color if less than 5 minutes
            if (secondsLeft < 300) {
                timerDisplay.classList.add('timer-warning');
            } else {
                timerDisplay.classList.remove('timer-warning');
            }
        }
    }

    // Initialize timers for all pending orders
    const pendingOrders = document.querySelectorAll('.pending-order-item');
    
    pendingOrders.forEach(orderItem => {
        // Initial update
        updateTimer(orderItem);

        // Update every second and store interval id so it can be cleared
        const intervalId = setInterval(() => {
            updateTimer(orderItem);
        }, 1000);
        orderItem.dataset.intervalId = intervalId.toString();
    });

    // Cancel button handler
    document.querySelectorAll('.cancel-btn').forEach(btn => {
        btn.addEventListener('click', function(e) {
            e.preventDefault();
            const orderItem = this.closest('.pending-order-item');
            const preOrderId = orderItem.dataset.preOrderId;

            if (!confirm('Are you sure you want to cancel this order now?')) return;

            fetch('/student/cancel-order', {
                method: 'POST',
                headers: {
                    'Content-Type': 'application/json',
                    'X-CSRF-TOKEN': csrfToken
                },
                body: JSON.stringify({ pre_order_id: preOrderId })
            })
            .then(res => res.json())
            .then(data => {
                if (data && data.success) {
                    // Update UI immediately
                    const statusBadge = orderItem.querySelector('.status-badge');
                    const timerText = orderItem.querySelector('.timer-text');
                    orderItem.classList.add('timer-expired');
                    statusBadge.textContent = 'CANCELLED';
                    statusBadge.className = 'status-badge status-cancelled';
                    timerText.textContent = '0:00';

                    // stop interval if exists
                    if (orderItem.dataset.intervalId) {
                        clearInterval(parseInt(orderItem.dataset.intervalId));
                        delete orderItem.dataset.intervalId;
                    }

                    // remove cancel button to prevent double-click
                    const cancelBtn = orderItem.querySelector('.cancel-btn');
                    if (cancelBtn) cancelBtn.remove();
                } else {
                    alert((data && data.message) ? data.message : 'Failed to cancel order');
                }
            })
            .catch(err => {
                console.error('Error cancelling order:', err);
                alert('An error occurred while cancelling the order.');
            });
        });
    });
});
</script>
@endsection
