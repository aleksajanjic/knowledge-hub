window.openEditModal = function (id, type) {
    let fetchUrl =
        type === "question" ? `/questions/${id}/edit` : `/answers/${id}/edit`;

    fetch(fetchUrl, {
        headers: {
            "X-Requested-With": "XMLHttpRequest",
        },
    })
        .then((response) => response.text())
        .then((html) => {
            const existingModal = document.getElementById("question-edit-modal");
            if (existingModal) {
                existingModal.remove();
            }
            document.body.insertAdjacentHTML("beforeend", html);
            document
                .getElementById("question-edit-modal")
                .classList.remove("hidden");
        })
        .catch((error) => {
            console.error("Error:", error);
            alert("Failed to load edit form");
        });
};
