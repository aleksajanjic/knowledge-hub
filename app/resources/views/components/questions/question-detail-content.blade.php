@php
    use App\Helpers\MarkdownHelper;
    $userVote = auth()->check() ? $question->userVote(auth()->id()) : null;
    $hasUpvoted = $userVote && $userVote->vote == 1;
    $hasDownvoted = $userVote && $userVote->vote == -1;
@endphp

<div style="display: flex; gap: 24px;">
    <!-- Vote Section -->
    <div style="display: flex; flex-direction: column; align-items: center; gap: 8px; min-width: 60px;">
        <button onclick="event.stopPropagation(); vote({{ $question->id }}, 'upvote', this)"
            style="padding: 8px; color: {{ $hasUpvoted ? '#10B981' : '#71717A' }}; background: none; border: none; cursor: pointer; transition: color 0.2s;"
            onmouseover="if(!this.style.color.includes('10B981')) this.style.color='#10B981'"
            onmouseout="if(!{{ $hasUpvoted ? 'true' : 'false' }}) this.style.color='#71717A'">
            <svg style="width: 28px; height: 28px;" fill="{{ $hasUpvoted ? 'currentColor' : 'none' }}"
                stroke="currentColor" viewBox="0 0 24 24">
                <path stroke-linecap="round" stroke-linejoin="round" stroke-width="2" d="M5 15l7-7 7 7" />
            </svg>
        </button>
        <span id="vote-count-{{ $question->id }}"
            style="font-size: 24px; font-weight: 700; color: {{ $question->votes > 0 ? '#10B981' : ($question->votes < 0 ? '#F43F5E' : '#E4E4E7') }};">
            {{ $question->votes }}
        </span>
        <button onclick="event.stopPropagation(); vote({{ $question->id }}, 'downvote', this)"
            style="padding: 8px; color: {{ $hasDownvoted ? '#F43F5E' : '#71717A' }}; background: none; border: none; cursor: pointer; transition: color 0.2s;"
            onmouseover="if(!this.style.color.includes('F43F5E')) this.style.color='#F43F5E'"
            onmouseout="if(!{{ $hasDownvoted ? 'true' : 'false' }}) this.style.color='#71717A'">
            <svg style="width: 28px; height: 28px;" fill="{{ $hasDownvoted ? 'currentColor' : 'none' }}"
                stroke="currentColor" viewBox="0 0 24 24">
                <path stroke-linecap="round" stroke-linejoin="round" stroke-width="2" d="M19 9l-7 7-7-7" />
            </svg>
        </button>
    </div>

    <!-- Content -->
    <div style="flex: 1;">
        <!-- Title -->
        <h2 style="color: #FAFAFA; font-size: 28px; font-weight: 600; margin-bottom: 16px; line-height: 1.3;">
            {{ $question->title }}
        </h2>

        <!-- Meta Info -->
        <div
            style="display: flex; align-items: center; gap: 16px; margin-bottom: 24px; padding-bottom: 16px; border-bottom: 1px solid #27272A;">

            <span style="display: flex; align-items: center; gap: 8px; color: #A1A1AA; font-size: 14px;">
                @if ($question->user && $question->user->profile_image)
                    <img src="{{ asset('storage/' . $question->user->profile_image) }}"
                        alt="{{ $question->user->name }}"
                        style="width:32px; height:32px; border-radius:50%; object-cover; border:1px solid #3F3F46;">
                @else
                    <div
                        style="width:32px; height:32px; border-radius:50%; background:#3F3F46; color:#A1A1AA; display:flex; align-items:center; justify-content:center; font-size:12px; font-weight:600;">
                        {{ strtoupper(substr($question->user->name ?? 'U', 0, 1)) }}
                    </div>
                @endif

                <span style="color: #FAFAFA; font-weight: 500;">
                    {{ $question->user->name ?? 'Unknown' }}
                </span>
                <span
                    style="color: {{ ($question->user->reputation ?? 0) > 0 ? '#10B981' : '#F43F5E' }}; font-size: 12px; font-weight: 600;">
                    {{ $question->user->reputation ?? 0 }}
                </span>
            </span>

            <span style="display: flex; align-items: center; gap: 6px; color: #71717A; font-size: 14px;">
                <svg style="width: 14px; height: 14px;" fill="none" stroke="currentColor" viewBox="0 0 24 24">
                    <path stroke-linecap="round" stroke-linejoin="round" stroke-width="2"
                        d="M12 8v4l3 3m6-3a9 9 0 11-18 0 9 9 0 0118 0z" />
                </svg>
                {{ $question->created_at->diffForHumans() }}
            </span>
        </div>

        <!-- Question Content -->
        <div class="markdown-content" style="color: #D4D4D8; line-height: 1.7; margin-bottom: 24px;">
            {!! MarkdownHelper::parse($question->content) !!}
        </div>

        <!-- Tags -->
        @if ($question->tags->count() > 0)
            <div style="display: flex; flex-wrap: wrap; gap: 8px; margin-bottom: 32px;">
                @foreach ($question->tags as $tag)
                    <a href="{{ route('questions.index', ['tag' => $tag->name]) }}" onclick="closeQuestionModal();"
                        style="padding: 6px 12px; background: #27272A; color: #A1A1AA; font-size: 13px; border-radius: 8px; transition: all 0.2s; text-decoration: none; cursor: pointer; font-weight: 500;"
                        onmouseover="this.style.background='#3F3F46'; this.style.color='#D4D4D8'"
                        onmouseout="this.style.background='#27272A'; this.style.color='#A1A1AA'">
                        {{ $tag->name }}
                    </a>
                @endforeach
            </div>
        @endif

        <!-- Answers Section -->
        <div style="border-top: 2px solid #27272A; padding-top: 24px;">
            <h3 style="color: #FAFAFA; font-size: 20px; font-weight: 600; margin-bottom: 16px;">
                {{ __('Answers') }} ({{ $question->answers->count() }})
            </h3>

            <div id="answers-list">
                @forelse($question->answers->sortByDesc('is_accepted')->sortByDesc('votes') as $answer)
                    <x-questions.answer-item :answer="$answer" :question="$question" />
                @empty
                    <div id="no-answers-message" style="text-align: center; padding: 48px 24px; color: #71717A;">
                        <svg style="width: 48px; height: 48px; margin: 0 auto 16px; color: #3F3F46;" fill="none"
                            stroke="currentColor" viewBox="0 0 24 24">
                            <path stroke-linecap="round" stroke-linejoin="round" stroke-width="2"
                                d="M8 12h.01M12 12h.01M16 12h.01M21 12c0 4.418-4.03 8-9 8a9.863 9.863 0 01-4.255-.949L3 20l1.395-3.72C3.512 15.042 3 13.574 3 12c0-4.418 4.03-8 9-8s9 3.582 9 8z" />
                        </svg>
                        <p style="font-size: 16px; margin-bottom: 8px;">No answers yet</p>
                        <p style="font-size: 14px;">Be the first to answer this question</p>
                    </div>
                @endforelse
            </div>

            <!-- Answer Form -->
            <div style="margin-top: 24px; padding: 20px; background: #27272A; border-radius: 12px;">
                <h4 style="color: #FAFAFA; font-size: 16px; font-weight: 600; margin-bottom: 12px;">
                    {{ __('Your Answer') }}
                </h4>
                <form id="answer-form-{{ $question->id }}">
                    @csrf
                    <textarea id="answer-content-{{ $question->id }}" class="answer-content-editor" name="content" rows="6"
                        placeholder="Write your answer here..."
                        style="width: 100%; padding: 12px; border-radius: 10px; border: 1px solid #3F3F46; background: #18181B; color: white; font-size: 14px; resize: vertical;"
                        required></textarea>
                    <span id="answer-error" style="color: #F43F5E; font-size: 13px; display: none;"></span>
                    <div style="display: flex; justify-content: space-between; align-items: center; margin-top: 12px;">
                        <button type="button" id="submit-answer-btn"
                            onclick="submitAnswer(event, {{ $question->id }})"
                            style="padding: 10px 24px; background: #10B981; color: #18181B; border: none; border-radius: 8px; cursor: pointer; font-weight: 600; font-size: 14px;">
                            {{ __('Post Answer') }}
                        </button>
                    </div>
                </form>
            </div>
        </div>
    </div>
</div>
