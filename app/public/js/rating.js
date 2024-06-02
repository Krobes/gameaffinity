document.addEventListener("DOMContentLoaded", function () {
    const ratingSelect = document.getElementById('score');

    checkRating(ratingSelect);
    ratingSelect.addEventListener('change', checkRating);
});

function checkRating(ratingSelect) {
    const submitButton = document.getElementById('rate-submit-button');
    submitButton.disabled = ratingSelect.value === '0';
}