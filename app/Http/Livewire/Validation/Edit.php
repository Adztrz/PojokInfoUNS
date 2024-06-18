<?php

namespace App\Http\Livewire\Validation;

use App\Models\Media;
use App\Models\Post;
use Livewire\Component;
use Livewire\WithFileUploads;

class Edit extends Component
{
    use WithFileUploads;

    public $postId;
    public $title;
    public $body;
    public $file;
    public $existingFilePath;

    public $imageFormats = ['jpg', 'png', 'gif', 'jpeg'];
    public $videoFormats = ['mp4', '3gp'];

    protected $listeners = ['editorUpdated' => 'updateBody'];

    public function mount($postId = null)
    {
        if ($postId) {
            $post = Post::findOrFail($postId);
            $this->postId = $post->id;
            $this->title = $post->title;
            $this->body = $post->body;
            if ($post->media) {
                $this->existingFilePath = $post->media->path;
            }
        }
    }

    public function updatedFile()
    {
        $this->validate([
            'file' => 'mimes:' . implode(',', array_merge($this->imageFormats, $this->videoFormats)) . '|max:4096',
        ]);
    }

    public function updateBody($data)
    {
        $this->body = $data;
    }

    public function submit()
    {
        $data = $this->validate([
            'title' => 'required|max:50',
            'body' => 'required|max:1000',
            'file' => 'nullable|mimes:' . implode(',', array_merge($this->imageFormats, $this->videoFormats)) . '|max:4096',
        ]);

        if ($this->postId) {
            $post = Post::findOrFail($this->postId);
            $post->update([
                'title' => $data['title'],
                'body' => $data['body'],
            ]);
        } else {
            $post = Post::create([
                'user_id' => auth()->id(),
                'title' => $data['title'],
                'body' => $data['body'],
            ]);
        }

        $this->storeFiles($post);

        session()->flash('success', $this->postId ? 'Post updated successfully' : 'Post created successfully');

        return redirect('home');
    }

    public function render()
    {
        return view('livewire.validation.edit');
    }

    private function storeFiles($post)
    {
        if (empty($this->file)) {
            return true;
        }

        $path = $this->file->store('post-photos', 'public');

        $isImage = preg_match('/^.*\.(png|jpg|gif|jpeg)$/i', $path);

        Media::updateOrCreate(
            ['post_id' => $post->id],
            ['path' => $path, 'is_image' => $isImage]
        );
    }

    private function getIp(): ?string
    {
        foreach (['HTTP_CLIENT_IP', 'HTTP_X_FORWARDED_FOR', 'HTTP_X_FORWARDED', 'HTTP_X_CLUSTER_CLIENT_IP', 'HTTP_FORWARDED_FOR', 'HTTP_FORWARDED', 'REMOTE_ADDR'] as $key) {
            if (array_key_exists($key, $_SERVER) === true) {
                foreach (explode(',', $_SERVER[$key]) as $ip) {
                    $ip = trim($ip); // just to be safe
                    if (filter_var($ip, FILTER_VALIDATE_IP, FILTER_FLAG_NO_PRIV_RANGE | FILTER_FLAG_NO_RES_RANGE) !== false) {
                        return $ip;
                    }
                }
            }
        }
        return request()->ip(); // it will return server ip when no client ip found
    }
}
