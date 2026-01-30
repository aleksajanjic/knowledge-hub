<x-app-layout>
    <x-slot name="header">
        <h2 class="font-semibold text-xl text-white leading-tight">
            {{ __('Admin Dashboard') }}
        </h2>
    </x-slot>

    <div class="py-12">
        <div class="max-w-7xl mx-auto sm:px-6 lg:px-8">
            <div style="background: #27272A; border: 1px solid #3F3F46; border-radius: 12px; overflow: hidden;">
                <!-- Tab Headers -->
                <div style="display: flex; border-bottom: 1px solid #3F3F46;">
                    <button onclick="loadTab('users')" id="tab-users"
                        style="flex: 1; padding: 16px; background: #18181B; color: #10B981; border: none; cursor: pointer; font-weight: 600; border-bottom: 2px solid #10B981;">
                        {{ __('Users') }}
                    </button>
                    <button onclick="loadTab('categories')" id="tab-categories"
                        style="flex: 1; padding: 16px; background: transparent; color: #A1A1AA; border: none; cursor: pointer; font-weight: 600;">
                        {{ __('Categories') }}
                    </button>
                    <button onclick="loadTab('tags')" id="tab-tags"
                        style="flex: 1; padding: 16px; background: transparent; color: #A1A1AA; border: none; cursor: pointer; font-weight: 600;">
                        {{ __('Tags') }}
                    </button>
                </div>

                <!-- Tab Content -->
                <div style="padding: 24px;">
                    <div id="tab-content">
                        <div style="text-align: center; padding: 48px; color: #71717A;">
                            <svg style="width: 48px; height: 48px; margin: 0 auto; color: #10B981; animation: spin 1s linear infinite;"
                                fill="none" stroke="currentColor" viewBox="0 0 24 24">
                                <path stroke-linecap="round" stroke-linejoin="round" stroke-width="2"
                                    d="M4 4v5h.582m15.356 2A8.001 8.001 0 004.582 9m0 0H9m11 11v-5h-.581m0 0a8.003 8.003 0 01-15.357-2m15.357 2H15" />
                            </svg>
                            <p style="margin-top: 16px;">{{ __('Loading...') }}</p>
                        </div>
                    </div>
                </div>
            </div>
        </div>
    </div>
</x-app-layout>

@include('admin.modals.tag-modals')

<style>
    @keyframes spin {
        from {
            transform: rotate(0deg);
        }

        to {
            transform: rotate(360deg);
        }
    }
</style>
