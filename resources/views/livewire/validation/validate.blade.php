<div>
<script src="https://cdn.ckeditor.com/ckeditor5/41.4.2/classic/ckeditor.js"></script>
<style>
  .wrap-content {
    white-space: pre-wrap; /* CSS3 */
    white-space: -moz-pre-wrap; /* Firefox */
    white-space: -pre-wrap; /* Opera <7 */
    white-space: -o-pre-wrap; /* Opera 7 */
    word-wrap: break-word; /* IE */
    max-width: 300px; /* Adjust the width as needed */
    overflow-wrap: break-word;
}
table {
    table-layout: fixed;
    width: 100%;
}
</style>
  <div class="-my-2 overflow-x-auto sm:-mx-6 lg:-mx-8">
    <div class="py-2 align-middle inline-block min-w-full sm:px-6 lg:px-8">
      <div class="shadow overflow-hidden border-b border-gray-200 sm:rounded-lg rounded-xl shadow-sm">
        <table class="min-w-full divide-y divide-gray-200">
          <thead class="bg-gray-50">
            <tr>
              <th scope="col" class="px-6 py-3 text-left text-xs font-medium text-gray-500 uppercase tracking-wider">
                Id
              </th>
              <th scope="col" class="px-6 py-3 text-left text-xs font-medium text-gray-500 uppercase tracking-wider">
                Name
              </th>
              <th scope="col" class="px-6 py-3 text-left text-xs font-medium text-gray-500 uppercase tracking-wider">
                Media
              </th>
              <th scope="col" class="px-6 py-3 text-left text-xs font-medium text-gray-500 uppercase tracking-wider" style="width: 300px;">
                Title
              </th>
              <th scope="col" class="px-6 py-3 text-left text-xs font-medium text-gray-500 uppercase tracking-wider" style="width: 500px;">
                Body
              </th>
              <th scope="col" class="px-6 py-3 text-left text-xs font-medium text-gray-500 uppercase tracking-wider">
                Validate
              </th>
              <th scope="col" class="px-6 py-3 text-left text-xs font-medium text-gray-500 uppercase tracking-wider">
                Actions
              </th>
              <th scope="col" class="relative px-6 py-3">
                <span class="sr-only">Edit</span>
              </th>
            </tr>
          </thead>
          <tbody class="bg-white divide-y divide-gray-200">
            @foreach($posts as $post)
            <tr>
              <td class="px-6 py-4 whitespace-nowrap">
                <div class="text-sm font-medium text-gray-900">
                  {{ $post->id }}
                </div>
              </td>
              <td class="px-6 py-4 whitespace-nowrap">
                <div class="text-sm text-gray-500">
                {{ $post->user->name }}
                    </div>
              </td>
              <td>
              <div class="h-full w-full  mb-3 filter" wire:offline.class="grayscale">
                            @foreach($post->postImages as $media)
                            @if($media->is_image && preg_match('/^.*\.(png|jpg|gif)$/i', $media->path))
                            <img src="{{ url('/storage/' . $media->path) }}"
                                alt="Social" class="w-full object-scale-down md:object-cover lg:object-cover rounded-2xl" onContextMenu="return false;">
                            @elseif(!$media->is_image && preg_match('/^.*\.(mp4|3gp)$/i', $media->path))
                            <div class="container">
                            <video controls crossorigin playsinline oncontextmenu="return false;" controlsList="nodownload" class="rounded-lg filter" id="player_{{ $post->id }}">
                                <!-- Video files -->
                                <source src="{{ url('/storage/' . $media->path) }}" type="video/mp4" size="576">

                                <!-- Fallback for browsers that don't support the <video> element -->
                                <a href="{{ url('/storage/' . $media->path) }}" download>Download</a>
                            </video>
                            </div>
                            @endif
                            @endforeach
                        </div>
          </td>
              <td class="px-6 py-4 whitespace-nowrap wrap-content">
                <div class="text-sm text-gray-500">
                  {{ $post->title }}
                </div>
              </td>
              <td class="px-6 py-4 whitespace-nowrap wrap-content">
                <div class="text-sm text-gray-500">
                  {!! $post->body !!}
                </div>
              </td>
              <td class="px-6 py-4 whitespace-nowrap text-right text-sm font-medium">
                <form action="{{ route('posts.updateStatus', $post) }}" method="POST" class="inline">
                  @csrf
                  <input type="hidden" name="isValid" value="{{ $post->isValid ? 0 : 1 }}">
                  <button type="submit" class="text-indigo-600 hover:text-indigo-900">
                    {{ $post->isValid ? 'Reject' : 'Accept' }}
                  </button>
                </form>
              </td>
              <td class="px-6 py-4 whitespace-nowrap text-right text-sm font-medium">
              <td class="px-6 py-4 whitespace-nowrap text-right text-sm font-medium">
                  <a href="{{ route('posts.edit', ['post' => $post->id ]) }}" class="text-indigo-600 hover:text-indigo-900">Edit</a>
              </td>
              </td>
              <td class="px-6 py-4 whitespace-nowrap text-right text-sm font-medium">
                <form action="{{ route('posts.destroy', $post) }}" method="POST" class="inline">
                    @csrf
                    @method('DELETE')
                    <button type="submit" class="text-red-600 hover:text-red-900">Delete</button>
                </form>
              </td>
            </tr>
            @endforeach
          </tbody>
        </table>
      </div>
    </div>
  </div>
</div>
