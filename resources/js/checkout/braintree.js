import '../../sass/style.sass';
import '../modules/siteNav';

const form = document.querySelector('#checkoutForm');
const clientToken = form.dataset.clientToken;

braintree.dropin.create(
  {
    authorization: clientToken,
    selector: '#btDropin',
    paypal: {
      flow: 'vault',
    },
  },
  (err, instance) => {
    if (err) {
      console.log('Create Error', err);
      return;
    }
    form.addEventListener('submit', e => {
      e.preventDefault();
      instance.requestPaymentMethod((err, payload) => {
        if (err) {
          console.log('Request Payment Method Error', err);
          return;
        }
        // Add the nonce to the form and submit.
        document.querySelector('#nonce').value = payload.nonce;
        form.submit();
      });
    });
  }
);
