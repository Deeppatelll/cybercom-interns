/**
 * EasyCart Phase 3 - Client-side Validations & UI Enhancements
 * Vanilla JS, No Frameworks, Progressive Enhancement
 */

document.addEventListener('DOMContentLoaded', function() {
  const basePath = (window.EASYCART_BASE_PATH || '').replace(/\/$/, '');
  const toUrl = (path) => `${basePath}${path}`;
  const currentPath = document.location.pathname;
  const normalizedPath = basePath && currentPath.startsWith(basePath)
    ? currentPath.slice(basePath.length)
    : currentPath;
  
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

  const postCartAction = (payload) => {
    const formData = new FormData();
    Object.keys(payload).forEach(key => formData.append(key, payload[key]));
    formData.append('ajax', '1');

    return fetch(toUrl('/cart'), {
      method: 'POST',
      body: formData
    }).then(res => res.json());
  };

  const updateCartBadge = (count) => {
    const cartLinks = document.querySelectorAll(`a[href="${toUrl('/cart')}"]`);
    cartLinks.forEach(link => {
      let badge = link.querySelector('.cart-badge');
      if (count > 0) {
        if (!badge) {
          badge = document.createElement('span');
          badge.className = 'cart-badge';
          link.appendChild(badge);
        }
        badge.textContent = count;
      } else if (badge) {
        badge.remove();
      }
    });
  };

  // ==========================================
  // 2. FORM VALIDATIONS
  // ==========================================

  // --- Login Page ---
  const loginForm = document.querySelector('form[action*="login"]'); 
  // Note: Finding form based on context if explicit ID is missing, but login.php has simple structure. 
  // Ideally, we'd add IDs, but requirement says "Minimal HTML hooks". 
  // In login.php, the form is just <form>.
  
  if (normalizedPath.match(/\/login(\/|$)/)) {
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
             window.location.href = toUrl('/');
}

      });
    }
  }

  // --- Signup Page ---
  if (normalizedPath.match(/\/signup(\/|$)/)) {
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
              window.location.href = toUrl('/login');
        }

      });
    }
  }

  // --- Checkout Page Validations ---
  if (normalizedPath.match(/\/checkout(\/|$)/)) {
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
        
        const placeOrderBtn = document.querySelector(`a[href="${toUrl('/orders')}"]`);
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
  if (normalizedPath.match(/\/cart(\/|$)/)) {
    const cartTable = document.querySelector('.cart-table');
    
    if (cartTable) {
      // A. Cart helpers
      let qtyUpdateTimer = null;

      const updateSummaryFromServer = (summary) => {
        if (!summary) return;
        const summaryMap = {
          subtotal: summary.subtotal,
          shipping: summary.shipping_cost,
          subtotal_before_tax: summary.subtotal_before_tax,
          gst: summary.gst,
          total: summary.total
        };

        Object.keys(summaryMap).forEach(key => {
          const el = document.querySelector(`[data-summary="${key}"]`);
          if (el) {
            const value = summaryMap[key];
            el.textContent = '₹' + (key === 'subtotal' || key === 'subtotal_before_tax' ? value : value.toFixed(2));
          }
        });
      };

      const updateQuantityAjax = (productId, quantity) => {
        clearTimeout(qtyUpdateTimer);
        qtyUpdateTimer = setTimeout(() => {
          postCartAction({
            action: 'update_qty',
            product_id: productId,
            quantity: quantity
          }).then(data => {
            updateSummaryFromServer(data.summary);
            if (data.cart_type) {
              applyShippingAvailability(data.cart_type, data.shipping_method);
              setCartTypeInDOM(data.cart_type, data.shipping_method);
            }
            if (typeof data.cart_count !== 'undefined') {
              updateCartBadge(data.cart_count);
            }
          }).catch(() => {
            // Silent fail; user can refresh if needed
          });
        }, 300);
      };

      const getSelectedShippingMethod = () => {
        const selected = document.querySelector('input[name="shipping"]:checked');
        return selected ? selected.value : 'standard';
      };

      const calculateShippingCost = (subtotal, method) => {
        switch (method) {
          case 'express':
            return Math.min(80, subtotal * 0.10);
          case 'white_glove':
            return Math.min(150, subtotal * 0.05);
          case 'freight':
            return Math.max(200, subtotal * 0.03);
          case 'standard':
          default:
            return 40;
        }
      };
const getAllowedShippingMethods = (cartType) => {
  return cartType === 'freight'
    ? ['freight']        // force only freight when cart is freight
    : ['standard', 'express'];
};


      const getCartTypeFromDOM = () => {
        const summary = document.querySelector('.order-summary');
        return summary?.dataset.cartType || 'express';
      };

      const deriveCartTypeFromTable = (subtotal) => {
        let hasFreightItem = false;
        const rows = cartTable.querySelectorAll('tbody tr');
        rows.forEach(row => {
          if (row.dataset.shippingType === 'freight') {
            hasFreightItem = true;
          }
        });

        if (hasFreightItem || subtotal > 300) {
          return 'freight';
        }

        return 'express';
      };

      const setCartTypeInDOM = (cartType, shippingMethod) => {
        const summary = document.querySelector('.order-summary');
        if (!summary) return;
        summary.dataset.cartType = cartType;
        if (shippingMethod) {
          summary.dataset.shippingMethod = shippingMethod;
        }
      };

   const applyShippingAvailability = (cartType, preferredShipping) => {

  // FORCE freight when cart becomes freight
  if (cartType === 'freight') {
    const freightRadio = document.querySelector(
      'input[name="shipping"][value="freight"]'
    );
    if (freightRadio) {
      freightRadio.checked = true;
    }
  }

  const allowed = getAllowedShippingMethods(cartType);
  const radios = document.querySelectorAll('input[name="shipping"]');

  radios.forEach(radio => {
    const isAllowed = allowed.includes(radio.value);
    radio.disabled = !isAllowed;

    if (!isAllowed && radio.checked) {
      radio.checked = false;
    }
  });

  // Ensure a valid option is selected
  let selected = document.querySelector('input[name="shipping"]:checked');

  if (!selected || !allowed.includes(selected.value)) {
    const targetValue =
      cartType === 'freight'
        ? 'freight'
        : (allowed.includes(preferredShipping)
            ? preferredShipping
            : allowed[0]);

    const targetRadio = document.querySelector(
      `input[name="shipping"][value="${targetValue}"]`
    );

    if (targetRadio) {
      targetRadio.checked = true;
    }
  }
};


      // B. Inject + / - Buttons
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
                const productId = input.getAttribute('data-product-id');
                updateQuantityAjax(productId, input.value);
              }
            });
            
            btnPlus.addEventListener('click', () => {
              let val = parseInt(input.value) || 0;
              if (val < 999) {
                input.value = val + 1;
                updateCartTotals();
                const productId = input.getAttribute('data-product-id');
                updateQuantityAjax(productId, input.value);
              }
            });
            
            input.addEventListener('input', () => {
              updateCartTotals();
              const productId = input.getAttribute('data-product-id');
              updateQuantityAjax(productId, input.value);
            });
        });

        // B. Instant Remove (AJAX)
        const deleteBtns = cartTable.querySelectorAll('button[name="delete_product"]');
        deleteBtns.forEach(btn => {
            btn.addEventListener('click', function(e) {
                e.preventDefault();

            if (!confirm('Are you sure you want to remove this item from your cart?')) {
              return;
            }
                
                // Visual feedback
                const row = btn.closest('tr');
                row.style.opacity = '0.5';
                
                // Create form data for AJAX (simulating the form submit)
                const form = btn.closest('form');
                const formData = new FormData(form);
                formData.append('delete_product', '1'); // Ensure the trigger name is sent
                
                postCartAction({
                  action: 'delete_item',
                  product_id: btn.getAttribute('data-product-id')
                })
                .then(data => {
                  // Remove row visually
                  row.remove();
                  // Check if cart is empty
                  if (cartTable.querySelectorAll('tbody tr').length === 0) {
                    location.reload();
                  } else {
                    updateCartTotals();
                    updateSummaryFromServer(data.summary);
                    if (data.cart_type) {
                      applyShippingAvailability(data.cart_type, data.shipping_method);
                      setCartTypeInDOM(data.cart_type, data.shipping_method);
                    }
                  }
                  if (typeof data.cart_count !== 'undefined') {
                    updateCartBadge(data.cart_count);
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

            const derivedCartType = deriveCartTypeFromTable(subtotal);
            applyShippingAvailability(derivedCartType, getSelectedShippingMethod());
            setCartTypeInDOM(derivedCartType, document.querySelector('input[name="shipping"]:checked')?.value);
            
            // Update Summary Area
            // We need to find the summary elements. Since they don't have IDs in original code,
            // we have to find them by text content or structure.
            const summaryRows = document.querySelectorAll('.summary-row');
            if (summaryRows.length >= 5) {
                // 1. Subtotal
                updateSummaryRow(summaryRows[0], subtotal);
                
              // 2. Shipping (based on selected method)
              const shippingMethod = getSelectedShippingMethod();
              const shippingCost = calculateShippingCost(subtotal, shippingMethod);
              summaryRows[1].lastElementChild.textContent = '₹' + shippingCost.toFixed(2);

              // 3. Subtotal before tax
              const subBeforeTax = subtotal + shippingCost;
                updateSummaryRow(summaryRows[2], subBeforeTax);
                
              // 4. GST 18%
              const gst = subBeforeTax * 0.18;
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

        // E. Init shipping availability based on server state
        const initialCartType = getCartTypeFromDOM();
        const initialShipping = document.querySelector('input[name="shipping"]:checked')?.value
          || document.querySelector('.order-summary')?.dataset.shippingMethod
          || 'standard';
        applyShippingAvailability(initialCartType, initialShipping);

        // D. Shipping selection updates totals
        const shippingRadios = document.querySelectorAll('input[name="shipping"]');
        shippingRadios.forEach(radio => {
          radio.addEventListener('change', () => {
            updateCartTotals();
            postCartAction({
              action: 'apply_shipping',
              shipping: radio.value
            }).then(data => {
              updateSummaryFromServer(data.summary);
              if (data.cart_type) {
                applyShippingAvailability(data.cart_type, data.shipping_method);
                setCartTypeInDOM(data.cart_type, data.shipping_method);
              }
            }).catch(() => {
              // Silent fail
            });
          });
        });
    }
  }


  // ==========================================
  // 4. CHECKOUT DELIVERY HIGHLIGHT
  // ==========================================
  if (normalizedPath.match(/\/checkout(\/|$)/)) {
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

    const paymentRadios = document.querySelectorAll('input[name="payment_method"]');
    const paymentPanels = document.querySelectorAll('.payment-details');

    const updatePaymentPanels = () => {
      const selected = document.querySelector('input[name="payment_method"]:checked')?.value;
      paymentPanels.forEach(panel => {
        const type = panel.getAttribute('data-payment');
        if (selected && type === selected) {
          panel.classList.remove('hidden');
        } else {
          panel.classList.add('hidden');
        }
      });
    };

    if (paymentRadios.length) {
      paymentRadios.forEach(radio => radio.addEventListener('change', updatePaymentPanels));
      updatePaymentPanels();
    }

    const placeOrderForm = document.querySelector(`form[action="${toUrl('/orders')}"]`);
    if (placeOrderForm) {
      placeOrderForm.addEventListener('submit', (e) => {
        const selected = document.querySelector('input[name="payment_method"]:checked');
        if (!selected) {
          e.preventDefault();
          alert('Please choose a payment method to place the order.');
        }
      });
    }
  }


  // ==========================================
  // 5. PRODUCT DETAIL GALLERY
  // ==========================================


if (normalizedPath.match(/\/product(\/|$)/)) {

  const mainImg = document.querySelector('.product-main-image img');
  const thumbContainer = document.querySelector('.product-thumbnails');

  if (!mainImg || !thumbContainer) return;

  // Clear old thumbnails if JS reloads
  thumbContainer.innerHTML = '';

  const sources = [
    mainImg.src,
    mainImg.src,
    mainImg.src
  ];

  sources.forEach((src, index) => {

    const thumbBox = document.createElement('div');
    thumbBox.className = 'product-thumbnail';

    const thumb = document.createElement('img');
    thumb.src = src;

    if (index === 0) thumbBox.classList.add('active');

    thumbBox.appendChild(thumb);
    thumbContainer.appendChild(thumbBox);

    thumbBox.addEventListener('click', () => {

      mainImg.src = src;

      document
        .querySelectorAll('.product-thumbnail')
        .forEach(t => t.classList.remove('active'));

      thumbBox.classList.add('active');

    });

  });
}


  // ==========================================
  // 6. PRODUCTS COUNT
  // ==========================================
  if (normalizedPath.match(/\/products(\/|$)/)) {
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

  // ==========================================
  // 7. ADD TO CART (AJAX)
  // ==========================================
  const addToCartButtons = document.querySelectorAll('.js-add-to-cart');
  addToCartButtons.forEach(button => {
    button.addEventListener('click', (e) => {
      e.preventDefault();
      const productId = button.getAttribute('data-product-id');
      if (!productId) return;

      button.disabled = true;
      const originalText = button.textContent;
      button.textContent = 'Adding...';

      postCartAction({
        action: 'add_to_cart',
        product_id: productId
      }).then((data) => {
        button.textContent = 'Added ✓';
        if (typeof data.cart_count !== 'undefined') {
          updateCartBadge(data.cart_count);
        }
        setTimeout(() => {
          button.textContent = originalText;
          button.disabled = false;
        }, 1200);
      }).catch(() => {
        button.textContent = originalText;
        button.disabled = false;
        alert('Failed to add item. Please try again.');
      });
    });
  });

  const addToCartForm = document.querySelector('form[data-add-to-cart="true"]');
  if (addToCartForm) {
    addToCartForm.addEventListener('submit', (e) => {
      e.preventDefault();
      const productId = addToCartForm.querySelector('input[name="product_id"]')?.value;
      const submitBtn = addToCartForm.querySelector('button[type="submit"]');
      if (!productId || !submitBtn) return;

      submitBtn.disabled = true;
      const originalText = submitBtn.textContent;
      submitBtn.textContent = 'Adding...';

      postCartAction({
        action: 'add_to_cart',
        product_id: productId
      }).then((data) => {
        submitBtn.textContent = 'Added ✓';
        if (typeof data.cart_count !== 'undefined') {
          updateCartBadge(data.cart_count);
        }
        setTimeout(() => {
          submitBtn.textContent = originalText;
          submitBtn.disabled = false;
        }, 1200);
      }).catch(() => {
        submitBtn.textContent = originalText;
        submitBtn.disabled = false;
        alert('Failed to add item. Please try again.');
      });
    });
  }

});
