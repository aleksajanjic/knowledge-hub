<div id="questions-container" class="space-y-4">
    @forelse($questions as $question)
    <x-question-card :question="$question" />
    @empty
    <div style="text-align: center; padding: 48px 24px; color: #71717A;">
        <svg style="width: 64px; height: 64px; margin: 0 auto 16px; color: #3F3F46;" fill="none"
            stroke="currentColor" viewBox="0 0 24 24">
            <path stroke-linecap="round" stroke-linejoin="round" stroke-width="2"
                d="M21 21l-6-6m2-5a7 7 0 11-14 0 7 7 0 0114 0z" />
        </svg>
        <p style="font-size: 18px; margin-bottom: 8px;">No questions found</p>
        <p style="font-size: 14px;">Try adjusting your search or filters</p>
    </div>
    @endforelse
</div>
