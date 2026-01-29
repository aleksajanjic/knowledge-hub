@props(['question'])

<div
    style="background: rgba(24, 24, 27, 0.5); border: 1px solid #27272A; border-radius: 12px; padding: 20px; margin-bottom: 16px; cursor: pointer; transition: all 0.2s;">
    <div style="display: flex; gap: 16px;">
        <!-- Vote Section -->
        <div style="display: flex; flex-direction: column; align-items: center; gap: 4px; min-width: 60px;">
            <button
                style="padding: 4px; color: #71717A; background: none; border: none; cursor: pointer; transition: color 0.2s;"
                onmouseover="this.style.color='#10B981'" onmouseout="this.style.color='#71717A'">
                <svg style="width: 20px; height: 20px;" fill="none" stroke="currentColor" viewBox="0 0 24 24">
                    <path stroke-linecap="round" stroke-linejoin="round" stroke-width="2" d="M5 15l7-7 7 7" />
                </svg>
            </button>
            <span style="font-size: 18px; font-weight: 600; color: #E4E4E7;">0</span>
            <button
                style="padding: 4px; color: #71717A; background: none; border: none; cursor: pointer; transition: color 0.2s;"
                onmouseover="this.style.color='#F43F5E'" onmouseout="this.style.color='#71717A'">
                <svg style="width: 20px; height: 20px;" fill="none" stroke="currentColor" viewBox="0 0 24 24">
                    <path stroke-linecap="round" stroke-linejoin="round" stroke-width="2" d="M19 9l-7 7-7-7" />
                </svg>
            </button>
        </div>

        <!-- Content -->
        <div style="flex: 1;">
            <h3 style="color: #FAFAFA; font-size: 18px; font-weight: 500; margin-bottom: 8px; transition: color 0.2s;"
                onmouseover="this.style.color='#10B981'" onmouseout="this.style.color='#FAFAFA'">
                {{ $question->title }}
            </h3>

            <p
                style="color: #71717A; font-size: 14px; margin-bottom: 12px; display: -webkit-box; -webkit-line-clamp: 2; -webkit-box-orient: vertical; overflow: hidden;">
                {{ $question->content }}
            </p>

            <!-- Tags -->
            @if ($question->tags->count() > 0)
                <div style="display: flex; flex-wrap: wrap; gap: 8px; margin-bottom: 12px;">
                    @foreach ($question->tags as $tag)
                        <span
                            style="padding: 4px 10px; background: #27272A; color: #A1A1AA; font-size: 12px; border-radius: 6px; transition: all 0.2s;"
                            onmouseover="this.style.background='#3F3F46'; this.style.color='#D4D4D8'"
                            onmouseout="this.style.background='#27272A'; this.style.color='#A1A1AA'">
                            {{ $tag->name }}
                        </span>
                    @endforeach
                </div>
            @endif

            <!-- Meta Info -->
            <div
                style="display: flex; align-items: center; justify-content: space-between; font-size: 12px; color: #71717A;">
                <div style="display: flex; align-items: center; gap: 16px;">
                    <span style="display: flex; align-items: center; gap: 6px;">
                        <div
                            style="width: 24px; height: 24px; border-radius: 50%; background: #3F3F46; color: #A1A1AA; display: flex; align-items: center; justify-content: center; font-size: 10px; font-weight: 500;">
                            {{ strtoupper(substr($question->user->name ?? 'U', 0, 1)) }}
                        </div>
                        {{ $question->user->name ?? 'Unknown' }}
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
                <div style="display: flex; align-items: center; gap: 16px;">
                    <span style="display: flex; align-items: center; gap: 4px;">
                        <svg style="width: 12px; height: 12px;" fill="none" stroke="currentColor"
                            viewBox="0 0 24 24">
                            <path stroke-linecap="round" stroke-linejoin="round" stroke-width="2"
                                d="M8 12h.01M12 12h.01M16 12h.01M21 12c0 4.418-4.03 8-9 8a9.863 9.863 0 01-4.255-.949L3 20l1.395-3.72C3.512 15.042 3 13.574 3 12c0-4.418 4.03-8 9-8s9 3.582 9 8z" />
                        </svg>
                        0
                    </span>
                    <span style="display: flex; align-items: center; gap: 4px;">
                        <svg style="width: 12px; height: 12px;" fill="none" stroke="currentColor"
                            viewBox="0 0 24 24">
                            <path stroke-linecap="round" stroke-linejoin="round" stroke-width="2"
                                d="M15 12a3 3 0 11-6 0 3 3 0 016 0z" />
                            <path stroke-linecap="round" stroke-linejoin="round" stroke-width="2"
                                d="M2.458 12C3.732 7.943 7.523 5 12 5c4.478 0 8.268 2.943 9.542 7-1.274 4.057-5.064 7-9.542 7-4.477 0-8.268-2.943-9.542-7z" />
                        </svg>
                        0
                    </span>
                </div>
            </div>
        </div>
    </div>
</div>
