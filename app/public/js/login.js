document.addEventListener("DOMContentLoaded", function () {
    let alertExist = document.body.querySelectorAll('.alertError');
    let allAlerts = document.body.querySelectorAll('.alert');
    const container = document.querySelector(".container");

    if (alertExist.length > 0) {
        container.classList.add("sign-up-mode");
    }

    if (allAlerts.length > 0) {
        allAlerts.forEach(function (alert) {
            setTimeout(function () {
                alert.remove();
            }, 6000);
        });
    }
});

const sign_in_btn = document.querySelector("#sign-in-btn");
const sign_up_btn = document.querySelector("#sign-up-btn");
const container = document.querySelector(".container");

sign_up_btn.addEventListener("click", () => {
    container.classList.add("sign-up-mode");
});

sign_in_btn.addEventListener("click", () => {
    container.classList.remove("sign-up-mode");
});