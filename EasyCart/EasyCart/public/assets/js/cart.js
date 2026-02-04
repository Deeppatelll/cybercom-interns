/**
 * Cart UI: quantity updates, delete item, totals
 */

document.addEventListener('DOMContentLoaded', function() {
  const basePath = (window.EASYCART_BASE_PATH || '').replace(/\/$/, '');
  const toUrl = (path) => `${basePath}${path}`;
  const currentPath = document.location.pathname;
  const normalizedPath = basePath && currentPath.startsWith(basePath)
    ? currentPath.slice(basePath.length)
    : currentPath;

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

  if (normalizedPath.match(/\/cart(\/|$)/)) {
    const cartTable = document.querySelector('.cart-table');

    if (cartTable) {
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
              if (window.EasyCartShipping?.applyShippingAvailability) {
                window.EasyCartShipping.applyShippingAvailability(data.cart_type, data.shipping_method);
              }
              if (window.EasyCartShipping?.setCartTypeInDOM) {
                window.EasyCartShipping.setCartTypeInDOM(data.cart_type, data.shipping_method);
              }
            }
            if (typeof data.cart_count !== 'undefined') {
              updateCartBadge(data.cart_count);
            }
          }).catch(() => {
            // Silent fail
          });
        }, 300);
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

      const getSelectedShippingMethod = () => {
        if (window.EasyCartShipping?.getSelectedShippingMethod) {
          return window.EasyCartShipping.getSelectedShippingMethod();
        }
        const selected = document.querySelector('input[name="shipping"]:checked');
        return selected ? selected.value : 'standard';
      };

      const calculateShippingCost = (subtotal, method) => {
        if (window.EasyCartShipping?.calculateShippingCost) {
          return window.EasyCartShipping.calculateShippingCost(subtotal, method);
        }
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

      const applyShippingAvailability = (cartType, preferredShipping) => {
        if (window.EasyCartShipping?.applyShippingAvailability) {
          window.EasyCartShipping.applyShippingAvailability(cartType, preferredShipping);
        }
      };

      const setCartTypeInDOM = (cartType, shippingMethod) => {
        if (window.EasyCartShipping?.setCartTypeInDOM) {
          window.EasyCartShipping.setCartTypeInDOM(cartType, shippingMethod);
        }
      };

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
        wrapper.appendChild(input);
        wrapper.appendChild(btnPlus);

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

      const deleteBtns = cartTable.querySelectorAll('button[name="delete_product"]');
      deleteBtns.forEach(btn => {
        btn.addEventListener('click', function(e) {
          e.preventDefault();

          if (!confirm('Are you sure you want to remove this item from your cart?')) {
            return;
          }

          const row = btn.closest('tr');
          row.style.opacity = '0.5';

          const form = btn.closest('form');
          const formData = new FormData(form);
          formData.append('delete_product', '1');

          postCartAction({
            action: 'delete_item',
            product_id: btn.getAttribute('data-product-id')
          })
            .then(data => {
              row.remove();
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

      function updateCartTotals() {
        let subtotal = 0;
        const rows = cartTable.querySelectorAll('tbody tr');

        rows.forEach(row => {
          const priceCell = row.cells[2];
          const totalCell = row.cells[3];
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

        const summaryRows = document.querySelectorAll('.summary-row');
        if (summaryRows.length >= 5) {
          updateSummaryRow(summaryRows[0], subtotal);

          const shippingMethod = getSelectedShippingMethod();
          const shippingCost = calculateShippingCost(subtotal, shippingMethod);
          summaryRows[1].lastElementChild.textContent = '₹' + shippingCost.toFixed(2);

          const subBeforeTax = subtotal + shippingCost;
          updateSummaryRow(summaryRows[2], subBeforeTax);

          const gst = subBeforeTax * 0.18;
          updateSummaryRow(summaryRows[3], gst, true);

          const total = subBeforeTax + gst;
          updateSummaryRow(summaryRows[4], total, true);
        }
      }

      function updateSummaryRow(rowElement, amount, isFormatted = false) {
        const span = rowElement.lastElementChild;
        span.textContent = '₹' + (isFormatted ? amount.toFixed(2) : amount);
      }

      window.EasyCartCart = window.EasyCartCart || {};
      window.EasyCartCart.updateCartTotals = updateCartTotals;
      window.EasyCartCart.updateSummaryFromServer = updateSummaryFromServer;
      window.EasyCartCart.deriveCartTypeFromTable = deriveCartTypeFromTable;
      window.EasyCartCart.getCartTypeFromDOM = getCartTypeFromDOM;

      const initialCartType = getCartTypeFromDOM();
      const initialShipping = document.querySelector('input[name="shipping"]:checked')?.value
        || document.querySelector('.order-summary')?.dataset.shippingMethod
        || 'standard';
      applyShippingAvailability(initialCartType, initialShipping);
    }
  }
});
