window.openCreateUserModal = function () {
    document.getElementById("create-user-modal").classList.remove("hidden");
};

window.editUser = function (userId) {
    fetch(`/admin/users/${userId}/edit`)
        .then((response) => response.json())
        .then((user) => {
            document.getElementById("edit-user-name").value = user.name;
            document.getElementById("edit-user-email").value = user.email;
            document.getElementById("edit-user-form").action =
                `/admin/users/${userId}`;
            document
                .getElementById("edit-user-modal")
                .classList.remove("hidden");
        })
        .catch((error) => {
            console.error("Error:", error);
            alert("Failed to load user data");
        });
};

window.closeUserModal = function (type) {
    document.getElementById(`${type}-user-modal`).classList.add("hidden");
};
