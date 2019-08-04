window.addEventListener('DOMContentLoaded', () => {
    const toggle = document.querySelector('#siteNavCartToggle');
    const dropdown = document.querySelector('#siteNavCartDropdown');

    if (toggle) {
      function toggleDropdown() {
        dropdown.classList.toggle('show');
      }

      toggle.addEventListener('click', toggleDropdown);
    }
});
