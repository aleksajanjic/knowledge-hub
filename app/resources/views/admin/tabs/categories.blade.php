<div>
    <div style="display: flex; justify-content: space-between; align-items: center; margin-bottom: 20px;">
        <h3 style="color: #FAFAFA; font-size: 20px; font-weight: 600;">{{ __('Category Management') }}</h3>
        <button onclick="openCreateCategoryModal()"
            style="padding: 10px 20px; background: #10B981; color: #18181B; border: none; border-radius: 8px; cursor: pointer; font-weight: 600; font-size: 14px;">
            + {{ __('Add Category') }}
        </button>
    </div>

    <div style="overflow-x: auto;">
        @if ($categories->count())
            <table style="width: 100%; border-collapse: collapse;">
                <thead>
                    <tr style="border-bottom: 1px solid #3F3F46;">
                        <th
                            style="padding: 12px; text-align: left; color: #A1A1AA; font-size: 12px; font-weight: 600; text-transform: uppercase;">
                            {{ __('ID') }}</th>
                        <th
                            style="padding: 12px; text-align: left; color: #A1A1AA; font-size: 12px; font-weight: 600; text-transform: uppercase;">
                            {{ __('Name') }}</th>
                        <th
                            style="padding: 12px; text-align: right; color: #A1A1AA; font-size: 12px; font-weight: 600; text-transform: uppercase;">
                            {{ __('Actions') }}</th>
                    </tr>
                </thead>
                <tbody>
                    @foreach ($categories as $category)
                        <tr style="border-bottom: 1px solid #3F3F46;">
                            <td style="padding: 12px; color: #FAFAFA;">{{ $category->id }}</td>
                            <td style="padding: 12px; color: #FAFAFA;">{{ $category->name }}</td>
                            <td style="padding: 12px; text-align: right;">
                                <button onclick="editCategory({{ $category->id }})"
                                    style="padding: 6px 12px; background: transparent; border: 1px solid #3F3F46; color: #D4D4D8; border-radius: 6px; cursor: pointer; font-size: 13px; margin-right: 8px;">
                                    {{ __('Edit') }} </button>
                                <form action="{{ route('admin.categories.destroy', $category) }}" method="POST"
                                    style="display: inline-block;">
                                    @csrf
                                    @method('DELETE')
                                    <button type="submit"
                                        style="padding: 6px 12px; background: transparent; border: 1px solid #F43F5E; color: #F43F5E; border-radius: 6px; cursor: pointer; font-size: 13px;">
                                        {{ __('Delete') }} </button>
                                </form>
                            </td>
                        </tr>
                    @endforeach
                </tbody>
            </table>

            <div style="margin-top: 20px; color: #FAFAFA;">
                {{ $categories->links() }}
            </div>
        @else
            <div style="padding: 40px; text-align: center; color: #71717A;">
                {{ __('No categories found.') }}
            </div>
        @endif
    </div>
</div>
