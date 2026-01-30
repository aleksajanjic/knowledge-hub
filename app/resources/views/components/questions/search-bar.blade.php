<div style="margin-bottom: 24px;">
    <form action="{{ route('questions.index') }}" method="GET">
        <div style="position: relative;">
            <input type="text" name="search" value="{{ request('search') }}" placeholder="Search questions..."
                style="width: 100%; padding: 14px 48px 14px 48px; background: #27272A; border: 1px solid #3F3F46; border-radius: 12px; color: white; font-size: 15px; transition: all 0.2s;"
                onfocus="this.style.borderColor='#10B981'; this.style.background='#18181B';"
                onblur="this.style.borderColor='#3F3F46'; this.style.background='#27272A';" />

            <!-- Search Icon -->
            <svg style="position: absolute; left: 16px; top: 50%; transform: translateY(-50%); width: 20px; height: 20px; color: #71717A; pointer-events: none;"
                fill="none" stroke="currentColor" viewBox="0 0 24 24">
                <path stroke-linecap="round" stroke-linejoin="round" stroke-width="2"
                    d="M21 21l-6-6m2-5a7 7 0 11-14 0 7 7 0 0114 0z" />
            </svg>

            <!-- Clear Button (only show if there's a search) -->
            @if (request('search'))
                <a href="{{ route('questions.index') }}"
                    style="position: absolute; right: 16px; top: 50%; transform: translateY(-50%); color: #71717A; text-decoration: none; transition: color 0.2s;"
                    onmouseover="this.style.color='#FAFAFA'" onmouseout="this.style.color='#71717A'">
                    <svg style="width: 20px; height: 20px;" fill="none" stroke="currentColor" viewBox="0 0 24 24">
                        <path stroke-linecap="round" stroke-linejoin="round" stroke-width="2"
                            d="M6 18L18 6M6 6l12 12" />
                    </svg>
                </a>
            @endif
        </div>

        <!-- Filter Tags (Optional) -->
        @if (request('tag'))
            <div style="margin-top: 12px; display: flex; align-items: center; gap: 8px;">
                <span style="color: #A1A1AA; font-size: 14px;">Filtering by tag:</span>
                <span
                    style="padding: 4px 10px; background: #10B981; color: #18181B; font-size: 12px; border-radius: 6px; font-weight: 500;">
                    {{ request('tag') }}
                </span>
            </div>
        @endif
    </form>
</div>
