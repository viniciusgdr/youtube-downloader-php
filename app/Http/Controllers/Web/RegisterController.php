<?php

namespace App\Http\Controllers\Web;
use App\Models\User;
use Illuminate\Contracts\View\View;
use App\Http\Controllers\Controller;
use Illuminate\Http\RedirectResponse;
use Illuminate\Http\Request;

class RegisterController extends Controller
{
    /**
     * Display a listing of the resource.
     */
    public function index(): View | RedirectResponse
    {
        if (auth()->check()) {
            return redirect()->route('home');
        }
        return view('auth.register');
    }

    /**
     * Create the user with form
     */
    public function form(Request $request): RedirectResponse
    {
        if (auth()->check()) {
            return redirect()->route('home');
        }
        $credentials = $request->validate([
            'email' => ['required', 'email', 'max:255', 'unique:users'],
            'password' => ['required','string','min:8'],
        ]);

        $user = User::create($credentials);

        if ($user) {
            return redirect()->route('login');
        }

        return back()->withErrors([
            'email' => 'The provided credentials do not match our records.',
        ]);
    }
}
