import '../../sass/style.sass';
import '../modules/siteNav';

import {
  displayError,
  displayValidationErrors,
  clearValidationErrors
} from '../lib/formHelpers';
import { getCsrfToken, setCsrfToken } from '../lib/csrf';

// Get DOM elements.
const form = document.querySelector('#checkoutForm');

// Get public key from data attribute.
const publicKey = form.dataset.publicKey;

// Initialise stripe.
const stripe = Stripe(publicKey);

// Add stripe card input to form.
const elements = stripe.elements();
const cardElement = elements.create('card');
cardElement.mount('#cardElement');

// Listen for form submission.
form.addEventListener('submit', handleSubmit);

let paymentData;

// Submit form to stripe, then either handle error
// or post payment id to server.
function handleSubmit(e) {
  e.preventDefault();

  const data = getFormData();

  stripe
    .createPaymentMethod('card', cardElement, {
      billing_details: data
    })
    .then(result => {
      if (result.error) {
        // Show error in payment form.
        displayError(result.error.message, form, 'afterbegin');
      } else {
        paymentData = {
          type: result.paymentMethod.type
        };
        if (paymentData.type === 'card') {
          paymentData.card_brand = result.paymentMethod.card.brand;
          paymentData.last4 = result.paymentMethod.card.last4;
        }
        // Send paymentMethod.id to your server (see Step 2).
        fetch('/api/checkout/confirm', {
          method: 'POST',
          headers: {
            'Content-Type': 'application/json'
          },
          body: JSON.stringify({
            ...data,
            ...paymentData,
            payment_method_id: result.paymentMethod.id,
            csrf_token: getCsrfToken()
          })
        })
          .then(response => {
            setCsrfToken(response);
            return response.json();
          })
          .then(response => {
            // Handle server response (see Step 3).
            handleServerResponse(response);
          });
      }
    });
}

// Handle response from server: either an error,
// more action required, or success.
function handleServerResponse(response) {
  if (response.error) {
    if (response.fatal) {
      location = response.location;
    } else if (response.validationErrors) {
      clearValidationErrors(form);
      displayValidationErrors(response.validationErrors);
    } else {
      // Show error from server on payment form.
      displayError(response.error, form, 'afterbegin');
    }
  } else if (response.requires_action) {
    // Use Stripe.js to handle required card action.
    stripe
      .handleCardAction(response.payment_intent_client_secret)
      .then(result => {
        if (result.error) {
          // Show error in payment form.
          displayError(result.error.message, form, 'afterbegin');
        } else {
          // The card action has been handled.
          // The PaymentIntent can be confirmed again on the server.
          const data = getFormData();

          fetch('/api/checkout/confirm', {
            method: 'POST',
            headers: { 'Content-Type': 'application/json' },
            body: JSON.stringify({
              ...data,
              ...paymentData,
              payment_intent_id: result.paymentIntent.id,
              csrf_token: getCsrfToken()
            })
          })
            .then(res => {
              setCsrfToken(res);
              return res.json();
            })
            .then(handleServerResponse);
        }
      });
  } else {
    // Success!
    location = response.location;
  }
}

function getFormData() {
  const name = form.cardName.value;
  const email = form.email.value;
  const city = form['address[city]'].value;
  const country = form['address[country]'].value;
  const postal_code = form['address[postal_code]'].value;
  const line1 = form['address[line1]'].value;
  const line2 = form['address[line2]'].value;
  const address = {
    city,
    country,
    postal_code,
    line1,
    line2
  };

  return {
    name,
    email,
    address
  };
}
