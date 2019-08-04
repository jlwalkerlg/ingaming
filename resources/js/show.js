import '../sass/style.sass';

import './modules/siteNav';

import alertMsg from './lib/alertMsg';
import { setCartValues } from './modules/cart';
import { getCsrfToken, setCsrfToken } from './lib/csrf';

window.addEventListener('DOMContentLoaded', () => {
  const showHead = document.querySelector('#showHead');
  showHead.style.backgroundImage = `url('${showHead.dataset.bgImg}')`;
});

window.addEventListener('DOMContentLoaded', () => {
  function addProductToCart(e) {
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

  function handleBuyNow(e) {
    addProductToCart(e).then(() => {
      window.location = '/cart';
    });
  }

  const addToBasketBtn = document.querySelector('#addToBasketBtn');
  const buyNowBtn = document.querySelector('#buyNowBtn');

  addToBasketBtn.addEventListener('click', addProductToCart);
  buyNowBtn.addEventListener('click', handleBuyNow);
});
