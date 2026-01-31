@include('components.questions.question-modal')
@include('components.questions.question-detail-modal')
@props(['categories'])

<x-app-layout>
    <x-slot name="header">
        <h2 class="font-semibold text-xl text-white leading-tight">
            {{ __('Home') }}
        </h2>
    </x-slot>
    <div class="py-12">
        <div class="max-w-7xl mx-auto sm:px-6 lg:px-8">
            <div class="flex justify-between items-center mb-4">
                <h3 class="text-xl font-bold mt-8 text-white">
                    {{ __('All Questions') }}
                    <span style="color: #71717A; font-size: 16px; font-weight: normal;">
                        ({{ $questions->total() }})
                    </span>
                </h3>
                <div class="mb-4 flex justify-end">
                    <button type="button"
                        class="inline-flex items-center gap-2 px-4 py-4 text-zinc-900 font-bold rounded-xl hover:opacity-90 transition-colors"
                        style="background-color: #55b685;" onclick="openQuestionCreateModal()">
                        <svg xmlns="http://www.w3.org/2000/svg" class="h-5 w-5" fill="none" viewBox="0 0 24 24"
                            stroke="currentColor">
                            <path stroke-linecap="round" stroke-linejoin="round" stroke-width="2" d="M12 4v16m8-8H4" />
                        </svg>
                        {{ __('Ask Question') }}
                    </button>
                </div>
            </div>

            <!-- Search Bar Component -->
            <x-questions.search-bar />

            <!-- Filter Component -->
            <x-questions.filters :allTags="$allTags" />

            <!-- Results Message -->
            @if (request('search'))
                <div
                    style="margin-bottom: 16px; padding: 12px 16px; background: #27272A; border: 1px solid #3F3F46; border-radius: 8px; color: #A1A1AA; font-size: 14px;">
                    Found {{ $questions->total() }} result(s) for "<span
                        style="color: #FAFAFA; font-weight: 500;">{{ request('search') }}</span>"
                </div>
            @endif

            <!-- Category Tree -->
            <div class="mb-6">
                <h3 class="text-lg font-bold text-white mb-2">Categories</h3>
                <x-categories.category-tree :categories="$categories" />
            </div>

            <!-- Questions List -->
            <x-questions.questions-list :questions="$questions" />

            <x-layout.pagination :paginator="$questions" />
        </div>
    </div>
</x-app-layout>
