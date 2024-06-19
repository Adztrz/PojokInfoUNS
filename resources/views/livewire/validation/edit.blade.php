<div>
<div class="flex flex-col sm:justify-center items-center pt-6 sm:pt-0 bg-gray-100">
    <div class="w-11/12 lg:w-full md:w-full sm:max-w-md mt-6 px-6 py-4 bg-white shadow-md overflow-hidden rounded-lg mb-12">
        <x-jet-validation-errors class="mb-4" />

        <script src="https://cdn.ckeditor.com/ckeditor5/41.4.2/classic/ckeditor.js"></script>

        <form method="POST" action="{{ route('posts.update', $post->id) }}" enctype="multipart/form-data">
            @csrf
            @method('PUT')

            <div>
                <x-jet-label for="title" value="{{ __('Title') }}" />
                <x-jet-input id="title" class="block mt-1 mb-2 w-full" type="text" name="title" value="{{ $post->title }}" required autofocus />
            </div>

            <div class="mt-4">
                <x-jet-label for="body" value="{{ __('Description') }}" />
                <div wire:ignore>
                    <textarea id="editor" rows="5" class="block mt-1 w-full border-gray-300 focus:border-indigo-300 focus:ring focus:ring-indigo-200 focus:ring-opacity-50 rounded-md shadow">{{ $post->body }}</textarea>
                </div>
            </div>

            <div class="form-group">
                <button type="submit" class="btn btn-primary">Update Post</button>
            </div>
        </form>
    </div>
</div>

</div>
