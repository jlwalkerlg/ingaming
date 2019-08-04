import '../sass/style.sass';

import './modules/siteNav';

import { handleBuyNow } from './modules/cart';

window.addEventListener('DOMContentLoaded', () => {
  const siteHead = document.querySelector('#siteHead');
  siteHead.style.backgroundImage = `url('${siteHead.dataset.bgImg}')`;
});

window.addEventListener('DOMContentLoaded', () => {
  const trailerBtn = document.querySelector('#trailerBtn');
  const siteHeadModal = document.querySelector('#siteHeadModal');
  const modalClose = document.querySelector('#modalClose');
  const modalLastEl = document.querySelector('#modalLastEl');

  trailerBtn.addEventListener('click', () => {
    siteHeadModal.classList.add('show');
    player.playVideo();
    modalClose.focus();
    trapFocus();
  });

  modalClose.addEventListener('click', () => {
    siteHeadModal.classList.remove('show');
    player.pauseVideo();
    untrapFocus();
  });

  function modalCloseHandler(e) {
    if (e.key === 'Tab' && e.shiftKey) {
      e.preventDefault();
    }
  }

  function modalLastElHandler(e) {
    modalClose.focus();
  }

  function trapFocus() {
    modalClose.addEventListener('keydown', modalCloseHandler);
    modalLastEl.addEventListener('focus', modalLastElHandler);
  }

  function untrapFocus() {
    modalClose.removeEventListener('keydown', modalCloseHandler);
    modalLastEl.removeEventListener('focus', modalLastElHandler);
  }

  // Asynchronously load YouTube API JavaScript code...
  // and insert into DOM.
  const tag = document.createElement('script');
  tag.id = 'iframe-demo';
  tag.src = 'https://www.youtube.com/iframe_api';
  const firstScriptTag = document.getElementsByTagName('script')[0];
  firstScriptTag.parentNode.insertBefore(tag, firstScriptTag);

  // Create a new YT player when the script has finished downloading.
  // onYouTubeIframeAPIReady is called automagically, but must be
  // available globally.
  let player;
  window.onYouTubeIframeAPIReady = () => {
    player = new YT.Player('yt-trailer');
  };
});

window.addEventListener('DOMContentLoaded', () => {
  const buyNowBtn = document.querySelector('#buyNowBtn');

  buyNowBtn.addEventListener('click', handleBuyNow);
});
