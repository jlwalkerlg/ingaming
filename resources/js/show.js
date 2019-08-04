import '../sass/style.sass';

import './modules/siteNav';

import { handleAddProductToCart, handleBuyNow } from './modules/cart';

window.addEventListener('DOMContentLoaded', () => {
  const showHead = document.querySelector('#showHead');
  showHead.style.backgroundImage = `url('${showHead.dataset.bgImg}')`;
});

window.addEventListener('DOMContentLoaded', () => {
  const addToBasketBtn = document.querySelector('#addToBasketBtn');
  const buyNowBtn = document.querySelector('#buyNowBtn');

  addToBasketBtn.addEventListener('click', handleAddProductToCart);
  buyNowBtn.addEventListener('click', handleBuyNow);
});
