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

        modal.classList.remove('hidden');

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

    document.addEventListener('keydown', function(event) {
        if (event.key === 'Escape') {
            closeQuestionModal();
        }
    });

    window.submitAnswer = function(event, questionId) {
        event.preventDefault();

        const form = event.target;
        const content = form.content.value;
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
                if (!response.ok) {
                    return response.text().then(text => {
                        console.error('Error response:', text);
                        throw new Error('Server error');
                    });
                }
                return response.text();
            })
            .then(html => {
                const noAnswersMsg = document.getElementById('no-answers-message');
                if (noAnswersMsg) {
                    noAnswersMsg.remove();
                }

                document.getElementById('answers-list').insertAdjacentHTML('beforeend', html);

                const heading = document.querySelector('h3');
                if (heading) {
                    const match = heading.textContent.match(/\((\d+)\)/);
                    if (match) {
                        const currentCount = parseInt(match[1]);
                        heading.textContent = heading.textContent.replace(/\(\d+\)/, `(${currentCount + 1})`);
                    }
                }

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
                    openQuestionModal(questionId);
                }
            })
            .catch(error => console.error('Error:', error));
    };
</script>
