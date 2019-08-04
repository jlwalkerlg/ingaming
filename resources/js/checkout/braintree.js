import '../../sass/style.sass';
import '../modules/siteNav';

import dropin from 'braintree-web-drop-in';

const form = document.querySelector('#checkoutForm');
const clientToken = form.dataset.clientToken;

console.log(dropin);
console.log(dropin.create);

console.log(
  dropin.create(
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
      console.log(instance, document.querySelector('#btDropin'));
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
  )
);
