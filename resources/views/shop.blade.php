@extends('layouts.main_layout')

@section('content')
    <div class="shop-body">
        <div class="shop-header">
            <h1>Spend Smart
                @if(isset($student))
                    {{ $student->FirstName }}!
                @else
                    Guest!
                @endif
            </h1>
            <p>Explore our products and make purchases using your SMARTPAY wallet.</p>
        </div>
        <div class="shop-container">
            <!-- Category Sidebar -->
            <aside class="shop-sidebar">
                <div class="sidebar-header">
                    <h3>Categories</h3>
                </div>
                <nav class="category-nav">
                    <a href="{{ route('student.shop') }}" class="category-link {{ !$selectedCategory || $selectedCategory == 'all' ? 'active' : '' }}">
                        <span class="category-icon">🏪</span>
                        All Products
                    </a>
                    @if(isset($categories) && count($categories) > 0)
                        @foreach($categories as $category)
                            <a href="{{ route('student.shop', ['category' => $category->CategoryID]) }}" 
                               class="category-link {{ $selectedCategory == $category->CategoryID ? 'active' : '' }}">
                                <span class="category-icon">📦</span>
                                {{ $category->CategoryName }}
                            </a>
                        @endforeach
                    @endif
                </nav>
            </aside>

            <!-- Products Grid -->
            <div class="shop-content">
                @if(isset($products) && count($products) > 0)
                    <div class="products-grid">
                        @foreach($products as $product)
                            <div class="product-card">
                                <div class="product-image">
                                    @if($product->ImagePath)
                                        <img src="{{ asset($product->ImagePath) }}" alt="{{ $product->ProductName }}" class="product-img">
                                    @else
                                        <div class="product-img-placeholder">No Image</div>
                                    @endif
                                </div>
                                
                                <div class="product-info">
                                    <h3 class="product-name">{{ $product->ProductName }}</h3>
                                    
                                    <div class="product-price">
                                        <span class="price-label">Price:</span>
                                        <span class="price-value">₱{{ number_format($product->Price, 2) }}</span>
                                    </div>
                                    
                                  <div class="product-stock">
                                        <span class="stock-label">Stock:</span>
                                        @php
                                            $totalStock = $product->TotalPieces;
                                        @endphp
                                            @if($product->StockStatus === 'OUT OF STOCK' || $totalStock <= 0)
                                                <span class="stock-badge out-of-stock">OUT OF STOCK</span>
                                            @elseif($product->StockStatus === 'CRITICAL' || $totalStock <= $product->MinimumStockLevel)
                                                <span class="stock-badge critical">CRITICAL ({{ $totalStock }} left)</span>
                                            @else
                                                <span class="stock-badge available">Available ({{ $totalStock }} in stock)</span>
                                            @endif
                                    </div>
                                    
                                    <button class="add-to-cart-btn" 
                                            onclick="addToCart({{ $product->ProductID }}, '{{ addslashes($product->ProductName) }}', {{ $product->Price }}, '{{ $product->ImagePath }}')"
                                            @if($product->StockStatus === 'OUT OF STOCK' || $totalStock <= 0)
                                                disabled
                                            @endif>
                                        @if($product->StockStatus === 'OUT OF STOCK' || $totalStock <= 0)
                                            Out of Stock
                                        @else
                                            Add to Cart
                                        @endif
                                    </button>
                                </div>
                            </div>
                        @endforeach
                    </div>
                @else
                    <div class="no-products">
                        <p>No products available in this category.</p>
                    </div>
                @endif
            </div>
        </div>
    </div>
@endsection