export function setCartValues(items = null, total = null) {
  if (items !== null) {
    const cartItems = document.querySelector("#cartItems");
    const cartDropdownItems = document.querySelector("#cartDropdownItems");
    cartItems.textContent = items;
    cartDropdownItems.textContent = items;
  }

  if (total !== null) {
    const cartDropdownTotal = document.querySelector("#cartDropdownTotal");
    cartDropdownTotal.textContent = total;
  }
}

export function setCheckoutValues(price = null, subtotal = null) {
  if (price !== null) {
    const checkoutPrice = document.querySelector("#checkoutPrice");
    checkoutPrice.textContent = `£${price}`;
  }
  if (subtotal !== null) {
    const checkoutSubtotal = document.querySelector("#checkoutSubtotal");
    checkoutSubtotal.textContent = `£${subtotal}`;
  }
}
