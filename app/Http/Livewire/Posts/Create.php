<?php

namespace App\Http\Livewire\Posts;

use App\Models\Media;
use App\Models\Post;
use Livewire\Component;
use Livewire\WithFileUploads;

class Create extends Component
{
    use WithFileUploads;

    public $title;

    public $body;

    public $file;

    public $imageFormats = ['jpg', 'png', 'gif', 'jpeg'];

    public $videoFormats = ['mp4', '3gp'];

    public function updatedFile()
    {
        $this->validate([
            'file' => 'mimes:' . implode(',', array_merge($this->imageFormats, $this->videoFormats)) . '|max:4096',
        ]);
    }

    public function submit()
    {
        $data = $this->validate([
            'title' => 'required|max:50',
            'body' => 'required|max:1000',
            'file' => 'nullable|mimes:' . implode(',', array_merge($this->imageFormats, $this->videoFormats)) . '|max:4096',
        ]);

        $post = Post::create([
            'user_id' => auth()->id(),
            'title' => $data['title'],
            'body' => $data['body'],
        ]);

        $this->storeFiles($post);

        session()->flash('success', 'Post created successfully');

        return redirect('home');
    }

    public function render(): \Illuminate\Contracts\View\Factory|\Illuminate\Contracts\View\View|\Illuminate\Contracts\Foundation\Application
    {
        return view('livewire.posts.create');
    }

    /**
     * @param $post
     * @return bool|void
     */
    private function storeFiles($post)
    {
        if (empty($this->file)) {
            return true;
        }

        $path = $this->file->store('post-photos', 'public');

        $isImage = preg_match('/^.*\.(png|jpg|gif)$/i', $path);

        Media::create([
            'post_id' => $post->id,
            'path' => $path,
            'is_image' => $isImage,
        ]);
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
