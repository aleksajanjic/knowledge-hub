<!-- Create Tag Modal -->
<div id="create-tag-modal"
    class="fixed inset-0 bg-black/70 backdrop-blur-sm flex items-center justify-center z-50 p-4 hidden"
    onclick="if(event.target === this) { closeTagModal('create'); }">
    <div style="background: #18181B; width: 500px; padding: 30px; border-radius: 15px; position: relative;">
        <button type="button" onclick="closeTagModal('create')"
            style="position: absolute; top: 20px; right: 20px; background: none; border: none; color: #71717A; font-size: 24px; cursor: pointer; padding: 5px 10px;">
            ✕
        </button>

        <h2 style="color: white; font-size: 24px; margin-bottom: 20px;">{{ __('Create Tag') }}</h2>

        <form action="{{ route('admin.tags.store') }}" method="POST">
            @csrf

            <div style="margin-bottom: 20px;">
                <label style="color: #A1A1AA; display: block; margin-bottom: 5px; font-size: 14px;">
                    {{ __('Tag Name') }}
                </label>
                <input type="text" name="name" required placeholder="e.g. javascript"
                    style="width: 100%; padding: 12px; border-radius: 10px; border: 1px solid #3F3F46; background: #27272A; color: white;">
            </div>

            <div style="display: flex; gap: 10px; justify-content: flex-end;">
                <button type="button" onclick="closeTagModal('create')"
                    style="padding: 10px 20px; background: transparent; color: #A1A1AA; border: 1px solid #3F3F46; border-radius: 8px; cursor: pointer; font-size: 14px;">
                    {{ __('Cancel') }}
                </button>
                <button type="submit"
                    style="padding: 10px 24px; background: #10B981; color: #18181B; border: none; border-radius: 8px; cursor: pointer; font-weight: 600; font-size: 14px;">
                    {{ __('Create') }}
                </button>
            </div>
        </form>
    </div>
</div>

<!-- Edit Tag Modal -->
<div id="edit-tag-modal"
    class="fixed inset-0 bg-black/70 backdrop-blur-sm flex items-center justify-center z-50 p-4 hidden"
    onclick="if(event.target === this) { closeTagModal('edit'); }">
    <div style="background: #18181B; width: 500px; padding: 30px; border-radius: 15px; position: relative;">
        <button type="button" onclick="closeTagModal('edit')"
            style="position: absolute; top: 20px; right: 20px; background: none; border: none; color: #71717A; font-size: 24px; cursor: pointer; padding: 5px 10px;">
            ✕
        </button>

        <h2 style="color: white; font-size: 24px; margin-bottom: 20px;">{{ __('Edit Tag') }}</h2>

        <form id="edit-tag-form" method="POST">
            @csrf
            @method('PUT')

            <div style="margin-bottom: 20px;">
                <label style="color: #A1A1AA; display: block; margin-bottom: 5px; font-size: 14px;">
                    {{ __('Tag Name') }}
                </label>
                <input type="text" id="edit-tag-name" name="name" required
                    style="width: 100%; padding: 12px; border-radius: 10px; border: 1px solid #3F3F46; background: #27272A; color: white;">
            </div>

            <div style="display: flex; gap: 10px; justify-content: flex-end;">
                <button type="button" onclick="closeTagModal('edit')"
                    style="padding: 10px 20px; background: transparent; color: #A1A1AA; border: 1px solid #3F3F46; border-radius: 8px; cursor: pointer; font-size: 14px;">
                    {{ __('Cancel') }}
                </button>
                <button type="submit"
                    style="padding: 10px 24px; background: #10B981; color: #18181B; border: none; border-radius: 8px; cursor: pointer; font-weight: 600; font-size: 14px;">
                    {{ __('Update') }}
                </button>
            </div>
        </form>
    </div>
</div>

<script>
    document.addEventListener('keydown', function(event) {
        if (event.key === 'Escape') {
            closeTagModal('create');
            closeTagModal('edit');
        }
    });
</script>
