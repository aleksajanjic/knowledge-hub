window.vote = function (questionId, type, button) {
    const csrfToken = document.querySelector('meta[name="csrf-token"]').content;

    fetch(`/questions/${questionId}/${type}`, {
        method: "POST",
        headers: {
            "Content-Type": "application/json",
            "X-CSRF-TOKEN": csrfToken,
        },
    })
        .then((r) => r.json())
        .then((data) => {
            const countEl = document.getElementById(`vote-count-${questionId}`);
            countEl.textContent = data.votes;
            countEl.style.color =
                data.votes > 0
                    ? "#10B981"
                    : data.votes < 0
                      ? "#F43F5E"
                      : "#E4E4E7";

            const wrap = button.parentElement;
            const up = wrap.children[0];
            const down = wrap.children[2];
            const upSvg = up.querySelector("svg");
            const downSvg = down.querySelector("svg");

            if (data.userVote === 1) {
                up.style.color = "#10B981";
                upSvg.setAttribute("fill", "currentColor");
                down.style.color = "#71717A";
                downSvg.setAttribute("fill", "none");
            } else if (data.userVote === -1) {
                down.style.color = "#F43F5E";
                downSvg.setAttribute("fill", "currentColor");
                up.style.color = "#71717A";
                upSvg.setAttribute("fill", "none");
            } else {
                up.style.color = down.style.color = "#71717A";
                upSvg.setAttribute("fill", "none");
                downSvg.setAttribute("fill", "none");
            }

            if (data.authorReputation !== undefined) {
                const reputationEl = document.getElementById(
                    `reputation-${questionId}`,
                );
                if (reputationEl) {
                    reputationEl.textContent = data.authorReputation;
                    reputationEl.style.color =
                        data.authorReputation > 0 ? "#10B981" : "#F43F5E";
                }
            }
        })
        .catch((error) => console.error("Vote error:", error));
};
