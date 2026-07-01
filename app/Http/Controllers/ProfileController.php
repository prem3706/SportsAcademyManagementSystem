<?php

namespace App\Http\Controllers;

use App\Http\Requests\ProfileRequest;
use Illuminate\Support\Facades\Auth;
use Illuminate\Support\Facades\Storage;
use Exception;
use Illuminate\Support\Facades\Log;

class ProfileController extends Controller
{
    /**
     * Show the form for editing the user profile.
     */
    public function edit()
    {
        try {
            $user = Auth::user();
            return view('profile.edit', compact('user'));
        } catch (Exception $e) {
            Log::error('Profile Edit Error: ' . $e->getMessage());
            return abort(500);
        }
    }

    /**
     * Update the user profile in storage.
     */
    public function update(ProfileRequest $request)
    {
        try {
            $user = Auth::user();
            $validatedData = $request->validated();

            if (empty($validatedData['password'])) {
                unset($validatedData['password']);
            }
            unset($validatedData['password_confirmation']);

            // Handle Profile Picture removal or upload
            if ($request->input('remove_profile_picture') == '1') {
                // Delete old profile picture if exists
                if ($user->profile_picture && Storage::disk('public')->exists($user->profile_picture)) {
                    Storage::disk('public')->delete($user->profile_picture);
                }
                $validatedData['profile_picture'] = null;
            } elseif ($request->hasFile('profile_picture')) {
                // Delete old profile picture if exists
                if ($user->profile_picture && Storage::disk('public')->exists($user->profile_picture)) {
                    Storage::disk('public')->delete($user->profile_picture);
                }

                // Store new profile picture
                $path = $request->file('profile_picture')->store('profile_pictures', 'public');
                $validatedData['profile_picture'] = $path;
            }

            $user->update($validatedData);

            return response()->json([
                'success' => true,
                'message' => 'Profile updated successfully.',
            ]);
        } catch (Exception $e) {
            Log::error('Profile Update Error: ' . $e->getMessage());
            return response()->json([
                'success' => false,
                'message' => 'Something went wrong while updating your profile.',
            ], 500);
        }
    }
}
