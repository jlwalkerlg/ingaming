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
  const addToBasketBtn = document.querySelector('#addToBasketBtn');

  addToBasketBtn.addEventListener('click', addProductToCart);

  function addProductToCart() {
    const gameId = this.dataset.productId;

    const data = {
      csrf_token: getCsrfToken(),
      product_id: gameId,
    };

    fetch('/api/cart', {
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
});
