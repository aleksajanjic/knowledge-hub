@props(['allTags'])

<div style="background: #27272A; border: 1px solid #3F3F46; border-radius: 12px; padding: 20px; margin-bottom: 20px;">
    <div style="display: flex; flex-wrap: wrap; gap: 16px; align-items: center;">
        <!-- Sort -->
        <div style="flex: 1; min-width: 150px;">
            <label
                style="display: block; color: #A1A1AA; font-size: 12px; margin-bottom: 6px; font-weight: 500;">{{ __('Sort by') }}</label>
            <select name="sort" onchange="applyFilter('sort', this.value)"
                style="width: 100%; padding: 8px 12px; background: #18181B; border: 1px solid #3F3F46; border-radius: 8px; color: #FAFAFA; font-size: 14px;">
                <option value="latest" {{ request('sort') == 'latest' ? 'selected' : '' }}>{{ __('Latest') }}</option>
                <option value="votes" {{ request('sort') == 'votes' ? 'selected' : '' }}>{{ __('Most Votes') }}</option>
                <option value="oldest" {{ request('sort') == 'oldest' ? 'selected' : '' }}>{{ __('Oldest') }}</option>
            </select>
        </div>

        <!-- Status -->
        <div style="flex: 1; min-width: 150px;">
            <label
                style="display: block; color: #A1A1AA; font-size: 12px; margin-bottom: 6px; font-weight: 500;">{{ __('Status') }}</label>
            <select name="status" onchange="applyFilter('status', this.value)"
                style="width: 100%; padding: 8px 12px; background: #18181B; border: 1px solid #3F3F46; border-radius: 8px; color: #FAFAFA; font-size: 14px;">
                <option value="">{{ __('All Questions') }}</option>
                @auth
                    <option value="bookmarked" {{ request('status') == 'bookmarked' ? 'selected' : '' }}>
                        {{ __('My Bookmarks') }}</option>
                @endauth
                <option value="unanswered" {{ request('status') == 'unanswered' ? 'selected' : '' }}>
                    {{ __('Unanswered') }}</option>
                <option value="answered" {{ request('status') == 'answered' ? 'selected' : '' }}>{{ __('Answered') }}
                </option>
                <option value="accepted" {{ request('status') == 'accepted' ? 'selected' : '' }}>
                    {{ __('Has Accepted Answer') }}</option>
            </select>
        </div>

        <!-- Date -->
        <div style="flex: 1; min-width: 150px;">
            <label
                style="display: block; color: #A1A1AA; font-size: 12px; margin-bottom: 6px; font-weight: 500;">{{ __('Time Period') }}</label>
            <select name="date" onchange="applyFilter('date', this.value)"
                style="width: 100%; padding: 8px 12px; background: #18181B; border: 1px solid #3F3F46; border-radius: 8px; color: #FAFAFA; font-size: 14px;">
                <option value="">{{ __('All Time') }}</option>
                <option value="today" {{ request('date') == 'today' ? 'selected' : '' }}>{{ __('Today') }}</option>
                <option value="week" {{ request('date') == 'week' ? 'selected' : '' }}>{{ __('This Week') }}
                </option>
                <option value="month" {{ request('date') == 'month' ? 'selected' : '' }}>{{ __('This Month') }}
                </option>
                <option value="year" {{ request('date') == 'year' ? 'selected' : '' }}>{{ __('This Year') }}
                </option>
            </select>
        </div>

        <!-- Tag Filter -->
        <div style="flex: 1; min-width: 200px;">
            <label
                style="display: block; color: #A1A1AA; font-size: 12px; margin-bottom: 6px; font-weight: 500;">{{ __('Filter by Tag') }}</label>
            <select name="tag" onchange="applyFilter('tag', this.value)"
                style="width: 100%; padding: 8px 12px; background: #18181B; border: 1px solid #3F3F46; border-radius: 8px; color: #FAFAFA; font-size: 14px;">
                <option value="">{{ __('All Tags') }}</option>
                @foreach ($allTags as $tag)
                    <option value="{{ $tag->name }}" {{ request('tag') == $tag->name ? 'selected' : '' }}>
                        {{ $tag->name }} ({{ $tag->questions_count }})
                    </option>
                @endforeach
            </select>
        </div>

        <!-- Clear Filters -->
        @if (request()->hasAny(['sort', 'status', 'date', 'tag']) || request('status') == 'bookmarked')
            <div style="flex: 0;">
                <label style="display: block; color: transparent; font-size: 12px; margin-bottom: 6px;">&nbsp;</label>
                <a href="{{ route('questions.index', ['search' => request('search')]) }}"
                    style="display: inline-flex; align-items: center; padding: 8px 16px; background: #F43F5E; color: white; border-radius: 8px; text-decoration: none; font-size: 14px; font-weight: 500; transition: all 0.2s;"
                    onmouseover="this.style.background='#DC2626'" onmouseout="this.style.background='#F43F5E'">
                    {{ __('Clear') }}
                </a>
            </div>
        @endif
    </div>

    <!-- Active Filters Display -->
    @if (request()->hasAny(['status', 'date', 'tag']))
        <div style="margin-top: 16px; padding-top: 16px; border-top: 1px solid #3F3F46;">
            <div style="display: flex; flex-wrap: wrap; gap: 8px; align-items: center;">
                <span style="color: #A1A1AA; font-size: 13px; font-weight: 500;">{{ __('Active filters:') }}</span>

                @if (request('status') && request('status') !== 'bookmarked')
                    <span
                        style="padding: 4px 10px; background: #10B981; color: #18181B; font-size: 12px; font-weight: 600; border-radius: 6px;">
                        {{ __(ucfirst(request('status'))) }}
                    </span>
                @endif

                @if (request('date'))
                    <span
                        style="padding: 4px 10px; background: #3B82F6; color: white; font-size: 12px; font-weight: 600; border-radius: 6px;">
                        {{ __(ucfirst(request('date'))) }}
                    </span>
                @endif

                @if (request('tag'))
                    <span
                        style="padding: 4px 10px; background: #8B5CF6; color: white; font-size: 12px; font-weight: 600; border-radius: 6px;">
                        {{ request('tag') }}
                    </span>
                @endif

                @if (request('status') == 'bookmarked')
                    <span
                        style="padding: 4px 10px; background: #F59E0B; color: #18181B; font-size: 12px; font-weight: 600; border-radius: 6px;">
                        {{ __('My Bookmarks') }}
                    </span>
                @endif
            </div>
        </div>
    @endif
</div>

<script>
    function applyFilter(param, value) {
        const url = new URL(window.location.href);

        if (value === '') {
            url.searchParams.delete(param);
        } else {
            url.searchParams.set(param, value);
        }

        url.searchParams.delete('page');

        window.location.href = url.toString();
    }
</script>
