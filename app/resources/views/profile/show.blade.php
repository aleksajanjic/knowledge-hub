<x-app-layout>
    <x-slot name="header">
        <h2 class="font-semibold text-xl text-gray-800 dark:text-gray-200 leading-tight">
            {{ $user->name }}'s Profile
        </h2>
    </x-slot>

    <div class="py-12">
        <div class="max-w-7xl mx-auto sm:px-6 lg:px-8 space-y-6">

            {{-- User Info & Reputation Card --}}
            <div class="bg-white dark:bg-gray-800 overflow-hidden shadow-sm sm:rounded-lg">
                <div class="p-6">
                    <div class="flex items-start justify-between">
                        <div>
                            <h3 class="text-2xl font-bold text-gray-900 dark:text-gray-100">
                                {{ $user->name }}
                            </h3>
                            <p class="text-sm text-gray-500 dark:text-gray-500 mt-2">
                                Member since {{ $user->created_at->format('F Y') }}
                            </p>
                        </div>
                        <div class="text-right">
                            <div class="text-4xl font-bold text-blue-600 dark:text-blue-400">
                                {{ number_format($user->reputation) }}
                            </div>
                            <div class="text-sm text-gray-600 dark:text-gray-400">reputation</div>
                        </div>
                    </div>
                </div>
            </div>

            {{-- Stats Grid --}}
            <div class="grid grid-cols-1 md:grid-cols-4 gap-4 flex justify-between">
                <div class="bg-white dark:bg-gray-800 overflow-hidden shadow-sm sm:rounded-lg p-6">
                    <div class="text-3xl font-bold text-gray-900 dark:text-gray-100">
                        {{ $stats['questions_asked'] }}
                    </div>
                    <div class="text-sm text-gray-600 dark:text-gray-400 mt-1">Questions Asked</div>
                </div>

                <div class="bg-white dark:bg-gray-800 overflow-hidden shadow-sm sm:rounded-lg p-6">
                    <div class="text-3xl font-bold text-gray-900 dark:text-gray-100">
                        {{ $stats['answers_given'] }}
                    </div>
                    <div class="text-sm text-gray-600 dark:text-gray-400 mt-1">Answers Given</div>
                </div>

                <div class="bg-white dark:bg-gray-800 overflow-hidden shadow-sm sm:rounded-lg p-6">
                    <div class="text-3xl font-bold text-green-600 dark:text-green-400">
                        {{ $stats['accepted_answers'] }}
                    </div>
                    <div class="text-sm text-gray-600 dark:text-gray-400 mt-1">Accepted Answers</div>
                </div>

                <div class="bg-white dark:bg-gray-800 overflow-hidden shadow-sm sm:rounded-lg p-6">
                    <div class="text-3xl font-bold text-purple-600 dark:text-purple-400">
                        {{ $stats['total_upvotes'] }}
                    </div>
                    <div class="text-sm text-gray-600 dark:text-gray-400 mt-1">Total Upvotes</div>
                </div>
            </div>

        </div>
    </div>
</x-app-layout>
