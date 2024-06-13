/*
 * Descripción: Archivo en el que gestiono la creación de modales de
 * manera dinámica dependiendo de las listas que que se vayan creando.
 * Además, también se maneja la selección de avatares.
 *
 * Autor: Rafael Bonilla Lara
 */

document.addEventListener("DOMContentLoaded", function () {
    let modalAddPublicList = new bootstrap.Modal(document.getElementById("modalAddPublicList"));
    let modalAddPrivateList = new bootstrap.Modal(document.getElementById("modalAddPrivateList"));
    let buttonModalAddPublicList = document.getElementById("buttonModalAddPublicList");
    let buttonModalAddPrivateList = document.getElementById("buttonModalAddPrivateList");

    buttonModalAddPublicList.addEventListener('click', function () {
        modalAddPublicList.show();
    });

    buttonModalAddPrivateList.addEventListener('click', function () {
        modalAddPrivateList.show();
    });

    let divLists = document.querySelectorAll('.divList');

    divLists.forEach(function (divList) {
        divList.addEventListener('click', function () {

            let modalId = this.getAttribute('data-target');
            let modal = document.querySelector(modalId);

            if (modal) {
                let modalInstance = new bootstrap.Modal(modal);
                modalInstance.show();
            }
        });
    });

    const profilePic = document.getElementById("current-profile-pic");

    profilePic.addEventListener("click", function () {
        const avatarModal = new bootstrap.Modal(document.getElementById("avatarModal"));
        avatarModal.show();
    });
});

function selectAvatar(avatarPath) {
    const profilePic = document.getElementById("current-profile-pic");
    profilePic.src = avatarPath;

    const selectedAvatarInput = document.getElementById("selected-avatar");
    if (!selectedAvatarInput) {
        const input = document.createElement("input");
        input.type = "hidden";
        input.name = "selectedAvatar";
        input.id = "selected-avatar";
        input.value = avatarPath;
        document.querySelector("form").appendChild(input);
    } else {
        selectedAvatarInput.value = avatarPath;
    }

    const avatars = document.querySelectorAll('.selectable-avatar');
    avatars.forEach(avatar => {
        avatar.classList.remove('selected-avatar');
    });

    const selectedAvatar = Array.from(avatars).find(avatar => avatar.src.includes(avatarPath.split('/').pop()));
    if (selectedAvatar) {
        selectedAvatar.classList.add('selected-avatar');
    }
}
