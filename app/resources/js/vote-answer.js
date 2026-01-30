window.voteAnswer = function (event, answerId, type, button) {
    event.stopPropagation();

    fetch(`/answers/${answerId}/${type}`, {
        method: "POST",
        headers: {
            "Content-Type": "application/json",
            "X-CSRF-TOKEN": document.querySelector('meta[name="csrf-token"]')
                .content,
        },
    })
        .then((r) => r.json())
        .then((data) => {
            const countEl = document.getElementById(
                `answer-vote-count-${answerId}`,
            );
            countEl.textContent = data.votes;
            countEl.style.color =
                data.votes > 0
                    ? "#10B981"
                    : data.votes < 0
                      ? "#F43F5E"
                      : "#E4E4E7";

            const container = button.parentElement;
            const upBtn = container.querySelector('[data-type="upvote"]');
            const downBtn = container.querySelector('[data-type="downvote"]');
            const upSvg = upBtn.querySelector("svg");
            const downSvg = downBtn.querySelector("svg");

            if (data.userVote === 1) {
                upBtn.style.color = "#10B981";
                upBtn.dataset.voted = "1";
                upSvg.setAttribute("fill", "currentColor");
                downBtn.style.color = "#71717A";
                downBtn.dataset.voted = "0";
                downSvg.setAttribute("fill", "none");
            } else if (data.userVote === -1) {
                downBtn.style.color = "#F43F5E";
                downBtn.dataset.voted = "1";
                downSvg.setAttribute("fill", "currentColor");
                upBtn.style.color = "#71717A";
                upBtn.dataset.voted = "0";
                upSvg.setAttribute("fill", "none");
            } else {
                upBtn.style.color = "#71717A";
                upBtn.dataset.voted = "0";
                upSvg.setAttribute("fill", "none");
                downBtn.style.color = "#71717A";
                downBtn.dataset.voted = "0";
                downSvg.setAttribute("fill", "none");
            }

            if (data.authorReputation !== undefined) {
                const reputationEl = document.getElementById(
                    `answer-reputation-${answerId}`,
                );
                if (reputationEl) {
                    reputationEl.textContent = data.authorReputation;
                    reputationEl.style.color =
                        data.authorReputation > 0 ? "#10B981" : "#F43F5E";
                }
            }
        })
        .catch((error) => {
            console.error("Vote error:", error);
        });
};
