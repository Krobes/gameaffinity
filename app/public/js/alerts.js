/*
 * Descripción: Aquí gestiono la eliminación de las alertas para que no se queden en pantalla.
 *
 * Autor: Rafael Bonilla Lara
 */

setTimeout(function () {
    let flashMessage = document.getElementById('flash-success-message');
    if (flashMessage) {
        flashMessage.remove();
    }
}, 3000);

setTimeout(function () {
    let flashMessage = document.getElementById('flash-error-message');
    if (flashMessage) {
        flashMessage.remove();
    }
}, 3000);