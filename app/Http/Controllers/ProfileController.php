<?php

namespace App\Http\Controllers;

use Illuminate\Http\Request;
use Illuminate\Support\Facades\Hash;
use Illuminate\Support\Facades\Storage;
use Illuminate\Validation\Rules\Password;

class ProfileController extends Controller
{
    /**
     * Display the user's profile.
     */
    public function show()
    {
        $user = auth()->user();
        return view('profile.show', compact('user'));
    }

    /**
     * Show the form for updating the user's photo.
     */
    public function editPhoto()
    {
        $user = auth()->user();
        return view('profile.edit-photo', compact('user'));
    }

    /**
     * Update the user's photo.
     */
    public function updatePhoto(Request $request)
    {
        $request->validate([
            'photo' => 'required|image|mimes:jpeg,png,jpg,gif|max:2048',
        ]);

        $user = auth()->user();

        // Delete old photo if exists
        if ($user->photo && Storage::disk('public')->exists($user->photo)) {
            Storage::disk('public')->delete($user->photo);
        }

        // Store new photo
        $path = $request->file('photo')->store('avatars', 'public');

        $user->update(['photo' => $path]);

        return redirect()->route('profile.show')
            ->with('success', 'Ảnh đại diện đã được cập nhật thành công!');
    }

    /**
     * Show the form for changing the user's password.
     */
    public function editPassword()
    {
        return view('profile.change-password');
    }

    /**
     * Update the user's password.
     */
    public function updatePassword(Request $request)
    {
        $request->validate([
            'current_password' => 'required',
            'password' => ['required', 'confirmed'],
        ]);

        $user = auth()->user();

        // Verify current password
        if (!Hash::check($request->current_password, $user->password)) {
            return back()->withErrors(['current_password' => 'Mật khẩu hiện tại không chính xác.']);
        }

        // Update password
        $user->update([
            'password' => Hash::make($request->password),
        ]);

        // Regenerate session to prevent session fixation
        $request->session()->regenerate();

        return redirect()->route('profile.show')
            ->with('success', 'Mật khẩu đã được thay đổi thành công!');
    }
}
