/**
 * EasyCart Phase 3 - Client-side Validations & UI Enhancements
 * Vanilla JS, No Frameworks, Progressive Enhancement
 */

document.addEventListener('DOMContentLoaded', function() {
  
  // ==========================================
  // 1. GLOBAL HELPER FUNCTIONS
  // ==========================================
  
  const showInputError = (input, message) => {
    // Check if error already exists
    let existingError = input.parentElement.querySelector('.error-msg');
    if (existingError) {
      existingError.textContent = message;
    } else {
      const errorDiv = document.createElement('div');
      errorDiv.className = 'error-msg';
      errorDiv.style.color = 'red';
      errorDiv.style.fontSize = '0.85rem';
      errorDiv.style.marginTop = '4px';
      errorDiv.textContent = message;
      input.parentElement.appendChild(errorDiv);
    }
    input.style.borderColor = 'red';
  };

  const clearInputError = (input) => {
    let existingError = input.parentElement.querySelector('.error-msg');
    if (existingError) {
      existingError.remove();
    }
    input.style.borderColor = ''; // Reset to default (managed by CSS)
  };

  const validateEmail = (email) => {
    return /^[^\s@]+@[^\s@]+\.[^\s@]+$/.test(email);
  };

  // ==========================================
  // 2. FORM VALIDATIONS
  // ==========================================

  // --- Login Page ---
  const loginForm = document.querySelector('form[action*="login"]'); 
  // Note: Finding form based on context if explicit ID is missing, but login.php has simple structure. 
  // Ideally, we'd add IDs, but requirement says "Minimal HTML hooks". 
  // In login.php, the form is just <form>.
  
  if (document.location.pathname.includes('login.php')) {
    const forms = document.querySelectorAll('form');
    // The login form is likely the first or second form (search form is in header)
    // We target the one in 'main' or with specific fields.
    const mainLoginForm = document.querySelector('.auth-container form');
    
    if (mainLoginForm) {
      mainLoginForm.addEventListener('submit', function(e) {
        let isValid = true;
        const emailInput = mainLoginForm.querySelector('input[type="email"]');
        const passInput = mainLoginForm.querySelector('input[type="password"]');

        clearInputError(emailInput);
        clearInputError(passInput);

        if (!emailInput.value.trim()) {
          showInputError(emailInput, 'Email is required');
          isValid = false;
        } else if (!validateEmail(emailInput.value.trim())) {
          showInputError(emailInput, 'Please enter a valid email');
          isValid = false;
        }

        if (!passInput.value.trim()) {
          showInputError(passInput, 'Password is required');
          isValid = false;
        }

        if (!isValid) {
           e.preventDefault();
        } else {
           e.preventDefault();
             window.location.href = 'index.php';
        }

      });
    }
  }

  // --- Signup Page ---
  if (document.location.pathname.includes('signup.php')) {
    const signupForm = document.querySelector('.auth-container form');
    if (signupForm) {
      signupForm.addEventListener('submit', function(e) {
        let isValid = true;
        const nameIn = signupForm.querySelector('input[id*="name"]'); // roughly match
        const emailIn = signupForm.querySelector('input[type="email"]');
        const passIn = signupForm.querySelectorAll('input[type="password"]')[0];
        const confirmPassIn = signupForm.querySelectorAll('input[type="password"]')[1];

        [nameIn, emailIn, passIn, confirmPassIn].forEach(i => i && clearInputError(i));

        if (!nameIn.value.trim()) {
            showInputError(nameIn, 'Name is required');
            isValid = false;
        }

        if (!emailIn.value.trim() || !validateEmail(emailIn.value.trim())) {
            showInputError(emailIn, 'Valid email is required');
            isValid = false;
        }

        if (passIn.value.length < 6) {
            showInputError(passIn, 'Password must be at least 6 characters');
            isValid = false;
        }

        if (passIn.value !== confirmPassIn.value) {
            showInputError(confirmPassIn, 'Passwords do not match');
            isValid = false;
        }

        if (!isValid) {
              e.preventDefault();
        } else {
              e.preventDefault();
              window.location.href = 'login.php';
        }

      });
    }
  }

  // --- Checkout Page Validations ---
  if (document.location.pathname.includes('checkout.php')) {
    const checkoutForm = document.querySelector('.page-container form'); // First form is address usually
    // checkout.php has multiple forms. Address is the first one inside .page-container div > div > form
    
    if (checkoutForm) {
        checkoutForm.addEventListener('submit', function(e) {
            // This form might not actually "submit" to anywhere in the vanilla PHP given (it just has inputs),
            // but if it were to submit, we validate. 
            // WAIT - scanning checkout.php... the address form doesn't have a submit button! 
            // It just holds values. The REAL submit is "Place Order" link at the bottom... which is just an <a> tag?
            // "checkout.php": <a href="orders.php" class="btn ...">Place Order</a>
            // Ah, the user might expect the "Place Order" button to validate the fields first.
            // As per constraints "DO NOT rewrite PHP business logic", I can't easily turn the link into a form submit that POSTs data.
            // BUT, the prompt says "Prevent submit when invalid".
            // Since the existing code is just an <a> tag, I should intercept that click.
            
            // NOTE: The address form inputs don't go anywhere in the original code (no <form action=...>), 
            // so we'll just validate visual inputs when the user tries to click "Place Order".
        });
        
        const placeOrderBtn = document.querySelector('a[href="orders.php"]');
        if (placeOrderBtn) {
            placeOrderBtn.addEventListener('click', function(e) {
                const addressForm = document.querySelector('.page-container form');
                if (!addressForm) return;

                let isValid = true;
                const requiredInputs = addressForm.querySelectorAll('input[type="text"], input[type="email"], input[type="tel"]');
                
                requiredInputs.forEach(input => {
                    clearInputError(input);
                    if (!input.value.trim()) {
                        showInputError(input, 'This field is required');
                        isValid = false;
                    }
                });

                // Specific email validation
                const email = addressForm.querySelector('input[type="email"]');
                if (email && email.value.trim() && !validateEmail(email.value.trim())) {
                    showInputError(email, 'Invalid email');
                    isValid = false;
                }

                if (!isValid) {
                    e.preventDefault();
                    window.scrollTo({ top: addressForm.offsetTop - 50, behavior: 'smooth' });
                }
            });
        }
    }
  }


  // ==========================================
  // 3. CART UI
  // ==========================================
  if (document.location.pathname.includes('cart.php')) {
    const cartTable = document.querySelector('.cart-table');
    
    if (cartTable) {
        // A. Inject + / - Buttons
        const qtyInputs = cartTable.querySelectorAll('input[type="number"]');
        qtyInputs.forEach(input => {
            const wrapper = document.createElement('div');
            wrapper.style.display = 'flex';
            wrapper.style.alignItems = 'center';
            wrapper.style.gap = '5px';
            
            const btnMinus = document.createElement('button');
            btnMinus.type = 'button';
            btnMinus.textContent = '−';
            btnMinus.className = 'qty-btn minus';
            btnMinus.style.cssText = 'width: 24px; height: 24px; border: 1px solid #ccc; background: #fff; cursor: pointer; border-radius: 4px;';
            
            const btnPlus = document.createElement('button');
            btnPlus.type = 'button';
            btnPlus.textContent = '+';
            btnPlus.className = 'qty-btn plus';
            btnPlus.style.cssText = 'width: 24px; height: 24px; border: 1px solid #ccc; background: #fff; cursor: pointer; border-radius: 4px;';
            
            input.parentNode.insertBefore(wrapper, input);
            wrapper.appendChild(btnMinus);
            wrapper.appendChild(input); // Move input inside
            wrapper.appendChild(btnPlus);
            
            // Events
            btnMinus.addEventListener('click', () => {
                let val = parseInt(input.value) || 0;
                if (val > 1) {
                    input.value = val - 1;
                    updateCartTotals();
                }
            });
            
            btnPlus.addEventListener('click', () => {
                let val = parseInt(input.value) || 0;
                if (val < 999) {
                    input.value = val + 1;
                    updateCartTotals();
                }
            });
            
            input.addEventListener('input', updateCartTotals);
        });

        // B. Instant Remove (AJAX)
        const deleteBtns = cartTable.querySelectorAll('button[name="delete_product"]');
        deleteBtns.forEach(btn => {
            btn.addEventListener('click', function(e) {
                e.preventDefault();
                
                // Visual feedback
                const row = btn.closest('tr');
                row.style.opacity = '0.5';
                
                // Create form data for AJAX (simulating the form submit)
                const form = btn.closest('form');
                const formData = new FormData(form);
                formData.append('delete_product', '1'); // Ensure the trigger name is sent
                
                fetch('cart.php', {
                    method: 'POST',
                    body: formData
                })
                .then(response => {
                    if (response.ok) {
                        // Remove row visually
                        row.remove();
                        // Check if cart is empty
                        if (cartTable.querySelectorAll('tbody tr').length === 0) {
                            location.reload(); // Simplest way to show "empty cart" message
                        } else {
                            updateCartTotals();
                        }
                    }
                })
                .catch(err => {
                    console.error('Error removing item:', err);
                    row.style.opacity = '1';
                    alert('Failed to remove item. Please try again.');
                });
            });
        });

        // C. Live Totals Calculation
        function updateCartTotals() {
            let subtotal = 0;
            const rows = cartTable.querySelectorAll('tbody tr');
            
            rows.forEach(row => {
                const priceCell = row.cells[2]; // Price is 3rd col
                const totalCell = row.cells[3]; // Total is 4th col
                const qtyInput = row.querySelector('input[type="number"]');
                
                const priceMatch = priceCell.textContent.match(/(\d+\.?\d*)/);
                const price = priceMatch ? parseFloat(priceMatch[0]) : 0;
                const qty = parseInt(qtyInput.value) || 0;
                
                const rowTotal = price * qty;
                totalCell.textContent = '₹' + rowTotal;
                
                subtotal += rowTotal;
            });
            
            // Update Summary Area
            // We need to find the summary elements. Since they don't have IDs in original code,
            // we have to find them by text content or structure.
            const summaryRows = document.querySelectorAll('.summary-row');
            if (summaryRows.length >= 5) {
                // 1. Subtotal
                updateSummaryRow(summaryRows[0], subtotal);
                
                // 2. Delivery (Parse existing, logic might vary but let's assume it stays same if not dynamic)
                let delivery = 49; 
                const deliveryText = summaryRows[1].lastElementChild.textContent;
                const deliveryMatch = deliveryText.match(/(\d+\.?\d*)/);
                if (deliveryMatch) delivery = parseFloat(deliveryMatch[0]);

                if (subtotal >= 500) {
                    delivery = 0; // "Free delivery on orders above 500" logic from footer
                }
                summaryRows[1].lastElementChild.textContent = '₹' + delivery;

                // 3. Subtotal before tax
                const subBeforeTax = subtotal + delivery;
                updateSummaryRow(summaryRows[2], subBeforeTax);
                
                // 4. GST 5%
                const gst = subBeforeTax * 0.05;
                updateSummaryRow(summaryRows[3], gst, true);
                
                // 5. Total
                const total = subBeforeTax + gst;
                updateSummaryRow(summaryRows[4], total, true);
            }
        }
        
        function updateSummaryRow(rowElement, amount, isFormatted = false) {
            const span = rowElement.lastElementChild;
            span.textContent = '₹' + (isFormatted ? amount.toFixed(2) : amount);
        }
    }
  }


  // ==========================================
  // 4. CHECKOUT DELIVERY HIGHLIGHT
  // ==========================================
  if (document.location.pathname.includes('checkout.php')) {
    const deliveryForm = document.querySelectorAll('.page-container form')[1]; // Second form
    if (deliveryForm) {
        const radios = deliveryForm.querySelectorAll('input[type="radio"]');
        
        // Helper to update style
        const updateHighlight = () => {
            radios.forEach(radio => {
                // Find the parent label or container
                // Structure: .form-group > label > [input] [span]
                const label = radio.closest('label');
                const container = radio.closest('.form-group');
                
                if (radio.checked) {
                    container.style.backgroundColor = '#eff6ff'; // Light blue
                    container.style.borderColor = '#2563eb'; // Blue
                    container.style.border = '1px solid #2563eb';
                    container.style.borderRadius = '6px';
                    container.style.padding = '5px'; // Add some padding hooks
                } else {
                    container.style.backgroundColor = '';
                    container.style.borderColor = '';
                    container.style.border = '';
                    container.style.padding = '';
                }
            });
        };
        
        // Init
        updateHighlight();
        
        // Listeners
        radios.forEach(r => r.addEventListener('change', updateHighlight));
    }
  }


  // ==========================================
  // 5. PRODUCT DETAIL GALLERY
  // ==========================================
  if (document.location.pathname.includes('product-detail.php')) {
    const mainImgDiv = document.querySelector('.product-detail-image');
    const mainImg = mainImgDiv ? mainImgDiv.querySelector('img') : null;
    
    if (mainImg) {
        // Create thumbnail container
        const thumbContainer = document.createElement('div');
        thumbContainer.className = 'thumbnails';
        thumbContainer.style.display = 'flex';
        thumbContainer.style.gap = '10px';
        thumbContainer.style.marginTop = '15px';
        thumbContainer.style.justifyContent = 'center';
        
        // Add container after image
        mainImgDiv.appendChild(thumbContainer);
        
        // Create dummy thumbnails (clones of current image for demo)
        // Since we don't have extra images in DB
        const sources = [
            mainImg.src, // Original
            mainImg.src, // Valid placeholder 2
            mainImg.src  // Valid placeholder 3
        ];
        
        sources.forEach((src, index) => {
            const thumb = document.createElement('img');
            thumb.src = src;
            thumb.style.width = '60px';
            thumb.style.height = '60px';
            thumb.style.objectFit = 'cover';
            thumb.style.border = index === 0 ? '2px solid #2563eb' : '1px solid #ddd';
            thumb.style.borderRadius = '4px';
            thumb.style.cursor = 'pointer';
            thumb.style.transition = 'all 0.2s';
            
            // Visual diff for demo purposes (optional filters)
            if (index === 1) thumb.style.filter = 'contrast(1.2)';
            if (index === 2) thumb.style.filter = 'brightness(1.1)';

            thumb.addEventListener('click', () => {
                // Update Main
                mainImg.src = src;
                mainImg.style.filter = thumb.style.filter; // Carry over effect for demo
                
                // Update active state
                Array.from(thumbContainer.children).forEach(t => t.style.border = '1px solid #ddd');
                thumb.style.border = '2px solid #2563eb';
            });
            
            thumbContainer.appendChild(thumb);
        });
    }
  }


  // ==========================================
  // 6. PRODUCTS COUNT
  // ==========================================
  if (document.location.pathname.includes('products.php')) {
     const grid = document.querySelector('.products-grid');
     const cards = grid ? grid.querySelectorAll('.product-card') : [];
     const count = cards.length;
     
     // Find where to insert. "products.php:92 <h1 ...>All Products</h1>"
     const title = document.querySelector('.page-title');
     if (title) {
         const span = document.createElement('span');
         span.textContent = ` (Showing ${count} Products)`;
         span.style.fontSize = '1rem';
         span.style.color = '#666';
         span.style.fontWeight = 'normal';
         span.style.marginLeft = '10px';
         title.appendChild(span);
     }
  }

});
