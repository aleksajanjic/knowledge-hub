@foreach ($categories as $category)
    <li>
        <a href="{{ route('home', ['category' => $category->id]) }}">
            {{ $category->name }}
        </a>

        @if ($category->children->count())
            <ul>
                @include('categories.partials.tree', ['categories' => $category->children])
            </ul>
        @endif
    </li>
@endforeach
