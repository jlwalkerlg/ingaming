window.addEventListener('DOMContentLoaded', () => {
    const deleteForms = document.querySelectorAll('.delete-form');

    deleteForms.forEach(form => {
        form.addEventListener('submit', submitDelete);
    });

    function submitDelete(e) {
        let confirmMsg = `Are you sure you wish to delete this game (id: ${this.dataset.gameId})?`;
        if (!confirm(confirmMsg)) {
            e.preventDefault();
        }
    }
});
