function alertMsg(msg) {
  const siteNav = document.querySelector("#siteNav");
  let alert = document.querySelector("#alert");

  if (!alert) {
    alert = document.createElement("p");
    alert.id = "alert";
  }

  alert.textContent = msg;
  siteNav.insertAdjacentElement("afterend", alert);

  // Restart animation.
  alert.classList.remove("alert");
  void alert.offsetWidth;
  alert.classList.add("alert");
}

export default alertMsg;
