<?php

namespace App\Http\Livewire\Profile;

use App\Models\Follower;
use App\Models\User;
use Auth;
use Illuminate\Support\Facades\Gate;
use Livewire\Component;

class ProfilePage extends Component
{
    public $user;

    public $posts;

    public $postsCount;

    public function mount()
    {
        $this->postsCount = $this->user->posts_count;
    }

    public function render()
    {
        return view('livewire.profile.profile-page');
    }

}
