import "../sass/style.sass";

import "./modules/siteNav";

import { setCartValues, setCheckoutValues } from "./modules/cart";
import { debounce } from "./lib/debounce";
import { getCsrfToken, setCsrfToken } from "./lib/csrf";
import alertMsg from "./lib/alertMsg";

window.addEventListener("DOMContentLoaded", () => {
  const productsTable = document.querySelector("#productsTable");

  const checkout = document.querySelector("#checkout");
  const checkoutPrice = document.querySelector("#checkoutPrice");
  const checkoutSubtotal = document.querySelector("#checkoutSubtotal");

  const quantities = document.querySelectorAll('input[name="quantity"]');

  quantities.forEach(input => {
    input.addEventListener("input", debounce(handleQtyChange, 500));
  });

  function handleQtyChange(e) {
    const quantity = this.value;

    if (!quantity || quantity < 1) {
      this.classList.add("form-input-error");
      return;
    }
    this.classList.remove("form-input-error");

    this.setAttribute("disabled", "true");

    const productId = this.dataset.productId;

    const data = {
      quantity,
      _method: "PUT",
      csrf_token: getCsrfToken()
    };

    fetch(`/api/cart/product/${productId}`, {
      method: "POST",
      headers: {
        "Content-Type": "application/json"
      },
      body: JSON.stringify(data)
    })
      .then(res => {
        setCsrfToken(res);
        return res.json();
      })
      .then(json => {
        this.removeAttribute("disabled");
        alertMsg(json.msg);
        if (json.success) {
          setCartValues(null, json.cartTotal);
          setCheckoutValues(json.cartTotal, json.cartTotal);
        }
      });
  }

  const deleteForms = document.querySelectorAll(".cart-remove-btn");

  deleteForms.forEach(form => {
    form.addEventListener("submit", deleteProduct);
  });

  function deleteProduct(e) {
    e.preventDefault();

    const confirmed = confirm(
      "Are you sure you want to delete the product from your cart?"
    );
    if (!confirmed) return;

    const productId = this.dataset.productId;
    const productRow = document.querySelector(`#product${productId}`);

    const qty = parseInt(
      productRow.querySelector('input[name="quantity"]').value
    );
    const price = parseFloat(
      productRow.querySelector(".cart-price").textContent.substr(1)
    );
    const productSubtotal = qty * price;

    const data = {
      _method: "DELETE",
      csrf_token: getCsrfToken()
    };

    fetch(`/api/cart/product/${productId}`, {
      method: "POST",
      headers: {
        "Content-Type": "application/json"
      },
      body: JSON.stringify(data)
    })
      .then(res => {
        setCsrfToken(res);
        return res.json();
      })
      .then(json => {
        alertMsg(json.msg);
        if (json.success) {
          productRow.remove();
          setCartValues(json.cartCount, json.cartTotal);
          let checkoutPriceNum = parseFloat(
            checkoutPrice.textContent.substr(1)
          );
          let checkoutSubtotalNum = parseFloat(
            checkoutSubtotal.textContent.substr(1)
          );
          checkoutPriceNum -= productSubtotal;
          checkoutSubtotalNum -= productSubtotal;
          if (checkoutSubtotalNum <= 0) {
            checkout.remove();
            productsTable.insertAdjacentHTML(
              "beforebegin",
              `
              <p class="flex-1">You have no items in your shopping cart. <a href="/games" class="text-link">Get shopping!</a></p>
              `
            );
          } else {
            checkoutPrice.textContent = `£${checkoutPriceNum}`;
            checkoutSubtotal.textContent = `£${checkoutSubtotalNum}`;
          }
        }
      });
  }
});
