import alertMsg from '../lib/alertMsg';
import { getCsrfToken, setCsrfToken } from '../lib/csrf';

export function setCartValues(items = null, total = null) {
  if (items !== null) {
    const cartItems = document.querySelector('#cartItems');
    const cartDropdownItems = document.querySelector('#cartDropdownItems');
    cartItems.textContent = items;
    cartDropdownItems.textContent = items;
  }

  if (total !== null) {
    const cartDropdownTotal = document.querySelector('#cartDropdownTotal');
    cartDropdownTotal.textContent = total;
  }
}

export function setCheckoutValues(price = null, subtotal = null) {
  if (price !== null) {
    const checkoutPrice = document.querySelector('#checkoutPrice');
    checkoutPrice.textContent = `£${price}`;
  }
  if (subtotal !== null) {
    const checkoutSubtotal = document.querySelector('#checkoutSubtotal');
    checkoutSubtotal.textContent = `£${subtotal}`;
  }
}

export function handleAddProductToCart(e) {
  const gameId = e.target.dataset.productId;

  const data = {
    csrf_token: getCsrfToken(),
    product_id: gameId,
  };

  return fetch('/api/cart', {
    method: 'POST',
    headers: {
      'Content-Type': 'application/json',
    },
    body: JSON.stringify(data),
  })
    .then(res => {
      setCsrfToken(res);
      return res.json();
    })
    .then(json => {
      alertMsg(json.msg);
      if (json.success && json.cartCount) {
        setCartValues(json.cartCount, json.cartTotal);
      }
    });
}

export function handleBuyNow(e) {
  handleAddProductToCart(e).then(() => {
    window.location = '/cart';
  });
}
