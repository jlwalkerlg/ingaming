import "../sass/style.sass";

import "./modules/siteNav";

import filterCheckboxes from "./modules/filterCheckboxes";

import { debounce } from "./lib/debounce";
import { getCsrfToken, setCsrfToken } from "./lib/csrf";

window.addEventListener("DOMContentLoaded", () => {
  const catLabels = document.querySelectorAll(
    '#resultsFilterForm label[for^="cat"]'
  );
  const catCheckboxes = document.querySelectorAll(
    '#resultsFilterForm input[name="category[]"]'
  );
  const platformLabels = document.querySelectorAll(
    '#resultsFilterForm label[for^="platform"]'
  );
  const platformCheckboxes = document.querySelectorAll(
    '#resultsFilterForm input[name="platform[]"]'
  );

  filterCheckboxes(catCheckboxes, catLabels);
  filterCheckboxes(platformCheckboxes, platformLabels);
});

window.addEventListener("load", () => {
  const form = document.querySelector("#resultsFilterForm");
  const filterToggle = document.querySelector("#resultsFilterToggle");
  const arrow = document.querySelector("#resultsFilterToggle > svg");

  const formScrollHeight = form.scrollHeight;

  let arrowRotated = 0;

  filterToggle.addEventListener("click", toggleForm);

  function toggleForm() {
    // Show/hide form.
    form.classList.toggle("show");
    if (form.classList.contains("show")) {
      form.style.height = `${formScrollHeight}px`;
    } else {
      form.style.height = "0px";
    }
    filterToggle.classList.toggle("show");
    // Rotate arrow on button.
    arrowRotated += 180;
    arrow.style.transform = `rotateZ(${arrowRotated}deg)`;
  }

  const showForm = debounce(() => {
    if (window.innerWidth >= 992) {
      // Reset form.
      form.classList.remove("show");
      form.removeAttribute("style");
      filterToggle.classList.remove("show");
      // Reset arrow rotation.
      arrowRotated = 0;
      arrow.style.transform = `rotateZ(${arrowRotated}deg)`;
    }
  }, 500);

  window.addEventListener("resize", showForm);
  window.addEventListener("orientationchange", showForm);
});

window.addEventListener("DOMContentLoaded", () => {
  const productsList = document.querySelector("#productsList");
  const filterForm = document.querySelector("#resultsFilterForm");

  const sort = document.querySelector("#sort");

  const platforms = document.querySelectorAll('input[id^="platform"]');
  const cats = document.querySelectorAll('input[id^="cat"]');
  const minPrice = document.querySelector("#minPrice");
  const maxPrice = document.querySelector("#maxPrice");
  const minRating = document.querySelector("#minRating");
  const maxRating = document.querySelector("#maxRating");
  const releaseSoon = document.querySelector("#releaseSoon");
  const releaseNow = document.querySelector("#releaseNow");

  let loading;

  filterForm.addEventListener("submit", e => {
    e.preventDefault();

    if (loading) return;

    loading = true;
    fetchResults();
  });

  function fetchResults(e) {
    const data = {
      ...collectFormData(),
      csrf_token: getCsrfToken()
    };

    fetch("/api/games", {
      method: "POST",
      body: JSON.stringify(data)
    })
      .then(res => {
        setCsrfToken(res);
        return res.json();
      })
      .then(games => {
        let html = "";
        games.forEach(game => {
          html += `
            <a href="/games/${game.id}" class="product">
              <img src="/uploads/product_images/product_${game.id}/${
            game.case_img
          }" alt="${game.title}" width="131" height="165">
              <p class="product-title my-1">${game.title}</p>
              <p class="product-release my-1" data-release-date=${
                game.release_date
              }>${displayReleaseDate(game.release_date)}</p>
              <div class="stars">
          `;
          for (let i = 0; i < game.rating; i++) {
            html += `
              <img src="/img/icons/star.svg" alt="star" class="star">
            `;
          }
          html += `
              </div>
              <p class="product-price my-1">£${game.price}</p>
            </a>
          `;
        });
        productsList.innerHTML = html;
        loading = false;
      });
  }

  function collectFormData() {
    const sortParams = sort.value.split("_");
    const order_col = sortParams[0];
    const order_dir = sortParams[1];

    const platformIds = Array.prototype.slice
      .apply(platforms)
      .filter(el => el.checked && el.value !== "all")
      .map(el => el.value);
    const categories = Array.prototype.slice
      .apply(cats)
      .filter(el => el.checked && el.value !== "all")
      .map(el => el.value);
    const min_price = minPrice.value;
    const max_price = maxPrice.value;
    const min_rating = minRating.value;
    const max_rating = maxRating.value;

    const release_date = [releaseSoon, releaseNow]
      .filter(el => el.checked)
      .map(el => el.value);

    const data = {
      platforms: platformIds,
      categories,
      min_price,
      max_price,
      min_rating,
      max_rating,
      release_date,
      order_col,
      order_dir
    };

    return data;
  }

  function displayReleaseDate(releaseDate) {
    let date = new Date(releaseDate);
    let now = new Date();
    return now >= date ? "Out now" : releaseDate;
  }
});

window.addEventListener("DOMContentLoaded", () => {
  const productsList = document.querySelector("#productsList");
  const sort = document.querySelector("#sort");

  const frag = document.createDocumentFragment();

  sort.addEventListener("change", () => {
    const products = Array.prototype.slice.apply(
      document.querySelectorAll(".product")
    );

    sortProducts(products, sort.value);

    products.forEach(product => frag.appendChild(product));

    productsList.innerHTML = "";
    productsList.appendChild(frag);
  });

  function sortProducts(products, order) {
    switch (order) {
      case "price_asc":
        products.sort((a, b) => {
          const priceA = a.querySelector(".product-price").textContent.slice(1);
          const priceB = b.querySelector(".product-price").textContent.slice(1);
          return priceA - priceB;
        });
        return products;
      case "price_desc":
        products.sort((a, b) => {
          const priceA = a.querySelector(".product-price").textContent.slice(1);
          const priceB = b.querySelector(".product-price").textContent.slice(1);
          return priceB - priceA;
        });
        return products;
      case "rating_asc":
        products.sort((a, b) => {
          const ratingA = a.querySelectorAll(".star").length;
          const ratingB = b.querySelectorAll(".star").length;
          return ratingA - ratingB;
        });
        return products;
      case "rating_desc":
        products.sort((a, b) => {
          const ratingA = a.querySelectorAll(".star").length;
          const ratingB = b.querySelectorAll(".star").length;
          return ratingB - ratingA;
        });
        return products;
      case "release_asc":
        products.sort((a, b) => {
          const releaseA = a.querySelector(".product-release").dataset
            .releaseDate;
          const releaseB = b.querySelector(".product-release").dataset
            .releaseDate;
          const timeA = new Date(releaseA);
          const timeB = new Date(releaseB);
          return timeA - timeB;
        });
        return products;
      case "release_desc":
        products.sort((a, b) => {
          const releaseA = a.querySelector(".product-release").dataset
            .releaseDate;
          const releaseB = b.querySelector(".product-release").dataset
            .releaseDate;
          const timeA = new Date(releaseA);
          const timeB = new Date(releaseB);
          return timeB - timeA;
        });
        return products;
      default:
        return products;
    }
  }
});
