function validateForm() {
    let title = document.getElementById('inputTitle').value;
    let feedback = document.getElementById('titleFeedback');
    if (title.length < 3 && title.length > 0) {
        feedback.style.display = 'block';
        document.getElementById('inputTitle').classList.add('is-invalid');
        return false;
    } else {
        feedback.style.display = 'none';
        document.getElementById('inputTitle').classList.remove('is-invalid');
        return true;
    }
}

document.addEventListener("DOMContentLoaded", function () {
    let form = document.getElementById('searchForm');
    form.onsubmit = validateForm;
});