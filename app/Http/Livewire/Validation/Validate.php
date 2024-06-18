<?php

namespace App\Http\Livewire\Validation;

use Livewire\WithPagination;
use App\Models\Post;
use Livewire\Component;

class Validate extends Component
{

    public $posts;

    public function mount()
    {
        $this->posts = Post::all(); // You can adjust this query as needed
    }

    public function render()
    {
        return view('livewire.validation.validate');
    }

    
    // public function render()
    // {
    //     $posts = Post::where('title', 'like', '%' . $this->searchTerm . '%')
    //                  ->orderBy('created_at', 'desc')
    //                  ->paginate($this->perPage);

    //     return view('livewire.validation.validate', ['posts' => $posts]);
    // }
}
