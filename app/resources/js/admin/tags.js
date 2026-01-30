// Tag functions
window.openCreateTagModal = function () {
    document.getElementById("create-tag-modal").classList.remove("hidden");
};

window.editTag = function (tagId) {
    fetch(`/admin/tags/${tagId}/edit`)
        .then((response) => response.json())
        .then((tag) => {
            document.getElementById("edit-tag-name").value = tag.name;
            document.getElementById("edit-tag-form").action =
                `/admin/tags/${tagId}`;
            document.getElementById("edit-tag-modal").classList.remove("hidden");
        })
        .catch((error) => {
            console.error("Error:", error);
            alert("Failed to load tag data");
        });
};

window.closeTagModal = function (type) {
    document.getElementById(`${type}-tag-modal`).classList.add("hidden");
};
