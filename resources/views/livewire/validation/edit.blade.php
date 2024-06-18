<div class="flex flex-col sm:justify-center items-center pt-6 sm:pt-0 bg-gray-100">
    <div class="w-11/12 lg:w-full md:w-full sm:max-w-md mt-6 px-6 py-4 bg-white shadow-md overflow-hidden rounded-lg mb-12">
         <x-jet-validation-errors class="mb-4" />
         <script src="https://cdn.ckeditor.com/ckeditor5/41.4.2/classic/ckeditor.js"></script>

        <form method="POST" wire:submit.prevent="submit" enctype="multipart/form-data">
            @csrf

            <div>
                <x-jet-label for="title" value="{{ __('Title') }}" />
                <x-jet-input id="title" class="block mt-1 mb-2 w-full" type="text" wire:model.lazy="title" />
            </div>
            
            <div class="mt-4">
                <x-jet-label for="body" value="{{ __('Description') }}" />
                <div wire:ignore>
                    <textarea id="editor" rows="5" class="block mt-1 w-full border-gray-300 focus:border-indigo-300 focus:ring focus:ring-indigo-200 focus:ring-opacity-50 rounded-md shadow">{{ $body }}</textarea>
                </div>
                <script>
                    document.addEventListener('livewire:load', function () {
                        ClassicEditor
                            .create(document.querySelector('#editor'))
                            .then(editor => {
                                editor.model.document.on('change:data', () => {
                                    Livewire.emit('editorUpdated', editor.getData());
                                });
                            })
                            .catch(error => {
                                console.error(error);
                            });

                        Livewire.on('editorUpdated', data => {
                            @this.set('body', data);
                        });
                    });
                </script>
            </div>
            
            @if($existingFilePath)
            <div class="mt-4">
                <x-jet-label value="{{ __('Current Media:') }}" />
                @if(in_array(pathinfo($existingFilePath, PATHINFO_EXTENSION), $this->imageFormats))
                    <img class="p-3 h-32" src="{{ Storage::disk('public')->url($existingFilePath) }}" oncontextmenu="return false;">
                @elseif(in_array(pathinfo($existingFilePath, PATHINFO_EXTENSION), $this->videoFormats))
                    <video controls crossorigin playsinline oncontextmenu="return false;" controlsList="nodownload" class="rounded-lg filter">
                        <source src="{{ Storage::disk('public')->url($existingFilePath) }}" type="video/{{ pathinfo($existingFilePath, PATHINFO_EXTENSION) }}" size="576">
                        <a href="{{ Storage::disk('public')->url($existingFilePath) }}" download>Download</a>
                    </video>
                @endif
            </div>
            @endif
            
            @if($file)
            <div class="mt-4">
                <x-jet-label value="{{ __('Preview :') }}" />
                @if(in_array($file->extension(), $this->imageFormats))
                    <img class="p-3 h-32" src="{{ $file->temporaryUrl() }}" oncontextmenu="return false;">
                @elseif(in_array($file->extension(), $this->videoFormats))
                    <video controls crossorigin playsinline oncontextmenu="return false;" controlsList="nodownload" class="rounded-lg filter">
                        <source src="{{ $file->temporaryUrl() }}" type="video/{{ $file->extension() }}" size="576">
                        <a href="{{ $file->temporaryUrl() }}" download>Download</a>
                    </video>
                @else
                    <p class="text-red-700 text-sm my-3">Invalid File selected. You can only upload {{ implode(', ',  array_merge($this->imageFormats, $this->videoFormats)) }} file types. </p>
                @endif
            </div>
            @endif

            <div x-data="{ isUploading: false, progress: 0 }"
                 x-on:livewire-upload-start="isUploading = true"
                 x-on:livewire-upload-finish="isUploading = false"
                 x-on:livewire-upload-error="isUploading = false"
                 x-on:livewire-upload-progress="progress = $event.detail.progress">

                <div class="mt-4">
                    <x-jet-label for="file" value="{{ __('Media') }}" />
                    <input type="file" wire:model="file">
                </div>

                <div wire:loading class="my-3" wire:target="file">Uploading...</div>

                <!-- Progress Bar -->
                <div x-show="isUploading" class="my-2">
                    <progress max="100" x-bind:value="progress"></progress>
                </div>
            </div>

            <div class="flex items-center justify-end mt-4">
                <x-jet-button class="ml-4">
                    {{ $postId ? __('Update Post') : __('Create Post') }}
                </x-jet-button>
            </div>
        </form>
    </div>
</div>
