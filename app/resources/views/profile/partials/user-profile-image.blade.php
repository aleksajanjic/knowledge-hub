<div class="mb-6">
    <form method="POST" action="{{ route('profile.update') }}" enctype="multipart/form-data">
        @csrf
        @method('PATCH')

        <label for="profile_image" class="block text-sm font-medium text-zinc-400 mb-2">
            Profile Image
        </label>

        <div class="flex items-center space-x-4">
            @if (auth()->user()->profile_image)
                <img id="profile-image-preview" src="{{ asset('storage/' . auth()->user()->profile_image) }}"
                    alt="Profile Image" class="w-24 h-24 rounded-full object-cover border-2 border-zinc-700">
                <div id="profile-image-placeholder" class="hidden"></div>
            @else
                <div id="profile-image-placeholder"
                    class="w-24 h-24 rounded-full bg-zinc-800 flex items-center justify-center text-zinc-500 border-2 border-zinc-700">
                    No Image
                </div>
                <img id="profile-image-preview"
                    class="w-24 h-24 rounded-full object-cover border-2 border-zinc-700 hidden">
            @endif

            <label
                class="cursor-pointer inline-flex items-center px-4 py-2 bg-zinc-700 text-white text-sm font-medium rounded-full shadow-sm hover:bg-zinc-600 transition-colors">
                Select Image
                <input type="file" name="profile_image" id="profile_image" accept="image/*" class="sr-only" />
            </label>
        </div>

        <p class="mt-2 text-xs text-zinc-500">
            Max size: 2MB. Supported formats: jpg, png, gif.
        </p>

        <div class="mt-4">
            <button type="submit"
                class="px-4 py-2 bg-green-600 text-white font-medium rounded-lg shadow hover:bg-green-500 transition-colors">
                Save
            </button>
        </div>
    </form>
</div>

<script>
    document.addEventListener('DOMContentLoaded', function() {
        const input = document.getElementById('profile_image');
        const preview = document.getElementById('profile-image-preview');
        const placeholder = document.getElementById('profile-image-placeholder');

        if (!input || !preview || !placeholder) return;

        input.addEventListener('change', function() {
            const file = this.files[0];
            if (!file) return;

            const reader = new FileReader();
            reader.onload = function(e) {
                preview.src = e.target.result;
                preview.classList.remove('hidden');
                placeholder.classList.add('hidden');
            };
            reader.readAsDataURL(file);
        });
    });
</script>
