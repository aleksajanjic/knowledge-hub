@props(['answer', 'question'])

@php
    use App\Helpers\MarkdownHelper;
    $userVote = auth()->check() ? $answer->userVote(auth()->id()) : null;
    $hasUpvoted = $userVote && $userVote->vote == 1;
    $hasDownvoted = $userVote && $userVote->vote == -1;
    $isQuestionOwner = auth()->check() && $question->user_id === auth()->id();
    $reputationColor = ($question->user->reputation ?? 0) > 0 ? '#10B981' : '#F43F5E';
@endphp

<div class="answer-container {{ $answer->is_accepted ? 'accepted' : '' }}"
    style="display: flex; gap: 20px; padding: 20px;
           background: {{ $answer->is_accepted ? 'rgba(16, 185, 129, 0.05)' : 'transparent' }};
           border: 1px solid {{ $answer->is_accepted ? '#10B981' : '#27272A' }};
           border-radius: 12px; margin-bottom: 16px; position: relative;">

    <!-- Vote Section -->
    <div style="display: flex; flex-direction: column; align-items: center; gap: 6px; min-width: 50px;">
        <button data-type="upvote" data-voted="{{ $hasUpvoted ? '1' : '0' }}"
            onclick="voteAnswer(event, {{ $answer->id }}, 'upvote', this)"
            style="padding:8px;color:{{ $hasUpvoted ? '#10B981' : '#71717A' }};background:none;border:none;cursor:pointer;transition:color .2s;"
            onmouseover="if(this.dataset.voted==='0') this.style.color='#10B981'"
            onmouseout="if(this.dataset.voted==='0') this.style.color='#71717A'">
            <svg style="width:28px;height:28px;" fill="{{ $hasUpvoted ? 'currentColor' : 'none' }}" stroke="currentColor"
                viewBox="0 0 24 24">
                <path stroke-linecap="round" stroke-linejoin="round" stroke-width="2" d="M5 15l7-7 7 7" />
            </svg>
        </button>

        <span id="answer-vote-count-{{ $answer->id }}"
            style="font-size:20px;font-weight:700;color:{{ $answer->votes > 0 ? '#10B981' : ($answer->votes < 0 ? '#F43F5E' : '#E4E4E7') }};">
            {{ $answer->votes_count }}
        </span>

        <button data-type="downvote" data-voted="{{ $hasDownvoted ? '1' : '0' }}"
            onclick="voteAnswer(event, {{ $answer->id }}, 'downvote', this)"
            style="padding:8px;color:{{ $hasDownvoted ? '#F43F5E' : '#71717A' }}; background:none; border:none; cursor:pointer; transition:color .2s;"
            onmouseover="if(this.dataset.voted==='0') this.style.color='#F43F5E'"
            onmouseout="if(this.dataset.voted==='0') this.style.color='#71717A'">
            <svg style="width:28px;height:28px;" fill="{{ $hasDownvoted ? 'currentColor' : 'none' }}"
                stroke="currentColor" viewBox="0 0 24 24">
                <path stroke-linecap="round" stroke-linejoin="round" stroke-width="2" d="M19 9l-7 7-7-7" />
            </svg>
        </button>

        @if ($isQuestionOwner)
            <button id="accept-btn-{{ $answer->id }}"
                onclick="acceptAnswer({{ $question->id }}, {{ $answer->id }}, this)"
                style="margin-top: 8px; padding: 6px; color: {{ $answer->is_accepted ? '#10B981' : '#71717A' }}; background: none; border: none; cursor: pointer; transition: color 0.2s;"
                title="{{ $answer->is_accepted ? 'Accepted answer' : 'Mark as accepted' }}"
                onmouseover="if(!this.style.color.includes('10B981')) this.style.color='#10B981'"
                onmouseout="if(!{{ $answer->is_accepted ? 'true' : 'false' }}) this.style.color='#71717A'">
                <svg style="width: 28px; height: 28px;" fill="{{ $answer->is_accepted ? 'currentColor' : 'none' }}"
                    stroke="currentColor" viewBox="0 0 24 24">
                    <path stroke-linecap="round" stroke-linejoin="round" stroke-width="2"
                        d="M9 12l2 2 4-4m6 2a9 9 0 11-18 0 9 9 0 0118 0z" />
                </svg>
            </button>
        @endif
    </div>

    <!-- Three-dot menu -->
    @canany(['update', 'delete'], $answer)
        <div style="position:absolute; top:12px; right:12px;" onclick="event.stopPropagation()">
            <div style="position: relative; display: inline-block;">
                <button onclick="toggleDropdown({{ $answer->id }})"
                    class="p-2 rounded text-zinc-400 hover:text-zinc-200 hover:bg-zinc-800 transition-colors"
                    style="background: none; border: none; cursor: pointer;">
                    <svg xmlns="http://www.w3.org/2000/svg" class="w-5 h-5" fill="white" viewBox="0 0 20 20">
                        <path
                            d="M10 3a1.5 1.5 0 110 3 1.5 1.5 0 010-3zm0 5a1.5 1.5 0 110 3 1.5 1.5 0 010-3zm0 5a1.5 1.5 0 110 3 1.5 1.5 0 010-3z" />
                    </svg>
                </button>

                <div id="dropdown-{{ $answer->id }}"
                    style="display:none; position:absolute; right:0; top:100%; margin-top:4px; background:#18181B; border:1px solid #3F3F46; border-radius:8px; min-width:150px; box-shadow:0 4px 6px rgba(0,0,0,0.3); z-index:50;">
                    @can('update', $answer)
                        <a href="#" onclick="event.preventDefault(); openEditModal({{ $answer->id }}, 'answer')"
                            style="display:block; padding:10px 16px; color:#FAFAFA; text-decoration:none; font-size:14px;"
                            onmouseover="this.style.background='#27272A'" onmouseout="this.style.background='transparent'">
                            Edit
                        </a>
                    @endcan
                    @can('delete', $answer)
                        <form method="POST" action="{{ route('answers.destroy', $answer) }}"
                            onsubmit="return confirm('Are you sure you want to delete this answer?')">
                            @csrf
                            @method('DELETE')
                            <button type="submit"
                                style="width:100%; text-align:left; padding:10px 16px; background:none; border:none; color:#F43F5E; font-size:14px; cursor:pointer;"
                                onmouseover="this.style.background='#27272A'" onmouseout="this.style.background='transparent'">
                                Delete
                            </button>
                        </form>
                    @endcan
                </div>
            </div>
        </div>
    @endcanany

    <!-- Content -->
    <div style="flex: 1;">
        <div class="markdown-content" style="color: #D4D4D8; font-size: 15px; line-height: 1.7; margin-bottom: 12px;">
            {!! MarkdownHelper::parse($answer->body) !!}
        </div>

        <!-- Meta -->
        <div style="display: flex; align-items: center; justify-content: space-between;">
            <div style="display: flex; align-items: center; gap: 8px;">
                @if ($answer->user && $answer->user->profile_image)
                    <img src="{{ asset('storage/' . $answer->user->profile_image) }}" alt="{{ $answer->user->name }}"
                        style="width:28px; height:28px; border-radius:50%; object-cover; border:1px solid #3F3F46;">
                @else
                    <div
                        style="width:28px; height:28px; border-radius:50%; background:#3F3F46; color:#A1A1AA; display:flex; align-items:center; justify-content:center; font-size:11px; font-weight:600;">
                        {{ strtoupper(substr($answer->user->name ?? 'U', 0, 1)) }}
                    </div>
                @endif

                <span style="color: #FAFAFA; font-weight: 500; font-size: 14px;">
                    {{ $answer->user->name ?? 'Unknown' }}
                </span>
                <span style="color: {{ $reputationColor }}; font-size: 12px; font-weight: 600;"
                    id="answer-reputation-{{ $answer->id }}">
                    {{ $answer->user->reputation ?? 0 }}
                </span>
                <span style="color: #71717A; font-size: 13px;">
                    {{ $answer->created_at->diffForHumans() }}
                </span>
            </div>
        </div>
    </div>
</div>
