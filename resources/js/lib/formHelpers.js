export function displayError(msg, el, pos) {
  let html = `<p id="formError" style="color: red;">${msg}</p>`;

  let errorEl = document.querySelector("#formError");

  if (!errorEl) {
    errorEl = document.createElement("div");
  }

  errorEl.innerHTML = html;

  el.insertAdjacentElement(pos, errorEl);
}

export function displayValidationErrors(errors) {
  for (let key in errors) {
    if (errors.hasOwnProperty(key)) {
      const error = errors[key];

      key = parseKey(key);

      const inputEl = document.querySelector(`[name="${key}"]`);
      if (!inputEl) continue;

      let errEl = document.querySelector(`[name="${key}"] + .form-error`);
      if (!errEl) {
        errEl = document.createElement("p");
        errEl.className = "form-error";
      }

      errEl.innerHTML = error;

      inputEl.insertAdjacentElement("afterend", errEl);
    }
  }
}

function parseKey(key) {
  key = key.replace(".", "[");
  key = key.replace(/\./g, "][");
  key = key + "]";
  return key;
}

export function clearValidationErrors(form) {
  const validationErrors = form.querySelectorAll(".form-error");

  for (let i = 0; i < validationErrors.length; i++) {
    const err = validationErrors[i];
    err.remove();
  }
}
