@props(['activities'])

@php
    $actionLabels = [
        'question_created' => __('asked a question'),
        'question_updated' => __('updated a question'),
        'question_deleted' => __('deleted a question'),
        'answer_created' => __('answered a question'),
        'answer_updated' => __('edited an answer'),
        'answer_accepted' => __('accepted an answer'),
    ];
@endphp

@if ($activities->isNotEmpty())
    <div class="mb-6 mt-6">
        <h3 class="text-lg font-bold text-white mb-2">{{ __('Recent Activity') }}</h3>
        <div
            style="background: #18181B; border: 1px solid #3F3F46; border-radius: 8px; overflow: hidden; margin-right: 5px;">
            @foreach ($activities as $activity)
                <div
                    style="padding: 12px 16px; border-bottom: 1px solid #27272A; font-size: 14px;{{ $loop->last ? ' border-bottom: none;' : '' }}">
                    <span style="color: #FAFAFA; font-weight: 500;">{{ $activity->user?->name ?? __('System') }}</span>
                    <span style="color: #A1A1AA;">{{ $actionLabels[$activity->action] ?? $activity->action }}</span>
                    @if ($activity->description)
                        <span style="color: #71717A;">"{{ Str::limit($activity->description, 40) }}"</span>
                    @endif
                    <span style="color: #52525B; font-size: 12px;">{{ $activity->created_at->diffForHumans() }}</span>
                </div>
            @endforeach
        </div>
    </div>
@endif
