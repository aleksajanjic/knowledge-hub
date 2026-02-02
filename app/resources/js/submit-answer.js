window.generateAIAnswer = function (questionId) {
    const btn = document.getElementById("generate-ai-answer-btn");
    if (btn) {
        btn.disabled = true;
        btn.textContent = "Generating...";
    }

    const csrfToken = document.querySelector('meta[name="csrf-token"]')?.content;

    fetch(`/ai/questions/${questionId}/generate-answer`, {
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
        .then((response) => response.json())
        .then((data) => {
            if (data.success && typeof openQuestionModal === "function") {
                openQuestionModal(questionId);
            } else {
                alert(data.message || "Failed to generate AI answer.");
            }
        })
        .catch((error) => {
            console.error("Error:", error);
            alert("Failed to generate AI answer.");
        })
        .finally(() => {
            if (btn) {
                btn.disabled = false;
                btn.textContent = "Generate AI Answer";
            }
        });
};

window.submitAnswer = function(event, questionId) {
    event.preventDefault();

    const form = document.getElementById(`answer-form-${questionId}`);
    const textarea = document.getElementById(`answer-content-${questionId}`);
    const content = textarea.value;

    const submitBtn = document.getElementById('submit-answer-btn');
    const errorMsg = document.getElementById('answer-error');

    if (content.trim().length < 10) {
        errorMsg.textContent = 'Answer must be at least 10 characters';
        errorMsg.style.display = 'block';
        return;
    }

    errorMsg.style.display = 'none';
    submitBtn.disabled = true;
    submitBtn.textContent = 'Posting...';

    const csrfToken = document.querySelector('meta[name="csrf-token"]')?.content;

    fetch(`/questions/${questionId}/answers`, {
        method: 'POST',
        headers: {
            'Content-Type': 'application/json',
            'X-CSRF-TOKEN': csrfToken,
            'Accept': 'application/json'
        },
        body: JSON.stringify({ content: content })
    })
    .then(response => response.json())
    .then(data => {
        openQuestionModal(questionId);
    })
    .catch(error => {
        console.error('Error:', error);
        openQuestionModal(questionId);
    });
};
