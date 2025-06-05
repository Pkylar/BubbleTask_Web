<?php

namespace App\Http\Controllers;

use App\Http\Requests\ProfileUpdateRequest;
use Illuminate\Http\RedirectResponse;
use Illuminate\Http\Request;
use Illuminate\Support\Facades\Auth;
use Illuminate\Support\Facades\Redirect;
use Illuminate\View\View;
use Illuminate\Support\Facades\Storage;
use Illuminate\Support\Facades\Hash;

class ProfileController extends Controller
{
    public function index(Request $request): View
    {
        return view('profile.index', [
            'user' => $request->user(),
        ]);
    }

    public function edit(Request $request): View
    {
        return view('profile.edit', [
            'user' => $request->user(),
        ]);
    }


    public function update(Request $request): RedirectResponse
    {
        $user = $request->user();
        $editType = $request->input('edit_type', 'full');

        switch ($editType) {
            case 'name':
                $request->validate([
                    'name' => 'required|string|max:255',
                ]);
                
                $user->update([
                    'name' => $request->name,
                ]);
                
                return redirect()->route('profile')->with('status', 'profile-updated');

            case 'password':
                $request->validate([
                    'current_password' => 'required',
                    'password' => 'required|string|min:8|confirmed',
                ]);

                // Verify current password
                if (!Hash::check($request->current_password, $user->password)) {
                    return back()->withErrors(['current_password' => 'Current password is incorrect.']);
                }

                $user->update([
                    'password' => Hash::make($request->password),
                ]);

                return redirect()->route('profile')->with('status', 'password-updated');

            case 'image':
                $request->validate([
                    'profile_picture' => 'required|image|mimes:jpeg,png,jpg,gif|max:2048',
                ]);

                // Delete old image if exists
                if ($user->profile_picture) {
                    Storage::disk('public')->delete($user->profile_picture);
                }

                // Upload new image
                $path = $request->file('profile_picture')->store('profile_pictures', 'public');
                
                $user->update([
                    'profile_picture' => $path,
                ]);

                return redirect()->route('profile')->with('status', 'image-updated');

            default:
                // Full update (original functionality)
                $request->validate([
                    'name' => 'required|string|max:255',
                    'email' => 'required|string|email|max:255|unique:users,email,' . $user->id,
                    'profile_picture' => 'nullable|image|mimes:jpeg,png,jpg,gif|max:2048',
                ]);

                $data = [
                    'name' => $request->name,
                    'email' => $request->email,
                ];

                if ($request->hasFile('profile_picture')) {
                    // Delete old image if exists
                    if ($user->profile_picture) {
                        Storage::disk('public')->delete($user->profile_picture);
                    }
                    
                    $path = $request->file('profile_picture')->store('profile_pictures', 'public');
                    $data['profile_picture'] = $path;
                }

                $user->update($data);

                return redirect()->route('profile')->with('status', 'profile-updated');
        }
    }

    public function destroy(Request $request): RedirectResponse
    {
        $request->validateWithBag('userDeletion', [
            'password' => ['required', 'current_password'],
        ]);

        $user = $request->user();

        Auth::logout();

        $user->delete();

        $request->session()->invalidate();
        $request->session()->regenerateToken();

        return Redirect::to('/');
    }
}