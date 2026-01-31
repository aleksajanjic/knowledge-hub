let questionEditEditor = null;
let answerEditEditor = null;

const easyMDEConfig = {
    spellChecker: false,
    placeholder: "Describe your problem in detail...",
    minHeight: "200px",
    status: false,
    toolbar: [
        "bold",
        "italic",
        "heading",
        "|",
        "code",
        "quote",
        "unordered-list",
        "ordered-list",
        "|",
        "link",
        "preview",
        "guide",
    ],
};

function destroyEditEditors() {
    if (questionEditEditor) {
        questionEditEditor.toTextArea();
        questionEditEditor = null;
    }
    if (answerEditEditor) {
        answerEditEditor.toTextArea();
        answerEditEditor = null;
    }
}

window.openEditModal = function (id, type) {
    let fetchUrl =
        type === "question" ? `/questions/${id}/edit` : `/answers/${id}/edit`;

    fetch(fetchUrl, {
        headers: {
            "X-Requested-With": "XMLHttpRequest",
        },
        credentials: "same-origin",
    })
        .then(async (response) => {
            if (!response.ok) {
                let msg = `${response.status} ${response.statusText}`;
                try {
                    const data = await response.json();
                    if (data.message) msg += "\n" + data.message;
                } catch (_) {}
                throw new Error(msg);
            }
            return response.text();
        })
        .then((html) => {
            const existingModal = document.getElementById("question-edit-modal");
            if (existingModal) {
                destroyEditEditors();
                existingModal.remove();
            }
            document.body.insertAdjacentHTML("beforeend", html);
            const modal = document.getElementById("question-edit-modal");
            if (!modal) {
                throw new Error("Edit form HTML invalid (missing modal element)");
            }
            modal.classList.remove("hidden");

            if (typeof EasyMDE !== "undefined") {
                if (type === "question") {
                    const textarea = document.getElementById(
                        "question-edit-content-editor",
                    );
                    if (textarea) {
                        questionEditEditor = new EasyMDE({
                            element: textarea,
                            ...easyMDEConfig,
                        });
                    }
                } else if (type === "answer") {
                    const textarea = document.getElementById(
                        "answer-edit-content-editor",
                    );
                    if (textarea) {
                        answerEditEditor = new EasyMDE({
                            element: textarea,
                            ...easyMDEConfig,
                            placeholder: "Write your answer here...",
                        });
                    }
                }
            }
        })
        .catch((error) => {
            console.error("Edit form error:", error);
            alert("Failed to load edit form" + (error.message ? ": " + error.message : ""));
        });
};
