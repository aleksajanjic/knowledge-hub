window.acceptAnswer = function (questionId, answerId, button) {
    const csrfToken =
        document.querySelector('meta[name="csrf-token"]')?.content ||
        "{{ csrf_token() }}";

    fetch(`/questions/${questionId}/accept-answer/${answerId}`, {
        method: "POST",
        headers: {
            "Content-Type": "application/json",
            "X-CSRF-TOKEN": csrfToken,
            Accept: "text/html",
        },
    })
        .then((res) => res.json())
        .then((data) => {
            if (!data.success) return;

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
                        "display:flex;align-items:center;gap:6px;padding:4px 10px;background:rgba(16,185,129,.1);color:#10B981;font-size:12px;font-weight:600;border-radius:6px;";
                    label.innerHTML = `
                        <svg style="width: 14px; height: 14px;" fill="currentColor" viewBox="0 0 24 24">
                            <path stroke-linecap="round" stroke-linejoin="round" stroke-width="2" d="M9 12l2 2 4-4m6 2a9 9 0 11-18 0 9 9 0 0118 0z"/>
                        </svg>
                        Accepted
                    `;
                    const metaDiv = container.querySelector(
                        '[style*="justify-content: space-between"]',
                    );
                    if (metaDiv) {
                        metaDiv.appendChild(label);
                    }
                }
                label.style.display = "flex";
            }

            if (data.authorReputation !== undefined) {
                const answerContainer = button.closest(".answer-container");
                const reputationEl = answerContainer.querySelector(
                    '[id^="answer-reputation-"]',
                );
                if (reputationEl) {
                    reputationEl.textContent = data.authorReputation;
                    reputationEl.style.color =
                        data.authorReputation > 0 ? "#10B981" : "#F43F5E";
                }
            }
        })
        .catch((err) => console.error(err));
};
