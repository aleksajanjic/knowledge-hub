@include('components.question-modal')

<x-app-layout>
    <x-slot name="header">
        <h2 class="font-semibold text-xl text-white leading-tight">
            {{ __('Home') }}
        </h2>
    </x-slot>

    <div class="py-12">
        <div class="max-w-7xl mx-auto sm:px-6 lg:px-8">
            <div class="flex justify-between items-center mb-4">
                <div>
                    <h3 class="text-xl font-bold mt-8 text-white"> {{ __('All Questions') }}</h3>
                    <p
                        style="color: #71717A; font-size: 14px; margin-bottom: 12px; display: -webkit-box; -webkit-line-clamp: 2; -webkit-box-orient: vertical; overflow: hidden;">
                        {{ __('Number of Questions:') }} {{ $questions->count() }}
                    </p>
                </div>
                <div class="mb-4 flex justify-end">
                    <button type="button"
                        class="inline-flex items-center gap-2 px-4 py-4 text-zinc-900 font-bold rounded-xl hover:opacity-90 transition-colors"
                        style="background-color: #55b685;"
                        onclick="document.getElementById('question-modal').classList.remove('hidden')">
                        <!-- Plus icon -->
                        <svg xmlns="http://www.w3.org/2000/svg" class="h-5 w-5" fill="none" viewBox="0 0 24 24"
                            stroke="currentColor">
                            <path stroke-linecap="round" stroke-linejoin="round" stroke-width="2" d="M12 4v16m8-8H4" />
                        </svg>
                        {{ __('Ask a Question') }}
                    </button>
                </div>
            </div>

            <div class="space-y-4">
                @forelse($questions as $question)
                    <x-question-card :question="$question" />
                @empty
                    <p class="text-zinc-500 text-center py-8"> {{ __('No questions found') }}
                    </p>
                @endforelse
            </div>
        </div>
    </div>
</x-app-layout>
