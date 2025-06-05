<?php

namespace App\Http\Controllers\Auth;

use App\Http\Controllers\Controller;
use App\Models\User;
use Illuminate\Auth\Events\Registered;
use Illuminate\Http\RedirectResponse;
use Illuminate\Http\Request;
use Illuminate\Support\Facades\Auth;
use Illuminate\Support\Facades\Hash;
use Illuminate\Validation\Rules;
use Illuminate\View\View;

class RegisteredUserController extends Controller
{
    /**
     * Display the registration view.
     */
    public function create(): View
    {
        return view('auth.register');
    }

    /**
     * Handle an incoming registration request.
     *
     * @throws \Illuminate\Validation\ValidationException
     */
    public function store(Request $request): RedirectResponse
    {
        $request->validate([
            'name' => ['required', 'string', 'max:255'],
            'email' => ['required', 'string', 'lowercase', 'email', 'max:255', 'unique:'.User::class],
            'password' => ['required', 'confirmed', Rules\Password::defaults()],
        ]);

        $user = User::create([
            'name' => $request->name,
            'email' => $request->email,
            'password' => Hash::make($request->password),
            'profile_picture' => $this->getDefaultProfilePicture($request->name),
        ]);

        event(new Registered($user));

        Auth::login($user);

        return redirect(route('dashboard', absolute: false));
    }

    private function getDefaultProfilePicture($name){
        return $this->generateInitialsAvatar($name);
    }

    private function generateInitialsAvatar($name){
        $initials = $this->getInitials($name);
        $background = $this->generateColorFromName($name);
        
        // Menggunakan UI Avatars service
        return "https://ui-avatars.com/api/?name={$initials}&background={$background}&color=fff&size=200&bold=true";
    }

    private function getInitials($name){
        $words = explode(' ', trim($name));
        $initials = '';
        
        foreach ($words as $word) {
            if (!empty($word)) {
                $initials .= strtoupper(substr($word, 0, 1));
            }
        }
        
        return substr($initials, 0, 2); // Max 2 huruf
    }

    private function generateColorFromName($name){
        $colors = [
            '3D3F91',
            //3498db', // Blue
            /* 'e74c3c', // Red
            '2ecc71', // Green
            'f39c12', // Orange
            '9b59b6', // Purple
            '1abc9c', // Turquoise
            'e67e22', // Carrot
            '34495e', // Wet Asphalt
            '16a085', // Green Sea
            'c0392b', // Pomegranate */
        ];
        
        $index = abs(crc32($name)) % count($colors);
        return $colors[$index];
    }
}
