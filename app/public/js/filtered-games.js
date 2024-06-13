/*
 * Descripción: Utilizo esta función para controlar que no se pueda clicar
 * el botón de Save si no hay ningún check activado.
 *
 * Autor: Rafael Bonilla Lara
 */

function checkSaveButton(modalId) {
    const checkboxes = document.querySelectorAll(`#${modalId} .form-check input[type="checkbox"]`);
    const saveButton = document.querySelector(`#${modalId} .saveButton`);

    let anyChecked = false;
    checkboxes.forEach(function (checkbox) {
        if (checkbox.checked) {
            anyChecked = true;
        }
    });

    saveButton.disabled = !anyChecked;

    return anyChecked;
}