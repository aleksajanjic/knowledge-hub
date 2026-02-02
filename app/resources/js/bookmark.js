window.toggleBookmark = function (questionId, button) {
    const csrfToken = document.querySelector('meta[name="csrf-token"]')?.content;

    fetch(`/questions/${questionId}/bookmark`, {
        method: "POST",
        headers: {
            "Content-Type": "application/json",
            "X-CSRF-TOKEN": csrfToken,
            "X-Requested-With": "XMLHttpRequest",
            Accept: "application/json",
        },
        body: JSON.stringify({}),
        credentials: "same-origin",
    })
        .then((r) => r.json())
        .then((data) => {
            const icon = document.getElementById(`bookmark-icon-${questionId}`);
            if (icon) {
                icon.setAttribute(
                    "fill",
                    data.bookmarked ? "currentColor" : "none",
                );
            }
            if (button) {
                button.style.color = data.bookmarked ? "#F59E0B" : "#71717A";
                button.title = data.bookmarked ? "Remove bookmark" : "Bookmark";
            }
        })
        .catch((err) => console.error("Bookmark error:", err));
};
