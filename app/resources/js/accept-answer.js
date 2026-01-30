window.acceptAnswer = function (questionId, answerId, button) {
    fetch(`/questions/${questionId}/accept-answer/${answerId}`, {
        method: "POST",
        headers: {
            "Content-Type": "application/json",
            "X-CSRF-TOKEN": document.querySelector('meta[name="csrf-token"]')
                .content,
        },
    })
        .then((res) => res.json())
        .then((data) => {
            if (!data.success) return;

            // Reset all answers
            document
                .querySelectorAll(".answer-container")
                .forEach((container) => {
                    container.style.border = "1px solid #27272A";
                    container.style.background = "transparent";

                    const acceptBtn = container.querySelector(
                        '[id^="accept-btn-"]',
                    );
                    if (acceptBtn) {
                        acceptBtn.style.color = "#71717A";
                        const svg = acceptBtn.querySelector("svg");
                        if (svg) svg.setAttribute("fill", "none");
                    }

                    const label = container.querySelector(".accepted-label");
                    if (label) label.style.display = "none";
                });

            // Highlight the newly accepted answer
            if (data.accepted) {
                const container = button.closest(".answer-container");
                container.style.background = "rgba(16, 185, 129, 0.05)";
                container.style.border = "1px solid #10B981";

                button.style.color = "#10B981";
                const svg = button.querySelector("svg");
                if (svg) svg.setAttribute("fill", "currentColor");

                let label = container.querySelector(".accepted-label");
                if (!label) {
                    label = document.createElement("span");
                    label.classList.add("accepted-label");
                    label.style.cssText =
                        "display:flex;align-items:center;gap:6px;background:rgba(16,185,129,.1);color:#10B981;font-size:12px;font-weight:600;border-radius:6px;";
                    container.appendChild(label);
                }
                label.style.display = "flex";
            }
        })
        .catch((err) => console.error(err));
};
