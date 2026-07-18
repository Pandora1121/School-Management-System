<?php

namespace App\Http\Controllers;

use Illuminate\Http\Request;
use Illuminate\Support\Facades\Auth;
use Illuminate\Support\Facades\Hash;

class ProfileController extends Controller
{
    public function show()
    {
        $user = auth()->user();
        return view('profile.show', compact('user'));
    }

    public function update(Request $request)
    {
        $user = auth()->user();

        $validated = $request->validate([
            'name' => ['required', 'string', 'max:255'],
            'email' => ['required', 'email', 'max:255', 'unique:tbl_users,email,'.$user->id],
            'phone' => ['nullable', 'string', 'max:20'],
            'img_url' => ['nullable', 'image', 'max:2048'],
        ]);

        $imgName = $user->img_url;
        if ($request->hasFile('img_url')) {
            $imgName = now()->format('YmdHis').'-'.$request->file('img_url')->getClientOriginalName();
            $request->file('img_url')->move(public_path('uploads/profiles'), $imgName);
        }

        // Whitelist manual — mencegah user menyisipkan field sensitif seperti role/status
        $user->update([
            'update_time' => now(),
            'update_id' => $user->id,
            'name' => $validated['name'],
            'email' => $validated['email'],
            'phone' => $validated['phone'] ?? null,
            'img_url' => $imgName,
        ]);

     return response()->json(['success' => true, 'message' => 'Profil berhasil diperbarui.', 'img_url' => $imgName]);
    }

    public function updatePassword(Request $request)
{
    $user = auth()->user();

    $validated = $request->validate([
        'current_password' => ['required', 'string'],
        'password' => ['required', 'string', 'min:8', 'confirmed'],
    ]);

    if (! Hash::check($validated['current_password'], $user->password)) {
        return response()->json([
            'success' => false,
            'errors' => ['current_password' => ['Password saat ini salah.']],
        ], 422);
    }

    $user->update([
        'update_time' => now(),
        'update_id' => $user->id,
        'password' => Hash::make($validated['password']),
    ]);

        return response()->json(['success' => true, 'message' => 'Password berhasil diubah.']);
    }
}