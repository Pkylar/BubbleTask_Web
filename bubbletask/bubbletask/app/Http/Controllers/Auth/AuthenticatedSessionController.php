<?php

namespace App\Http\Controllers\Auth;

use App\Http\Controllers\Controller;
use Illuminate\Http\RedirectResponse;
use Illuminate\Http\Request;
use Illuminate\Support\Facades\Auth;
use Illuminate\View\View;

class AuthenticatedSessionController extends Controller
{
    /**
     * Display the login view.
     */
    public function create(): View
    {
        return view('auth.login');
    }

    /**
     * Handle an incoming authentication request.
     */
    public function store(Request $request): RedirectResponse
    {
        $login = $request->input('email'); // input name="email" bisa email atau username

        // Tentukan field yang akan digunakan untuk login
        $field = filter_var($login, FILTER_VALIDATE_EMAIL) ? 'email' : 'name';

        // Merge request supaya bisa dipakai validasi dan auth
        $request->merge([
            $field => $login,
        ]);

        // Validasi input
        $request->validate([
            'email' => 'required|string',
            'password' => 'required|string',
        ]);

        // Coba login dengan field yang dipilih (email atau name)
        if (!Auth::attempt($request->only($field, 'password'), $request->boolean('remember'))) {
            return back()->withErrors([
                'email' => __('auth.failed'),
            ])->onlyInput('email');
        }

        $request->session()->regenerate();

        // Redirect ke 'home' setelah login berhasil
        return redirect()->intended(route('home'));
    }

    /**
     * Destroy an authenticated session.
     */
    public function destroy(Request $request): RedirectResponse
    {
        Auth::guard('web')->logout();

        $request->session()->invalidate();

        $request->session()->regenerateToken();

        return redirect('/');
    }
}
