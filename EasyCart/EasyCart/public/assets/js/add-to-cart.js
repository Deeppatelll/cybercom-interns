/**
 * Add to cart (AJAX)
 */

document.addEventListener('DOMContentLoaded', function() {
  const basePath = (window.EASYCART_BASE_PATH || '').replace(/\/$/, '');
  const toUrl = (path) => `${basePath}${path}`;

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
