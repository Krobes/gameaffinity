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
        divList.addEventListener('click', function (event) {

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

    var loadFile = function (event) {
        var image = document.getElementById("output");
        image.src = URL.createObjectURL(event.target.files[0]);
    };
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

}
