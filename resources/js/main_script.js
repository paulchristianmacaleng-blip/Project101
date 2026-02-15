// Avatar upload: show file dialog and preview image
document.addEventListener('DOMContentLoaded', function() {
	const changePfpBtn = document.getElementById('change-pfp-btn');
	const avatarInput = document.getElementById('avatar-input');
	const mypfp = document.getElementById('mypfp');
	if (changePfpBtn && avatarInput && mypfp) {
		changePfpBtn.addEventListener('click', function(e) {
			e.preventDefault();
			avatarInput.click();
		});
		avatarInput.addEventListener('change', function() {
			if (this.files && this.files[0]) {
				const reader = new FileReader();
				reader.onload = function(e) {
					mypfp.src = e.target.result;
				};
				reader.readAsDataURL(this.files[0]);
			}
		});
	}

	// Show success popup for settings update
	const successMessage = document.getElementById('success-message');
	if (successMessage && successMessage.value) {
		alert('✓ ' + successMessage.value);
	}
});

document.addEventListener('DOMContentLoaded', function() {
	// ...existing code...
	const menuBtn = document.querySelector('.menu-btn');
	const topNav = document.querySelector('.top-nav');

	// Menu button routing (HOME, SHOP, CREDIT, TRANSACTIONS, SETTINGS, PENDING, LOG OUT)
	const menuButtons = document.querySelectorAll('.menu button');
	const routes = [
		'/student/home',
		'/student/shop',        // HOME
		'/student/credit',      // CREDIT
		'/student/transaction', // TRANSACTIONS
		'/student/setting',     // SETTINGS
		'/student/pending',     // PENDING
		'/student/logout'       // LOG OUT (end session)
	];

	menuButtons.forEach((btn, idx) => {
		btn.addEventListener('click', function() {
			window.location.href = routes[idx];
		});
	});

	if (menuBtn && topNav) {
		menuBtn.addEventListener('click', function() {
			if (window.innerWidth <= 600) {
				topNav.style.right = topNav.style.right === '0px' ? '-250px' : '0px';
			}
		});

		document.addEventListener('click', function(e) {
			if (window.innerWidth <= 600) {
				// If nav is open and click is outside nav and menuBtn
				if (topNav.style.right === '0px' && !topNav.contains(e.target) && e.target !== menuBtn) {
					topNav.style.right = '-250px';
				}
			}
		});
	}
	// Lock switch AJAX toggle
	const lockSwitch = document.getElementById('lock-switch');
	if (lockSwitch) {
		lockSwitch.addEventListener('change', function() {
			const studentId = this.getAttribute('data-student-id');
			fetch('/student/toggle-lock', {
				method: 'POST',
				headers: {
					'Content-Type': 'application/json',
					'X-CSRF-TOKEN': document.querySelector('meta[name="csrf-token"]').getAttribute('content')
				},
				body: JSON.stringify({ student_id: studentId })
			})
			.then(response => response.json())
			.then(data => {
				if (!data.success) {
					alert('Failed to update lock status: ' + data.message);
					// Revert switch state
					lockSwitch.checked = !lockSwitch.checked;
				} else {
					// Optionally show a message or update UI
					// alert(data.message);
				}
			})
			.catch(() => {
				alert('Error updating lock status.');
				lockSwitch.checked = !lockSwitch.checked;
			});
		});
	}
});

// Alert window animation: slide down, stay, fade out
window.addEventListener('DOMContentLoaded', function() {
	const alertWindow = document.querySelector('.alert-window');
	if (alertWindow && alertWindow.innerHTML.trim() !== '') {
		alertWindow.classList.add('active');
		// Show for 2 seconds, then fade out on 3rd second
		setTimeout(() => {
			alertWindow.classList.add('fade-out');
			// After fade-out animation, hide the alert
			setTimeout(() => {
				alertWindow.classList.remove('active', 'fade-out');
				alertWindow.style.display = 'none';
			}, 500); // match fade-out duration in CSS
		}, 2500); // 2s visible + 0.5s slide = 2.5s, then fade
	}
});

// Payment method sliding logic
document.addEventListener('DOMContentLoaded', function() {
	const movingWindow = document.querySelector('.moving-window');
	const visaBtn = document.getElementById('visa-btn');
	const mastercardBtn = document.getElementById('mastercard-btn');
	const gcashBtn = document.getElementById('gcash-btn');
	const btns = [visaBtn, mastercardBtn, gcashBtn];

	function setActiveBtn(activeBtn) {
		btns.forEach(btn => {
			if (btn) btn.classList.remove('active');
		});
		if (activeBtn) activeBtn.classList.add('active');
	}

	if (movingWindow && visaBtn && mastercardBtn && gcashBtn) {
		visaBtn.addEventListener('click', function() {
			movingWindow.classList.add('visa-active');
			movingWindow.classList.remove('mastercard-active', 'gcash-active');
			setActiveBtn(visaBtn);
		});
		mastercardBtn.addEventListener('click', function() {
			movingWindow.classList.add('mastercard-active');
			movingWindow.classList.remove('visa-active', 'gcash-active');
			setActiveBtn(mastercardBtn);
		});
		gcashBtn.addEventListener('click', function() {
			movingWindow.classList.add('gcash-active');
			movingWindow.classList.remove('visa-active', 'mastercard-active');
			setActiveBtn(gcashBtn);
		});
		// Set initial active button
		setActiveBtn(visaBtn);
	}
});

// Shopping Cart Functionality
document.addEventListener('DOMContentLoaded', function() {
	const cartToggleBtn = document.querySelector('.cart-toggle-btn');
	const cartSidebar = document.querySelector('.cart-sidebar');
	const cartOverlay = document.querySelector('.cart-overlay');
	const cartCloseBtn = document.querySelector('.cart-close-btn');
	const cartItemsContainer = document.querySelector('.cart-items');
	const totalAmountDisplay = document.querySelector('.total-amount');
	const cartCountDisplay = document.querySelector('.cart-count');
	const checkoutBtn = document.querySelector('.checkout-btn');

	// Initialize cart from localStorage
	let cart = JSON.parse(localStorage.getItem('smartpayCart')) || [];

	// Update cart display
	function updateCartDisplay() {
		const itemCount = cart.reduce((total, item) => total + item.quantity, 0);
		cartCountDisplay.textContent = itemCount;

		// Update total amount
		const total = cart.reduce((sum, item) => sum + (item.price * item.quantity), 0);
		totalAmountDisplay.textContent = '₱' + total.toFixed(2);

		// Update cart items HTML
		if (cart.length === 0) {
			cartItemsContainer.innerHTML = '<div class="cart-empty"><p>Your cart is empty</p></div>';
			checkoutBtn.disabled = true;
		} else {
			let itemsHTML = '';
			cart.forEach((item, index) => {
				itemsHTML += `
					<div class="cart-item">
						<div class="cart-item-image">
    						${item.image ? `<img src="/${item.image}" alt="${item.name}">` : '<div style="background-color: #f5f5f5; width: 100%; height: 100%;"></div>'}
						</div>
						<div class="cart-item-details">
							<div class="cart-item-name">${item.name}</div>
							<div class="cart-item-price">₱${item.price.toFixed(2)}</div>
							<div class="cart-item-controls">
								<button class="quantity-btn decrease-qty" data-index="${index}">−</button>
								<span class="quantity-display">${item.quantity}</span>
								<button class="quantity-btn increase-qty" data-index="${index}">+</button>
								<button class="remove-btn" data-index="${index}">Remove</button>
							</div>
						</div>
					</div>
				`;
			});
			cartItemsContainer.innerHTML = itemsHTML;

			// Attach event listeners to quantity buttons
			document.querySelectorAll('.decrease-qty').forEach(btn => {
				btn.addEventListener('click', function() {
					const index = this.dataset.index;
					if (cart[index].quantity > 1) {
						cart[index].quantity--;
					} else {
						cart.splice(index, 1);
					}
					saveCart();
					updateCartDisplay();
				});
			});

			document.querySelectorAll('.increase-qty').forEach(btn => {
				btn.addEventListener('click', function() {
					const index = this.dataset.index;
					const item = cart[index];
					
					// Get available stock from the DOM (stored as data attribute)
					const stockInfo = this.parentElement.querySelector('[data-available-stock]');
					const availableStock = stockInfo ? parseInt(stockInfo.dataset.availableStock) : 999;
					
					if (item.quantity < availableStock) {
						item.quantity++;
						saveCart();
						updateCartDisplay();
					} else {
						alert('Cannot add more items. Maximum available stock: ' + availableStock);
					}
				});
			});

			document.querySelectorAll('.remove-btn').forEach(btn => {
				btn.addEventListener('click', function() {
					const index = this.dataset.index;
					cart.splice(index, 1);
					saveCart();
					updateCartDisplay();
				});
			});

			checkoutBtn.disabled = false;
		}

		// Update checkout button state after display update
		if (typeof updateCheckoutButtonState === 'function') {
			updateCheckoutButtonState();
		}
	}

	// Save cart to localStorage
	function saveCart() {
		localStorage.setItem('smartpayCart', JSON.stringify(cart));
	}

	// Toggle cart sidebar
	cartToggleBtn.addEventListener('click', function() {
		cartSidebar.classList.toggle('active');
		cartOverlay.classList.toggle('active');
	});

	// Close cart when close button or overlay is clicked
	cartCloseBtn.addEventListener('click', function() {
		cartSidebar.classList.remove('active');
		cartOverlay.classList.remove('active');
	});

	cartOverlay.addEventListener('click', function() {
		cartSidebar.classList.remove('active');
		cartOverlay.classList.remove('active');
	});

	// Get modal elements
	const checkoutModal = document.querySelector('.checkout-modal');
	const modalOverlay = document.querySelector('.modal-overlay');
	const modalCloseBtn = document.querySelector('.modal-close-btn');
	const modalCancelBtn = document.querySelector('.modal-cancel-btn');
	const modalConfirmBtn = document.querySelector('.modal-confirm-btn');
	const modalItemsList = document.getElementById('modal-items-list');
	const modalTotalAmount = document.querySelector('.modal-total-amount');
	const modalBalanceAmount = document.querySelector('.modal-balance-amount');

	// Function to get current student balance
	async function getStudentBalance() {
		try {
			const response = await fetch('/student/balance', {
				method: 'GET',
				headers: {
					'X-Requested-With': 'XMLHttpRequest',
					'Accept': 'application/json'
				}
			});

			if (!response.ok) {
				console.warn('Balance fetch returned status:', response.status);
				return 0;
			}

			const data = await response.json();
			console.log('Balance data from server:', data);
			return parseFloat(data.balance) || 0;
		} catch (error) {
			console.error('Error fetching balance:', error);
			return 0;
		}
	}

	// Function to open checkout modal with cart summary
	async function openCheckoutModal() {
		if (cart.length === 0) return;

		const total = cart.reduce((sum, item) => sum + (item.price * item.quantity), 0);

		// Populate modal items
		modalItemsList.innerHTML = cart.map((item, index) => `
			<div class="modal-item">
				<div>
					<div class="modal-item-name">${item.name}</div>
					<div class="modal-item-qty">Qty: ${item.quantity}</div>
				</div>
				<div class="modal-item-price">₱${(item.price * item.quantity).toFixed(2)}</div>
			</div>
		`).join('');

		// Update total
		modalTotalAmount.textContent = `₱${total.toFixed(2)}`;

		// Get and update balance BEFORE showing modal
		const balance = await getStudentBalance();
		console.log('Setting balance to:', balance);
		modalBalanceAmount.textContent = `₱${balance.toFixed(2)}`;

		// Show modal
		checkoutModal.classList.add('active');
		modalOverlay.classList.add('active');
	}

	// Function to close modal
	function closeCheckoutModal() {
		checkoutModal.classList.remove('active');
		modalOverlay.classList.remove('active');
	}

	// Modal close button
	modalCloseBtn.addEventListener('click', function() {
		closeCheckoutModal();
	});

	// Modal cancel button
	modalCancelBtn.addEventListener('click', function() {
		closeCheckoutModal();
	});

	// Close modal when overlay is clicked
	modalOverlay.addEventListener('click', function(e) {
		if (e.target === modalOverlay) {
			closeCheckoutModal();
		}
	});

	// Function to perform actual checkout (submit to backend)
	async function performCheckout() {
		if (cart.length === 0) return;

		checkoutBtn.disabled = true;
		modalConfirmBtn.disabled = true;
		modalConfirmBtn.textContent = 'Processing...';

		// Prepare cart data for submission
		const checkoutData = {
			items: cart.map(item => ({
				id: item.id,
				quantity: item.quantity
			}))
		};

		// Get CSRF token from meta tag
		const csrfToken = document.querySelector('meta[name="csrf-token"]').content;

		try {
			const response = await fetch('/student/checkout', {
				method: 'POST',
				headers: {
					'Content-Type': 'application/json',
					'X-CSRF-TOKEN': csrfToken
				},
				body: JSON.stringify(checkoutData)
			});

			const data = await response.json();

			if (data.success) {
				// Clear cart on successful checkout
				cart = [];
				saveCart();
				updateCartDisplay();

				// Close cart sidebar and modal
				cartSidebar.classList.remove('active');
				cartOverlay.classList.remove('active');
				closeCheckoutModal();

				// Show success message
				alert(`✓ ${data.message}\n\nOrder Amount: ₱${data.total_amount.toFixed(2)}\nStatus: ${data.status}`);
			} else {
				// Show error message
				alert(`✗ ${data.message}`);
				checkoutBtn.disabled = false;
				modalConfirmBtn.disabled = false;
				modalConfirmBtn.textContent = 'Confirm Order';
			}
		} catch (error) {
			console.error('Checkout error:', error);
			alert('An error occurred during checkout. Please try again.');
			checkoutBtn.disabled = false;
			modalConfirmBtn.disabled = false;
			modalConfirmBtn.textContent = 'Confirm Order';
		}
	}

	// Modal confirm button
	modalConfirmBtn.addEventListener('click', function() {
		performCheckout();
	});

	// Checkout button - opens modal instead of direct checkout
	checkoutBtn.addEventListener('click', function(e) {
		e.preventDefault();
		openCheckoutModal();
	});

	// Function to update button state based on balance
	async function updateCheckoutButtonState() {
		const balance = await getStudentBalance();
		const total = cart.reduce((sum, item) => sum + (item.price * item.quantity), 0);

		if (balance < total && cart.length > 0) {
			checkoutBtn.textContent = 'Insufficient Balance';
			checkoutBtn.disabled = true;
		} else if (cart.length === 0) {
			checkoutBtn.textContent = 'Your Cart is Empty';
			checkoutBtn.disabled = true;
		} else {
			checkoutBtn.textContent = 'Proceed to Checkout';
			checkoutBtn.disabled = false;
		}
	}

	// Update button state initially and whenever cart changes
	updateCheckoutButtonState();
	// Re-check balance every 2 seconds to ensure up-to-date status
	setInterval(updateCheckoutButtonState, 2000);

	// Expose addToCart function globally for use in product pages
	window.addToCart = function(productId, productName, price, imagePath) {
		const existingItem = cart.find(item => item.id === productId);

		if (existingItem) {
			existingItem.quantity++;
		} else {
			cart.push({
				id: productId,
				name: productName,
				price: parseFloat(price),
				image: imagePath,
				quantity: 1
			});
		}

		saveCart();
		updateCartDisplay();

		// Show cart to user
		cartSidebar.classList.add('active');
		cartOverlay.classList.add('active');
	};

	// Initial display
	updateCartDisplay();
});

