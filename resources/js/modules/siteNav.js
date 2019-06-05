import { debounce } from "../lib/debounce";

import "./showCart";

window.addEventListener("DOMContentLoaded", () => {
  // Store references to DOM elements.
  const siteNavToggle = document.querySelector("#siteNavToggle");
  const siteNavList = document.querySelector("#siteNavList");
  const siteNavLinks = document.querySelectorAll(".site-nav-link");

  // Show/hide nav on small screens.
  function toggleNav() {
    // Make nav visible so the transition can be seen.
    siteNavList.style.visibility = "visible";
    if (siteNavList.classList.contains("show")) {
      // Hide nav.
      siteNavList.classList.remove("show");
      // Untrap keyboard focus from nav.
      untrapFocus();
    } else {
      // Show nav.
      siteNavList.classList.add("show");
      // Trap keyboard focus in nav.
      trapFocus();
    }
  }

  let cycleFocus;

  function trapFocus() {
    let links = [siteNavToggle, ...siteNavLinks];

    let i = 0;

    cycleFocus = function(e) {
      if (e.key === "Tab") {
        e.preventDefault();
        if (!e.shiftKey) {
          if (i === links.length - 1) {
            i = 0;
          } else {
            i++;
          }
        } else {
          if (i === 0) {
            i = links.length - 1;
          } else {
            i--;
          }
        }
        links[i].focus();
      }
    };

    document.addEventListener("keydown", cycleFocus);
  }

  function untrapFocus() {
    document.removeEventListener("keydown", cycleFocus);
  }

  // Hide nav visiblity on small screens when toggled off,
  // so that the links can not recieve keyboard focus while
  // off screen.
  function hideNavSmScreen() {
    if (!this.classList.contains("show")) {
      this.style.visibility = "hidden";
    }
  }

  // Ensure nav is visible on large screens.
  const revealNavMdScreen = debounce(() => {
    if (window.innerWidth >= 768) {
      siteNavList.style.visibility = "visible";
      siteNavList.classList.remove("show");
    }
  }, 500);

  // Attach event listeners.
  siteNavToggle.addEventListener("click", toggleNav);
  siteNavList.addEventListener("transitionend", hideNavSmScreen);
  window.addEventListener("resize", revealNavMdScreen);
  window.addEventListener("orientationchange", revealNavMdScreen);
});
