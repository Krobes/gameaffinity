/*
 * Descripción: En este archivo se gestiona la función relativa a la búsqueda avanzada.
 * Su cometido es validar que la búsqueda por caracteres esté vacía o tenga más de 3 caracteres,
 * para que la búsqueda afine más. En caso de estar vacía no filtrará por nombre.
 *
 * Autor: Rafael Bonilla Lara
 */

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