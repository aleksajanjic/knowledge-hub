<div id="question-detail-modal"
    class="fixed inset-0 bg-black/70 backdrop-blur-sm flex items-center justify-center z-50 p-4 hidden"
    onclick="if(event.target === this) { closeQuestionModal(); }">
    <div
        style="background: #18181B; width: 80%; max-width: 900px; max-height: 90vh; overflow-y: auto; border-radius: 15px; position: relative;">
        <!-- Close X Button -->
        <button type="button" onclick="closeQuestionModal()"
            style="position: absolute; top: 20px; right: 20px; background: none; border: none; color: #71717A; font-size: 24px; cursor: pointer; padding: 5px 10px; z-index: 10;">
            ✕
        </button>

        <!-- Question Content -->
        <div id="question-detail-content" style="padding: 30px;">
            <!-- Loading spinner -->
            <div style="text-align: center; padding: 48px;">
                <svg style="width: 48px; height: 48px; margin: 0 auto; color: #10B981; animation: spin 1s linear infinite;"
                    fill="none" stroke="currentColor" viewBox="0 0 24 24">
                    <path stroke-linecap="round" stroke-linejoin="round" stroke-width="2"
                        d="M4 4v5h.582m15.356 2A8.001 8.001 0 004.582 9m0 0H9m11 11v-5h-.581m0 0a8.003 8.003 0 01-15.357-2m15.357 2H15" />
                </svg>
                <p style="color: #A1A1AA; margin-top: 16px;">Loading...</p>
            </div>
        </div>
    </div>
</div>

<style>
    @keyframes spin {
        from {
            transform: rotate(0deg);
        }

        to {
            transform: rotate(360deg);
        }
    }
</style>

<script>
    function openQuestionModal(questionId) {
        const modal = document.getElementById('question-detail-modal');
        const content = document.getElementById('question-detail-content');

        // Show modal
        modal.classList.remove('hidden');

        // Fetch question details
        fetch(`/questions/${questionId}/details`, {
                headers: {
                    'X-Requested-With': 'XMLHttpRequest'
                }
            })
            .then(response => response.text())
            .then(html => {
                content.innerHTML = html;
            })
            .catch(error => {
                console.error('Error:', error);
                content.innerHTML = '<p style="color: #F43F5E; text-align: center;">Failed to load question</p>';
            });
    }

    function closeQuestionModal() {
        const modal = document.getElementById('question-detail-modal');
        modal.classList.add('hidden');
    }

    // Close on ESC key
    document.addEventListener('keydown', function(event) {
        if (event.key === 'Escape') {
            closeQuestionModal();
        }
    });

    // Submit answer function
    window.submitAnswer = function(event, questionId) {
        event.preventDefault();

        const form = event.target;
        const content = form.content.value;
        const submitBtn = document.getElementById('submit-answer-btn');
        const errorMsg = document.getElementById('answer-error');

        console.log('Submitting answer:', content);

        if (content.trim().length < 10) {
            errorMsg.textContent = 'Answer must be at least 10 characters';
            errorMsg.style.display = 'block';
            return;
        }

        errorMsg.style.display = 'none';
        submitBtn.disabled = true;
        submitBtn.textContent = 'Posting...';

        // Get CSRF token
        const csrfToken = document.querySelector('meta[name="csrf-token"]')?.content || '{{ csrf_token() }}';

        fetch(`/questions/${questionId}/answers`, {
                method: 'POST',
                headers: {
                    'Content-Type': 'application/json',
                    'X-CSRF-TOKEN': csrfToken,
                    'Accept': 'text/html'
                },
                body: JSON.stringify({
                    content: content
                })
            })
            .then(response => {
                console.log('Response status:', response.status);
                if (!response.ok) {
                    return response.text().then(text => {
                        console.error('Error response:', text);
                        throw new Error('Server error');
                    });
                }
                return response.text();
            })
            .then(html => {
                console.log('Success! Answer posted');

                // Remove "no answers" message if exists
                const noAnswersMsg = document.getElementById('no-answers-message');
                if (noAnswersMsg) {
                    noAnswersMsg.remove();
                }

                // Add new answer to the list
                document.getElementById('answers-list').insertAdjacentHTML('beforeend', html);

                // Update answer count in heading
                const heading = document.querySelector('h3');
                if (heading) {
                    const match = heading.textContent.match(/\((\d+)\)/);
                    if (match) {
                        const currentCount = parseInt(match[1]);
                        heading.textContent = heading.textContent.replace(/\(\d+\)/, `(${currentCount + 1})`);
                    }
                }

                // Clear form
                form.reset();
                submitBtn.disabled = false;
                submitBtn.textContent = '{{ __('Post Answer') }}';
            })
            .catch(error => {
                console.error('Error:', error);
                errorMsg.textContent = 'Failed to post answer. Please try again.';
                errorMsg.style.display = 'block';
                submitBtn.disabled = false;
                submitBtn.textContent = '{{ __('Post Answer') }}';
            });
    };

    // Vote answer function
    window.voteAnswer = function(answerId, type, button) {
        const csrfToken = document.querySelector('meta[name="csrf-token"]')?.content || '{{ csrf_token() }}';

        fetch(`/answers/${answerId}/${type}`, {
                method: 'POST',
                headers: {
                    'Content-Type': 'application/json',
                    'X-CSRF-TOKEN': csrfToken
                }
            })
            .then(response => response.json())
            .then(data => {
                const voteCount = document.getElementById(`answer-vote-count-${answerId}`);
                voteCount.textContent = data.votes;

                if (data.votes > 0) {
                    voteCount.style.color = '#10B981';
                } else if (data.votes < 0) {
                    voteCount.style.color = '#F43F5E';
                } else {
                    voteCount.style.color = '#E4E4E7';
                }

                const upvoteBtn = button.parentElement.querySelector('button:first-child');
                const downvoteBtn = button.parentElement.querySelectorAll('button')[1];
                const upvoteSvg = upvoteBtn.querySelector('svg');
                const downvoteSvg = downvoteBtn.querySelector('svg');

                if (data.userVote === 1) {
                    upvoteBtn.style.color = '#10B981';
                    upvoteSvg.setAttribute('fill', 'currentColor');
                    downvoteBtn.style.color = '#71717A';
                    downvoteSvg.setAttribute('fill', 'none');
                } else if (data.userVote === -1) {
                    downvoteBtn.style.color = '#F43F5E';
                    downvoteSvg.setAttribute('fill', 'currentColor');
                    upvoteBtn.style.color = '#71717A';
                    upvoteSvg.setAttribute('fill', 'none');
                } else {
                    upvoteBtn.style.color = '#71717A';
                    upvoteSvg.setAttribute('fill', 'none');
                    downvoteBtn.style.color = '#71717A';
                    downvoteSvg.setAttribute('fill', 'none');
                }
            })
            .catch(error => console.error('Error:', error));
    };

    // Accept answer function
    window.acceptAnswer = function(questionId, answerId, button) {
        const csrfToken = document.querySelector('meta[name="csrf-token"]')?.content || '{{ csrf_token() }}';

        fetch(`/questions/${questionId}/answers/${answerId}/accept`, {
                method: 'POST',
                headers: {
                    'Content-Type': 'application/json',
                    'X-CSRF-TOKEN': csrfToken
                }
            })
            .then(response => response.json())
            .then(data => {
                if (data.success) {
                    // Reload the question details to show updated accepted status
                    openQuestionModal(questionId);
                }
            })
            .catch(error => console.error('Error:', error));
    };
</script>
