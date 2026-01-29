@props(['paginator'])

@if ($paginator->hasPages())
    <div style="display: flex; justify-content: center; align-items: center; gap: 8px; margin-top: 32px;">
        {{-- Previous Button --}}
        <a href="{{ $paginator->onFirstPage() ? '#' : $paginator->appends(request()->query())->previousPageUrl() }}"
            style="padding: 10px 16px; background: #27272A; border: 1px solid #3F3F46; color: {{ $paginator->onFirstPage() ? '#52525B' : '#FAFAFA' }}; border-radius: 8px; cursor: {{ $paginator->onFirstPage() ? 'not-allowed' : 'pointer' }}; font-size: 14px; text-decoration: none; transition: all 0.2s; {{ $paginator->onFirstPage() ? 'pointer-events: none;' : '' }}"
            onmouseover="if(!{{ $paginator->onFirstPage() ? 'true' : 'false' }}) this.style.background='#3F3F46'"
            onmouseout="if(!{{ $paginator->onFirstPage() ? 'true' : 'false' }}) this.style.background='#27272A'">
            ← {{ __('Previous') }}
        </a>

        {{-- Page Numbers --}}
        @php
            $currentPage = $paginator->currentPage();
            $lastPage = $paginator->lastPage();
            $start = max(1, $currentPage - 2);
            $end = min($lastPage, $currentPage + 2);
        @endphp

        {{-- First Page --}}
        @if ($start > 1)
            <a href="{{ $paginator->appends(request()->query())->url(1) }}"
                style="padding: 10px 16px; background: #27272A; border: 1px solid #3F3F46; color: #FAFAFA; border-radius: 8px; cursor: pointer; font-size: 14px; text-decoration: none; transition: all 0.2s;"
                onmouseover="this.style.background='#3F3F46'" onmouseout="this.style.background='#27272A'">
                1
            </a>
            @if ($start > 2)
                <span style="padding: 10px 16px; color: #71717A; font-size: 14px;">...</span>
            @endif
        @endif

        {{-- Page Range --}}
        @for ($page = $start; $page <= $end; $page++)
            @if ($page == $currentPage)
                <span
                    style="padding: 10px 16px; background: #10B981; color: #18181B; border-radius: 8px; font-size: 14px; font-weight: 600;">
                    {{ $page }}
                </span>
            @else
                <a href="{{ $paginator->appends(request()->query())->url($page) }}"
                    style="padding: 10px 16px; background: #27272A; border: 1px solid #3F3F46; color: #FAFAFA; border-radius: 8px; cursor: pointer; font-size: 14px; text-decoration: none; transition: all 0.2s;"
                    onmouseover="this.style.background='#3F3F46'" onmouseout="this.style.background='#27272A'">
                    {{ $page }}
                </a>
            @endif
        @endfor

        {{-- Last Page --}}
        @if ($end < $lastPage)
            @if ($end < $lastPage - 1)
                <span style="padding: 10px 16px; color: #71717A; font-size: 14px;">...</span>
            @endif
            <a href="{{ $paginator->appends(request()->query())->url($lastPage) }}"
                style="padding: 10px 16px; background: #27272A; border: 1px solid #3F3F46; color: #FAFAFA; border-radius: 8px; cursor: pointer; font-size: 14px; text-decoration: none; transition: all 0.2s;"
                onmouseover="this.style.background='#3F3F46'" onmouseout="this.style.background='#27272A'">
                {{ $lastPage }}
            </a>
        @endif

        {{-- Next Button --}}
        <a href="{{ $paginator->hasMorePages() ? $paginator->appends(request()->query())->nextPageUrl() : '#' }}"
            style="padding: 10px 16px; background: #27272A; border: 1px solid #3F3F46; color: {{ $paginator->hasMorePages() ? '#FAFAFA' : '#52525B' }}; border-radius: 8px; cursor: {{ $paginator->hasMorePages() ? 'pointer' : 'not-allowed' }}; font-size: 14px; text-decoration: none; transition: all 0.2s; {{ $paginator->hasMorePages() ? '' : 'pointer-events: none;' }}"
            onmouseover="if({{ $paginator->hasMorePages() ? 'true' : 'false' }}) this.style.background='#3F3F46'"
            onmouseout="if({{ $paginator->hasMorePages() ? 'true' : 'false' }}) this.style.background='#27272A'">
            {{ __('Next') }} →
        </a>
    </div>

    {{-- Page Info --}}
    <div style="text-align: center; margin-top: 16px; color: #71717A; font-size: 14px;">
        Showing {{ $paginator->firstItem() }} to {{ $paginator->lastItem() }} of {{ $paginator->total() }} questions
    </div>
@endif
