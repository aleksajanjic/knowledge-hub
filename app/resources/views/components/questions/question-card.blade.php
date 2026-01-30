@props(['question'])

@php
    $userVote = auth()->check() ? $question->userVote(auth()->id()) : null;
    $hasUpvoted = $userVote && $userVote->vote == 1;
    $hasDownvoted = $userVote && $userVote->vote == -1;

    $isOwner = auth()->check() && $question->user_id === auth()->id();
    $bgColor = $isOwner ? 'rgba(40,40,47,.5)' : 'rgba(24,24,27,.5)';
    $borderColor = $isOwner ? '#3F3F46' : '#27272A';
    $textColor = $isOwner ? '#E4E4E7' : '#FAFAFA';

    $reputationColor = ($question->user->reputation ?? 0) > 0 ? '#10B981' : '#F43F5E';
@endphp


<div onclick="openQuestionModal({{ $question->id }})"
    style="background: {{ $bgColor }}; border:1px solid {{ $borderColor }}; border-radius:12px; padding:20px; margin-bottom:16px; cursor:pointer; transition:.2s; position:relative;">

    <!-- Three-dot menu -->
    @canany(['update', 'delete'], $question)
        <div style="position:absolute; top:12px; right:12px;" onclick="event.stopPropagation()">
            <div style="position: relative; display: inline-block;">
                <button onclick="toggleDropdown({{ $question->id }})"
                    class="p-2 rounded text-zinc-400 hover:text-zinc-200 hover:bg-zinc-800 transition-colors"
                    style="background: none; border: none; cursor: pointer;">
                    <svg xmlns="http://www.w3.org/2000/svg" class="w-5 h-5" fill="white" viewBox="0 0 20 20">
                        <path
                            d="M10 3a1.5 1.5 0 110 3 1.5 1.5 0 010-3zm0 5a1.5 1.5 0 110 3 1.5 1.5 0 010-3zm0 5a1.5 1.5 0 110 3 1.5 1.5 0 010-3z" />
                    </svg>
                </button>

                <!-- Dropdown menu -->
                <div id="dropdown-{{ $question->id }}"
                    style="display: none; position: absolute; right: 0; top: 100%; margin-top: 4px; background: #18181B; border: 1px solid #3F3F46; border-radius: 8px; min-width: 150px; box-shadow: 0 4px 6px rgba(0,0,0,0.3); z-index: 50;">
                    <a href="#" onclick="event.preventDefault(); openEditModal({{ $question->id }})"
                        onclick="event.stopPropagation()"
                        style="display: block; padding: 10px 16px; color: #FAFAFA; text-decoration: none; font-size: 14px; transition: background 0.2s;"
                        onmouseover="this.style.background='#27272A'" onmouseout="this.style.background='transparent'">
                        <svg style="width: 14px; height: 14px; display: inline-block; margin-right: 8px;" fill="none"
                            stroke="currentColor" viewBox="0 0 24 24">
                            <path stroke-linecap="round" stroke-linejoin="round" stroke-width="2"
                                d="M11 5H6a2 2 0 00-2 2v11a2 2 0 002 2h11a2 2 0 002-2v-5m-1.414-9.414a2 2 0 112.828 2.828L11.828 15H9v-2.828l8.586-8.586z" />
                        </svg>
                        {{ __('Edit') }}
                    </a>

                    <form method="POST" action="{{ route('questions.destroy', $question) }}"
                        onsubmit="return confirm('{{ __('Are you sure you want to delete this question?') }}')">
                        @csrf
                        @method('DELETE')
                        <button type="submit" onclick="event.stopPropagation()"
                            style="width: 100%; text-align: left; padding: 10px 16px; background: none; border: none; color: #F43F5E; font-size: 14px; cursor: pointer; transition: background 0.2s;"
                            onmouseover="this.style.background='#27272A'" onmouseout="this.style.background='transparent'">
                            <svg style="width: 14px; height: 14px; display: inline-block; margin-right: 8px;" fill="none"
                                stroke="currentColor" viewBox="0 0 24 24">
                                <path stroke-linecap="round" stroke-linejoin="round" stroke-width="2"
                                    d="M19 7l-.867 12.142A2 2 0 0116.138 21H7.862a2 2 0 01-1.995-1.858L5 7m5 4v6m4-6v6m1-10V4a1 1 0 00-1-1h-4a1 1 0 00-1 1v3M4 7h16" />
                            </svg>
                            {{ __('Delete') }}
                        </button>
                    </form>
                </div>
            </div>
        </div>
    @endcanany

    <div style="display:flex; gap:16px;">
        <!-- Vote Section -->
        <div style="display:flex; flex-direction:column; align-items:center; gap:4px; min-width:60px;"
            onclick="event.stopPropagation()">
            <button onclick="vote({{ $question->id }}, 'upvote', this)"
                style="padding:4px; color:{{ $hasUpvoted ? '#10B981' : '#71717A' }}; background:none; border:none; cursor: pointer;">
                <svg style="width:20px; height:20px;" fill="{{ $hasUpvoted ? 'currentColor' : 'none' }}"
                    stroke="currentColor" viewBox="0 0 24 24">
                    <path stroke-linecap="round" stroke-linejoin="round" stroke-width="2" d="M5 15l7-7 7 7" />
                </svg>
            </button>

            <span id="vote-count-{{ $question->id }}"
                style="font-size:18px; font-weight:600; color:{{ $question->votes > 0 ? '#10B981' : ($question->votes < 0 ? '#F43F5E' : '#E4E4E7') }}">
                {{ $question->votes }}
            </span>

            <button onclick="vote({{ $question->id }}, 'downvote', this)"
                style="padding:4px; color:{{ $hasDownvoted ? '#F43F5E' : '#71717A' }}; background:none; border:none; cursor: pointer;">
                <svg style="width:20px; height:20px;" fill="{{ $hasDownvoted ? 'currentColor' : 'none' }}"
                    stroke="currentColor" viewBox="0 0 24 24">
                    <path stroke-linecap="round" stroke-linejoin="round" stroke-width="2" d="M19 9l-7 7-7-7" />
                </svg>
            </button>
        </div>

        <!-- Content -->
        <div style="flex:1;">
            <h3 style="color:#FAFAFA; font-size:18px; font-weight:500; margin-bottom:8px;">
                {{ $question->title }}
            </h3>

            <p
                style="color:#71717A; font-size:14px; margin-bottom:12px; display:-webkit-box; -webkit-line-clamp:2; -webkit-box-orient:vertical; overflow:hidden;">
                {{ $question->content }}
            </p>

            @if ($question->tags->count())
                <div style="display:flex; flex-wrap:wrap; gap:8px; margin-bottom:12px;"
                    onclick="event.stopPropagation()">
                    @foreach ($question->tags as $tag)
                        <a href="{{ route('questions.index', ['tag' => $tag->name]) }}"
                            style="padding:4px 10px; background:#27272A; color:#A1A1AA; font-size:12px; border-radius:6px; text-decoration:none; transition: all 0.2s;"
                            onmouseover="this.style.background='#3F3F46'; this.style.color='#D4D4D8'"
                            onmouseout="this.style.background='#27272A'; this.style.color='#A1A1AA'">
                            {{ $tag->name }}
                        </a>
                    @endforeach
                </div>
            @endif

            <div style="display:flex; justify-content:space-between; font-size:12px; color:#71717A;">
                <div style="display:flex; align-items:center; gap:16px;">
                    <span style="display:flex; align-items:center; gap:6px;">
                        <div
                            style="width:24px; height:24px; border-radius:50%; background:#3F3F46; display:flex; align-items:center; justify-content:center; font-size:10px;">
                            {{ strtoupper(substr($question->user->name ?? 'U', 0, 1)) }}
                        </div>
                        <span style="color: #FAFAFA; font-weight: 500;">{{ $question->user->name ?? 'Unknown' }}</span>
                        <span style="color: {{ $reputationColor }}; font-size: 12px; font-weight: 600;"
                            id="reputation-{{ $question->id }}">
                            {{ $question->user->reputation ?? 0 }}
                        </span>
                    </span>
                    <span style="display: flex; align-items: center; gap: 4px;">
                        <svg style="width: 12px; height: 12px;" fill="none" stroke="currentColor"
                            viewBox="0 0 24 24">
                            <path stroke-linecap="round" stroke-linejoin="round" stroke-width="2"
                                d="M12 8v4l3 3m6-3a9 9 0 11-18 0 9 9 0 0118 0z" />
                        </svg>
                        {{ $question->created_at->diffForHumans() }}
                    </span>
                </div>
            </div>
        </div>
    </div>
</div>

<script>
    function toggleDropdown(questionId) {
        const dropdown = document.getElementById(`dropdown-${questionId}`);
        const isVisible = dropdown.style.display === 'block';

        document.querySelectorAll('[id^="dropdown-"]').forEach(d => d.style.display = 'none');

        dropdown.style.display = isVisible ? 'none' : 'block';
    }

    document.addEventListener('click', function(event) {
        if (!event.target.closest('[id^="dropdown-"]') && !event.target.closest('button')) {
            document.querySelectorAll('[id^="dropdown-"]').forEach(d => d.style.display = 'none');
        }
    });
</script>
