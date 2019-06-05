import {basename} from '../lib/basename';

// Display chosen file name in label, and trigger click on input
// when space down is pressed on label.

window.addEventListener('DOMContentLoaded', () => {
    const labels = document.querySelectorAll('.form-label-file');

    labels.forEach(label => {
        // Store original label text.
        const labelTxt = label.textContent;

        // Store corresponsing input.
        const input = document.querySelector('#' + label.getAttribute('for'));

        // Remove/add file inputs/labels from tab order.
        input.setAttribute('tabindex', '-1');
        label.setAttribute('tabindex', '0');

        // Listen for change on input and display appropriate text in label.
        input.addEventListener('change', () => {
            let name = basename(input.value);
            if (name.length > 0) {
                // Display file name in label.
                label.textContent = name;
            } else {
                // Display original text in label.
                label.textContent = labelTxt;
            }
        });

        // Listen for space bar keydown on label and trigger click on corresponding input.
        label.addEventListener('keydown', (e) => {
            if (e.key === ' ') {
                e.preventDefault();
                input.click();
            }
        });
    });
});
