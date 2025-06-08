<?php

namespace App\Http\Controllers\Api;

use App\Http\Controllers\Controller;
use Illuminate\Http\Request;
use Illuminate\Support\Facades\Hash;
use Illuminate\Support\Facades\Auth;

class ProfileApiController extends Controller
{
    public function index(Request $request)
    {
        return response()->json($request->user());
    }

    public function update(Request $request)
    {
        $user = $request->user();
        $editType = $request->input('edit_type', 'full');

        switch ($editType) {
            case 'name':
                $request->validate([
                    'name' => 'required|string|max:255',
                ]);
                $user->update(['name' => $request->name]);
                return response()->json(['message' => 'Name updated']);

            case 'password':
                $request->validate([
                    'current_password' => 'required',
                    'password' => 'required|string|min:8|confirmed',
                ]);
                if (!Hash::check($request->current_password, $user->password)) {
                    return response()->json(['error' => 'Current password is incorrect'], 422);
                }
                $user->update(['password' => Hash::make($request->password)]);
                return response()->json(['message' => 'Password updated']);

            case 'image':
                $request->validate([
                    'profile_picture' => 'required|image|mimes:jpeg,png,jpg,gif|max:2048',
                ]);
                $image = $request->file('profile_picture');
                $imageData = base64_encode(file_get_contents($image->getRealPath()));
                $mimeType = $image->getMimeType();
                $base64Image = 'data:' . $mimeType . ';base64,' . $imageData;
                $user->update(['profile_picture' => $base64Image]);
                return response()->json(['message' => 'Image updated']);

            default:
                $request->validate([
                    'name' => 'required|string|max:255',
                    'email' => 'required|email|max:255|unique:users,email,' . $user->id,
                    'profile_picture' => 'nullable|image|mimes:jpeg,png,jpg,gif|max:2048',
                ]);

                $data = $request->only('name', 'email');

                if ($request->hasFile('profile_picture')) {
                    $image = $request->file('profile_picture');
                    $imageData = base64_encode(file_get_contents($image->getRealPath()));
                    $mimeType = $image->getMimeType();
                    $data['profile_picture'] = 'data:' . $mimeType . ';base64,' . $imageData;
                }

                $user->update($data);
                return response()->json(['message' => 'Profile updated']);
        }
    }

    public function destroy(Request $request)
    {
        $request->validate([
            'password' => ['required'],
        ]);

        $user = $request->user();

        if (!Hash::check($request->password, $user->password)) {
            return response()->json(['error' => 'Password is incorrect'], 422);
        }

        Auth::logout();
        $user->delete();

        return response()->json(['message' => 'User account deleted']);
    }
}
