<x-app-layout>
    <x-slot name="header">
        <h2 class="font-semibold text-xl text-white leading-tight">
            {{ __('Admin Dashboard') }}
        </h2>
    </x-slot>

    <div class="py-12">
        <div class="max-w-7xl mx-auto sm:px-6 lg:px-8">
            <!-- Tabs -->
            <div style="background: #27272A; border: 1px solid #3F3F46; border-radius: 12px; overflow: hidden;">
                <!-- Tab Headers -->
                <div style="display: flex; border-bottom: 1px solid #3F3F46;">
                    <button onclick="switchTab('users')" id="tab-users"
                        style="flex: 1; padding: 16px; background: #18181B; color: #10B981; border: none; cursor: pointer; font-weight: 600; border-bottom: 2px solid #10B981;">
                        {{ __('Users') }}
                    </button>
                    <button onclick="switchTab('categories')" id="tab-categories"
                        style="flex: 1; padding: 16px; background: transparent; color: #A1A1AA; border: none; cursor: pointer; font-weight: 600;">
                        {{ __('Categories') }}
                    </button>
                    <button onclick="switchTab('tags')" id="tab-tags"
                        style="flex: 1; padding: 16px; background: transparent; color: #A1A1AA; border: none; cursor: pointer; font-weight: 600;">
                        {{ __('Tags') }}
                    </button>
                </div>

                <!-- Tab Content -->
                <div style="padding: 24px;">
                    <!-- Users Tab -->
                    <div id="content-users">
                        <h3 style="color: #FAFAFA; font-size: 20px; font-weight: 600; margin-bottom: 16px;">
                            {{ __('User Management') }}</h3>
                        <!-- User list will go here -->
                        <p style="color: #71717A;">Users content...</p>
                    </div>

                    <!-- Categories Tab -->
                    <div id="content-categories" style="display: none;">
                        <h3 style="color: #FAFAFA; font-size: 20px; font-weight: 600; margin-bottom: 16px;">
                            {{ __('Category Management') }}</h3>
                        <!-- Category list will go here -->
                        <p style="color: #71717A;">Categories content...</p>
                    </div>

                    <!-- Tags Tab -->
                    <div id="content-tags" style="display: none;">
                        <h3 style="color: #FAFAFA; font-size: 20px; font-weight: 600; margin-bottom: 16px;">
                            {{ __('Tag Management') }}</h3>
                        <!-- Tag list will go here -->
                        <p style="color: #71717A;">Tags content...</p>
                    </div>
                </div>
            </div>
        </div>
    </div>

    <script>
        function switchTab(tabName) {
            // Hide all content
            document.querySelectorAll('[id^="content-"]').forEach(el => el.style.display = 'none');

            // Reset all tab buttons
            document.querySelectorAll('[id^="tab-"]').forEach(btn => {
                btn.style.background = 'transparent';
                btn.style.color = '#A1A1AA';
                btn.style.borderBottom = 'none';
            });

            // Show selected content
            document.getElementById(`content-${tabName}`).style.display = 'block';

            // Highlight selected tab
            const activeTab = document.getElementById(`tab-${tabName}`);
            activeTab.style.background = '#18181B';
            activeTab.style.color = '#10B981';
            activeTab.style.borderBottom = '2px solid #10B981';
        }
    </script>
</x-app-layout>
