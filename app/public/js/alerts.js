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