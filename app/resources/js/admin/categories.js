window.openCreateCategoryModal = function () {
    document.getElementById("create-category-modal").classList.remove("hidden");
};

window.editCategory = function (categoryId) {
    fetch(`/admin/categories/${categoryId}/edit`)
        .then((response) => response.json())
        .then((category) => {
            document.getElementById("edit-category-name").value = category.name;
            document.getElementById("edit-category-form").action =
                `/admin/categories/${categoryId}`;
            document
                .getElementById("edit-category-modal")
                .classList.remove("hidden");
        })
        .catch((error) => {
            console.error("Error:", error);
            alert("Failed to load category data");
        });
};

window.closeCategoryModal = function (type) {
    document.getElementById(`${type}-category-modal`).classList.add("hidden");
};
