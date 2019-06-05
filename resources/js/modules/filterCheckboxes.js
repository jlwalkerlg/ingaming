export default function filterCheckboxes(domCheckboxes, domLabels) {
  // Labels/checkboxes for each item.
  const labels = Array.prototype.slice.apply(domLabels);
  const checkboxes = Array.prototype.slice.apply(domCheckboxes);

  // Label/checkbox for master option.
  const masterLabel = labels.shift();
  const masterCheckbox = checkboxes.shift();

  // Listen for change to each label.
  checkboxes.forEach(box => {
    box.addEventListener("change", onAllChange);
  });

  // Listen for change to master label.
  masterCheckbox.addEventListener("change", onMasterChange);

  function onAllChange() {
    // Are all checkboxes selected?
    let allChecked = true;
    for (let i = 0; i < checkboxes.length; i++) {
      const box = checkboxes[i];
      if (!box.checked) {
        allChecked = false;
        break;
      }
    }

    if (allChecked) {
      // Mute labels and check master checkbox.
      muteLabels();
      checkMasterCheckbox();
    } else {
      // Unmute labels and uncheck master checkbox.
      unmuteLabels();
      uncheckMasterCheckbox();
    }
  }

  function onMasterChange() {
    if (this.checked) {
      muteLabels();
      checkCheckboxes();
      checkMasterCheckbox();
    } else {
      unmuteLabels();
      uncheckCheckboxes();
      uncheckMasterCheckbox();
    }
  }

  function muteLabels() {
    labels.forEach(label => label.classList.add("text-muted"));
  }

  function unmuteLabels() {
    labels.forEach(label => label.classList.remove("text-muted"));
  }

  function checkCheckboxes() {
    checkboxes.forEach(box => (box.checked = true));
  }

  function uncheckCheckboxes() {
    checkboxes.forEach(box => (box.checked = false));
  }

  function checkMasterCheckbox() {
    masterLabel.classList.remove("text-muted");
    masterCheckbox.checked = true;
  }

  function uncheckMasterCheckbox() {
    masterLabel.classList.add("text-muted");
    masterCheckbox.checked = false;
  }
}
