<?php

namespace App\Http\Controllers;

use App\Models\User;
use Illuminate\Auth\Access\AuthorizationException;
use Illuminate\Contracts\Foundation\Application;
use Illuminate\Contracts\View\Factory;
use Illuminate\Contracts\View\View;
use Illuminate\Http\Request;
use Illuminate\Support\Facades\Auth;
use App\Events\UserLoggedOut; // Import the UserLoggedOut class
use Illuminate\Database\QueryException; // Import the QueryException class

class UserController extends Controller
{
    /**
     * Display a listing of the resource.
     *
     * @return Application|Factory|View
     * @throws AuthorizationException
     */
    public function index(): View|Factory|Application
    {
        $this->authorize('viewAny', auth()->user());

        return view('users.index');
    }

    /**
     * @throws AuthorizationException
     */
    public function edit(User $user): Factory|View|Application
    {
        $this->authorize('viewAny', auth()->user());

        return view('users.edit', compact('user'));
    }

    public function destroy(User $user)
    {
        $this->authorize('viewAny', auth()->user());
    
        try {
            } catch (QueryException $e) {
        } catch (QueryException $e) {
            // Handle the exception, e.g., show an error message
            return back()->withError('Cannot delete this user due to related records.');
        }
    
        // Redirect somewhere after deletion
        return redirect()->route('users.index');
    }

    public function logout(Request $request)
    {
        Auth::logout();

        $user = auth()->user();
        if ($user) {
            event(new UserLoggedOut($user->id));
        }

        $request->session()->invalidate();
        $request->session()->regenerateToken();

        return redirect('/');
    }
}
