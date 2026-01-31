let questionEditEditor = null;
let answerEditEditor = null;
const modalStack = [];

const easyMDEConfig = {
    spellChecker: false,
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

function pushModal(modalEl, type) {
    modalStack.push({ el: modalEl, type });
}

function removeModalFromStack(modalEl) {
    const i = modalStack.findIndex((m) => m.el === modalEl);
    if (i !== -1) modalStack.splice(i, 1);
}

function destroyEditor(type) {
    if (type === "question" && questionEditEditor) {
        questionEditEditor.toTextArea();
        questionEditEditor = null;
    }
    if (type === "answer" && answerEditEditor) {
        answerEditEditor.toTextArea();
        answerEditEditor = null;
    }
}

window.openEditModal = function (id, type) {
    const isQuestion = type === "question";
    const fetchUrl = isQuestion
        ? `/questions/${id}/edit`
        : `/answers/${id}/edit`;
    const modalId = isQuestion ? "question-edit-modal" : "answer-edit-modal";
    const textareaId = isQuestion
        ? "question-edit-content-editor"
        : "answer-edit-content-editor";

    fetch(fetchUrl, { headers: { "X-Requested-With": "XMLHttpRequest" } })
        .then((r) => r.text())
        .then((html) => {
            document.body.insertAdjacentHTML("beforeend", html);

            const modal = document.getElementById(modalId);
            if (!modal) return;

            modal.classList.remove("hidden");
            pushModal(modal, type);

            const textarea = document.getElementById(textareaId);
            if (textarea && typeof EasyMDE !== "undefined") {
                const editor = new EasyMDE({
                    element: textarea,
                    ...easyMDEConfig,
                    placeholder: isQuestion
                        ? "Describe your problem in detail..."
                        : "Write your answer here...",
                });

                if (isQuestion) questionEditEditor = editor;
                else answerEditEditor = editor;
            }
        });
};

window.closeTopModal = function () {
    if (!modalStack.length) return;

    const { el, type } = modalStack.pop();
    destroyEditor(type);
    el.remove();
};

document.addEventListener("keydown", function (e) {
    if (e.key === "Escape") {
        window.closeTopModal();
    }
});

document.addEventListener("submit", function () {
    if (questionEditEditor) questionEditEditor.codemirror.save();
    if (answerEditEditor) answerEditEditor.codemirror.save();
});
