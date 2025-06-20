<?php

namespace App\Http\Controllers;

use App\Http\Controllers\Controller;
use Illuminate\Http\Request;
use Illuminate\Http\JsonResponse;
use Illuminate\Support\Facades\Auth;
use Illuminate\Support\Facades\Hash;
use Illuminate\Validation\ValidationException;

class ProfileApiController extends Controller
{
    /**
     * Get authenticated user profile
     */
    public function show(Request $request): JsonResponse
    {
        try {
            $user = $request->user();
            
            return response()->json([
                'success' => true,
                'message' => 'Profile retrieved successfully',
                'data' => [
                    'id' => $user->id,
                    'name' => $user->name,
                    'email' => $user->email,
                    'profile_picture' => $user->profile_picture,
                    'email_verified_at' => $user->email_verified_at,
                    'created_at' => $user->created_at,
                    'updated_at' => $user->updated_at,
                ]
            ], 200);
        } catch (\Exception $e) {
            return response()->json([
                'success' => false,
                'message' => 'Failed to retrieve profile',
                'error' => $e->getMessage()
            ], 500);
        }
    }

    /**
     * Update user profile
     */
    public function update(Request $request): JsonResponse
    {
        try {
            $user = $request->user();
            $editType = $request->input('edit_type', 'full');

            switch ($editType) {
                case 'name':
                    return $this->updateName($request, $user);
                
                case 'password':
                    return $this->updatePassword($request, $user);
                
                case 'image':
                    return $this->updateImage($request, $user);
                
                default:
                    return $this->updateFull($request, $user);
            }
        } catch (ValidationException $e) {
            return response()->json([
                'success' => false,
                'message' => 'Validation failed',
                'errors' => $e->errors()
            ], 422);
        } catch (\Exception $e) {
            return response()->json([
                'success' => false,
                'message' => 'Failed to update profile',
                'error' => $e->getMessage()
            ], 500);
        }
    }

    /**
     * Update user name only
     */
    private function updateName(Request $request, $user): JsonResponse
    {
        $request->validate([
            'name' => 'required|string|max:255',
        ]);
        
        $user->update([
            'name' => $request->name,
        ]);
        
        return response()->json([
            'success' => true,
            'message' => 'Name updated successfully',
            'data' => [
                'id' => $user->id,
                'name' => $user->name,
                'email' => $user->email,
                'profile_picture' => $user->profile_picture,
            ]
        ], 200);
    }

    /**
     * Update user password
     */
    private function updatePassword(Request $request, $user): JsonResponse
    {
        $request->validate([
            'current_password' => 'required',
            'password' => 'required|string|min:8|confirmed',
        ]);

        if (!Hash::check($request->current_password, $user->password)) {
            return response()->json([
                'success' => false,
                'message' => 'Current password is incorrect',
                'errors' => ['current_password' => ['Current password is incorrect']]
            ], 422);
        }

        $user->update([
            'password' => Hash::make($request->password),
        ]);

        return response()->json([
            'success' => true,
            'message' => 'Password updated successfully',
        ], 200);
    }

    /**
     * Update profile picture
     */
    private function updateImage(Request $request, $user): JsonResponse
    {
        $request->validate([
            'profile_picture' => 'required|image|mimes:jpeg,png,jpg,gif|max:2048',
        ]);

        // Convert image to base64
        $image = $request->file('profile_picture');
        $imageData = base64_encode(file_get_contents($image->getRealPath()));
        $mimeType = $image->getMimeType();
        $base64Image = 'data:' . $mimeType . ';base64,' . $imageData;

        $user->update([
            'profile_picture' => $base64Image,
        ]);

        return response()->json([
            'success' => true,
            'message' => 'Profile picture updated successfully',
            'data' => [
                'id' => $user->id,
                'name' => $user->name,
                'email' => $user->email,
                'profile_picture' => $user->profile_picture,
            ]
        ], 200);
    }

    /**
     * Update full profile
     */
    private function updateFull(Request $request, $user): JsonResponse
    {
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
            $image = $request->file('profile_picture');
            $imageData = base64_encode(file_get_contents($image->getRealPath()));
            $mimeType = $image->getMimeType();
            $data['profile_picture'] = 'data:' . $mimeType . ';base64,' . $imageData;
        }

        $user->update($data);

        return response()->json([
            'success' => true,
            'message' => 'Profile updated successfully',
            'data' => [
                'id' => $user->id,
                'name' => $user->name,
                'email' => $user->email,
                'profile_picture' => $user->profile_picture,
            ]
        ], 200);
    }

    /**
     * Delete user account
     */
    public function destroy(Request $request): JsonResponse
    {
        try {
            $request->validate([
                'password' => 'required',
            ]);

            $user = $request->user();

            // Verify password before deletion
            if (!Hash::check($request->password, $user->password)) {
                return response()->json([
                    'success' => false,
                    'message' => 'Password is incorrect',
                    'errors' => ['password' => ['Password is incorrect']]
                ], 422);
            }

            // Revoke all tokens (if using Sanctum)
            $user->tokens()->delete();
            
            // Delete user
            $user->delete();

            return response()->json([
                'success' => true,
                'message' => 'Account deleted successfully',
            ], 200);

        } catch (ValidationException $e) {
            return response()->json([
                'success' => false,
                'message' => 'Validation failed',
                'errors' => $e->errors()
            ], 422);
        } catch (\Exception $e) {
            return response()->json([
                'success' => false,
                'message' => 'Failed to delete account',
                'error' => $e->getMessage()
            ], 500);
        }
    }
}