/**
 * Shipping availability + radio logic
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

  if (normalizedPath.match(/\/cart(\/|$)/)) {
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
        ? ['freight']
        : ['standard', 'express'];
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

    const getCartTypeFromDOM = () => {
      const summary = document.querySelector('.order-summary');
      return summary?.dataset.cartType || 'express';
    };

    window.EasyCartShipping = {
      getSelectedShippingMethod,
      calculateShippingCost,
      getAllowedShippingMethods,
      setCartTypeInDOM,
      applyShippingAvailability,
      getCartTypeFromDOM
    };

    const initialCartType = getCartTypeFromDOM();
    const initialShipping = document.querySelector('input[name="shipping"]:checked')?.value
      || document.querySelector('.order-summary')?.dataset.shippingMethod
      || 'standard';
    applyShippingAvailability(initialCartType, initialShipping);

    const shippingRadios = document.querySelectorAll('input[name="shipping"]');
    shippingRadios.forEach(radio => {
      radio.addEventListener('change', () => {
        if (window.EasyCartCart?.updateCartTotals) {
          window.EasyCartCart.updateCartTotals();
        }
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
});
