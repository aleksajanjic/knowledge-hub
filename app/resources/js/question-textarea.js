let questionCreateEditor = null;

function openQuestionCreateModal() {
    document.getElementById("question-modal").classList.remove("hidden");

    if (!questionCreateEditor) {
        setTimeout(() => {
            const textarea = document.getElementById("question-content-editor");
            if (textarea && typeof EasyMDE !== "undefined") {
                questionCreateEditor = new EasyMDE({
                    element: textarea,
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
                });
            }
        }, 100);
    }
}

function closeQuestionCreateModal() {
    document.getElementById("question-modal").classList.add("hidden");

    if (questionCreateEditor) {
        questionCreateEditor.value("");
    }
}

window.openQuestionCreateModal = openQuestionCreateModal;
window.closeQuestionCreateModal = closeQuestionCreateModal;
