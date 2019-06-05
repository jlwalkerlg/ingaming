window.addEventListener('DOMContentLoaded', () => {
    const toggle = document.querySelector('#siteNavCartToggle');
    const dropdown = document.querySelector('#siteNavCartDropdown');

    toggle.addEventListener('click', toggleDropdown);

    function toggleDropdown() {
        dropdown.classList.toggle('show');
    }
});
