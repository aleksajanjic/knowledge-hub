let answerCreateEditor = null;

function initAnswerEditor() {
    const textarea = document.querySelector(".answer-content-editor");
    if (textarea && typeof EasyMDE !== "undefined" && !answerCreateEditor) {
        answerCreateEditor = new EasyMDE({
            element: textarea,
            spellChecker: false,
            placeholder: "Write your answer here...",
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
}

if (document.readyState === "loading") {
    document.addEventListener("DOMContentLoaded", initAnswerEditor);
} else {
    initAnswerEditor();
}
