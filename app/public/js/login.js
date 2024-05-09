const container = document.getElementById('container');
const registerBtn = document.getElementById('register');
const loginBtn = document.getElementById('login');

registerBtn.addEventListener('click', () => {
    container.classList.add("active");
});

loginBtn.addEventListener('click', () => {
    container.classList.remove("active");
});

document.addEventListener("DOMContentLoaded", function () {
    let alertExist = document.body.querySelectorAll('.alertError');
    let allAlerts = document.body.querySelectorAll('.alert');

    if (alertExist.length > 0) {
        container.classList.add("active");
    }

    if (allAlerts.length > 0) {
        allAlerts.forEach(function (alert) {
            setTimeout(function () {
                alert.remove();
            }, 4000);
        });
    }
});