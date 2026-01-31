@props(['categories'])

<ul class="category-tree space-y-1">
    @foreach ($categories as $category)
        <li class="pl-4 relative group">
            {{-- Optional arrow for categories with children --}}
            @if ($category->children->isNotEmpty())
                <span
                    class="absolute left-0 top-1.5 w-3 h-3 border-l-2 border-b-2 border-gray-400 rotate-45 transition-transform group-hover:rotate-90"></span>
            @endif

            <span class="font-medium text-white hover:text-green-400 cursor-pointer">
                {{ $category->name }}
            </span>

            @if ($category->children->isNotEmpty())
                <div class="ml-4 mt-1">
                    <x-categories.category-tree :categories="$category->children" />
                </div>
            @endif
        </li>
    @endforeach
</ul>
