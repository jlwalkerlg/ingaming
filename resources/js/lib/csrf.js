export function getCsrfToken() {
  return document
    .querySelector('meta[name="csrf-token"]')
    .getAttribute("content");
}

export function setCsrfToken(res) {
  const token = res.headers.get("XSRF-TOKEN");
  document
    .querySelector('meta[name="csrf-token"]')
    .setAttribute("content", token);
}
