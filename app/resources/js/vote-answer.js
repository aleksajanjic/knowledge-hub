window.voteAnswer = function (event, answerId, type, button) {
    event.preventDefault();
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

            if (data.votes > 0) countEl.style.color = "#10B981";
            else if (data.votes < 0) countEl.style.color = "#F43F5E";
            else countEl.style.color = "#E4E4E7";

            const wrapper = button.parentElement;
            const upBtn = wrapper.querySelector('[data-vote="up"]');
            const downBtn = wrapper.querySelector('[data-vote="down"]');
            const upSvg = upBtn.querySelector("svg");
            const downSvg = downBtn.querySelector("svg");

            // reset buttons
            upBtn.dataset.voted = "0";
            downBtn.dataset.voted = "0";
            upBtn.style.color = "#71717A";
            downBtn.style.color = "#71717A";
            upSvg.setAttribute("fill", "none");
            downSvg.setAttribute("fill", "none");

            // apply current user vote
            if (data.userVote === 1) {
                upBtn.dataset.voted = "1";
                upBtn.style.color = "#10B981";
                upSvg.setAttribute("fill", "currentColor");
            } else if (data.userVote === -1) {
                downBtn.dataset.voted = "1";
                downBtn.style.color = "#F43F5E";
                downSvg.setAttribute("fill", "currentColor");
            }
        })
        .catch((err) => console.error(err));
};
