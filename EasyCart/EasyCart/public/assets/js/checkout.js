/**
 * Checkout validation + payment toggle
 */

document.addEventListener('DOMContentLoaded', function() {
  const basePath = (window.EASYCART_BASE_PATH || '').replace(/\/$/, '');
  const toUrl = (path) => `${basePath}${path}`;
  const currentPath = document.location.pathname;
  const normalizedPath = basePath && currentPath.startsWith(basePath)
    ? currentPath.slice(basePath.length)
    : currentPath;

  const showInputError = (input, message) => {
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
    input.style.borderColor = '';
  };

  const validateEmail = (email) => {
    return /^[^\s@]+@[^\s@]+\.[^\s@]+$/.test(email);
  };

  if (normalizedPath.match(/\/checkout(\/|$)/)) {
    const checkoutForm = document.querySelector('.page-container form');

    if (checkoutForm) {
      checkoutForm.addEventListener('submit', function(e) {
        // Intentionally left without submit logic (original flow uses link)
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

    const deliveryForm = document.querySelectorAll('.page-container form')[1];
    if (deliveryForm) {
      const radios = deliveryForm.querySelectorAll('input[type="radio"]');

      const updateHighlight = () => {
        radios.forEach(radio => {
          const container = radio.closest('.form-group');

          if (radio.checked) {
            container.style.backgroundColor = '#eff6ff';
            container.style.borderColor = '#2563eb';
            container.style.border = '1px solid #2563eb';
            container.style.borderRadius = '6px';
            container.style.padding = '5px';
          } else {
            container.style.backgroundColor = '';
            container.style.borderColor = '';
            container.style.border = '';
            container.style.padding = '';
          }
        });
      };

      updateHighlight();
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
});
