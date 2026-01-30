<div>
    <div style="display: flex; justify-content: space-between; align-items: center; margin-bottom: 20px;">
        <h3 style="color: #FAFAFA; font-size: 20px; font-weight: 600;">{{ __('Category Management') }}</h3>
        <button onclick="openCreateCategoryModal()"
            style="padding: 10px 20px; background: #10B981; color: #18181B; border: none; border-radius: 8px; cursor: pointer; font-weight: 600; font-size: 14px;">
            + {{ __('Add Category') }}
        </button>
    </div>

    <div style="overflow-x: auto;">
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
                        style="padding: 12px; text-align: left; color: #A1A1AA; font-size: 12px; font-weight: 600; text-transform: uppercase;">
                        {{ __('Description') }}</th>
                    <th
                        style="padding: 12px; text-align: left; color: #A1A1AA; font-size: 12px; font-weight: 600; text-transform: uppercase;">
                        {{ __('Questions') }}</th>
                    <th
                        style="padding: 12px; text-align: right; color: #A1A1AA; font-size: 12px; font-weight: 600; text-transform: uppercase;">
                        {{ __('Actions') }}</th>
                </tr>
            </thead>
            <tbody>
                <tr>
                    <td colspan="5" style="padding: 40px; text-align: center; color: #71717A;">
                        {{ __('Categories coming soon...') }}
                    </td>
                </tr>
            </tbody>
        </table>
    </div>
</div>
