@props(['categories'])

<div class="category-tree space-y-1">
    <ul>
        @foreach ($categories as $category)
            <li class="relative pl-4 group">
                @if ($category->children->isNotEmpty())
                    <button type="button"
                        class="absolute left-0 top-1.5 w-5 h-5 flex items-center justify-center focus:outline-none">
                        <span x-bind:class="openCategories[{{ $category->id }}] ? 'rotate-90' : 'rotate-45'"
                            class="inline-block w-3 h-3 border-l-2 border-b-2 border-gray-400 transition-transform duration-200"></span>
                    </button>
                @endif

                <a href="{{ route('questions.index', array_merge(request()->query(), ['category' => $category->id])) }}"
                    class="font-medium pl-6 inline-block {{ request('category') == $category->id ? 'text-green-400' : 'text-white hover:text-green-400' }}">
                    {{ $category->name }}
                </a>

                @if ($category->children->isNotEmpty())
                    <div x-show="openCategories[{{ $category->id }}]" x-transition class="ml-4 mt-1 overflow-hidden">
                        <x-categories.category-tree :categories="$category->children" />
                    </div>
                @endif
            </li>
        @endforeach
    </ul>
</div>
