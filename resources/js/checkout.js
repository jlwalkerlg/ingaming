import "../sass/style.sass";

import "./modules/siteNav";

window.addEventListener("DOMContentLoaded", () => {
  // Initialise the payment form.
  const stripe = Stripe("pk_test_Yz7Xdr8O9u4rnQMYDVdgsu6a");

  const elements = stripe.elements();
  const cardElement = elements.create("card");
  cardElement.mount("#card-element");

  // Submit payment on click.
  const cardholderName = document.getElementById("cardholder-name");
  const cardButton = document.getElementById("card-button");
  const clientSecret = cardButton.dataset.secret;

  cardButton.addEventListener("click", e => {
    e.target.setAttribute("disabled", "true");
    stripe
      .handleCardPayment(clientSecret, cardElement, {
        payment_method_data: {
          billing_details: { name: cardholderName.value }
        }
      })
      .then(result => {
        e.target.removeAttribute("disabled");
        if (result.error) {
          // Display error.message in your UI.
          console.log("Error!");
          console.log(result);
        } else {
          // The payment has succeeded. Display a success message.
          console.log("Success!");
          console.log(result);
        }
      });
  });
});
