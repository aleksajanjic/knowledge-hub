<div id="question-edit-modal"
    class="fixed inset-0 bg-black/70 backdrop-blur-sm flex items-center justify-center z-50 p-4 hidden"
    onclick="if(event.target === this) { this.classList.add('hidden'); }">
    <div style="background: #18181B; width: 60%; padding: 30px; border-radius: 15px; position: relative;">
        <!-- Close X Button -->
        <button type="button" onclick="document.getElementById('question-edit-modal').classList.add('hidden')"
            style="position: absolute; top: 20px; right: 20px; background: none; border: none; color: #71717A; font-size: 24px; cursor: pointer; padding: 5px 10px;">
            ✕
        </button>

        <h2 style="color: white; font-size: 24px; margin-bottom: 20px;">{{ __('Edit Question') }}</h2>

        <form action="{{ route('questions.update', $question) }}" method="POST">
            @csrf
            @method('PUT')

            <div style="margin-bottom: 15px;">
                <label style="color: #A1A1AA; display: block; margin-bottom: 5px; font-size: 14px;">
                    {{ __('Title') }}
                </label>
                <input type="text" name="title" required value="{{ old('title', $question->title) }}"
                    placeholder="What's your question?"
                    style="width: 100%; padding: 12px; border-radius: 10px; border: 1px solid #3F3F46; background: #27272A; color: white;">
            </div>

            <div style="margin-bottom: 15px;">
                <label style="color: #A1A1AA; display: block; margin-bottom: 5px; font-size: 14px;">
                    {{ __('Question') }}
                </label>
                <!-- <textarea name="content" rows="6" required placeholder="Describe your problem in detail..." -->
                <!--     style="width: 100%; padding: 12px; border-radius: 10px; border: 1px solid #3F3F46; background: #27272A; color: white;">{{ old('content', $question->content) }}</textarea> -->
                <textarea id="question-content-editor" name="content" required></textarea>
            </div>

            <div style="margin-bottom: 20px;">
                <label style="color: #A1A1AA; display: block; margin-bottom: 5px; font-size: 14px;">
                    {{ __('Tags') }}
                </label>
                <input type="text" name="tags"
                    value="{{ old('tags', $question->tags->pluck('name')->implode(', ')) }}"
                    placeholder="e.g. javascript, react, api"
                    style="width: 100%; padding: 12px; border-radius: 10px; border: 1px solid #3F3F46; background: #27272A; color: white;">
            </div>

            <div style="display: flex; gap: 10px; justify-content: flex-end;">
                <button type="button" onclick="document.getElementById('question-edit-modal').classList.add('hidden')"
                    style="padding: 10px 20px; background: transparent; color: #A1A1AA; border: none; border-radius: 8px; cursor: pointer; font-size: 14px;">
                    {{ __('Cancel') }}
                </button>
                <button type="submit"
                    style="padding: 10px 24px; background: #10B981; color: #18181B; border: none; border-radius: 8px; cursor: pointer; font-weight: 600; font-size: 14px;">
                    {{ __('Update') }}
                </button>
            </div>
        </form>
    </div>
</div>

<script>
    document.addEventListener('keydown', function(event) {
        if (event.key === 'Escape') {
            const modal = document.getElementById('question-edit-modal');
            if (modal && !modal.classList.contains('hidden')) {
                modal.classList.add('hidden');
            }
        }
    });
</script>
