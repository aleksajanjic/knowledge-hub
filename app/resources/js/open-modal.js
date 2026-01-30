// Open edit modal
window.openEditModal = function (questionId) {
    fetch(`/questions/${questionId}/edit`, {
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
