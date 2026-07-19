<?php

namespace App\Http\Controllers;

use App\Models\PasswordResetRequest;
use Illuminate\Support\Facades\Hash;
use Illuminate\Support\Str;

class PasswordResetRequestController extends Controller
{
    public function index()
    {
        return view('password-resets.index');
    }

    public function data()
    {
        $requests = PasswordResetRequest::with('user')
            ->orderBy('id', 'desc')
            ->get();

        $data = $requests->map(function ($item) {
            return [
                'id' => $item->id,
                'name' => $item->user->name ?? '-',
                'username' => $item->user->username ?? '-',
                'email' => $item->user->email ?? '-',
                'creation_time' => \Carbon\Carbon::parse($item->creation_time)->translatedFormat('d M Y, H:i'),
                'status' => $item->status == 1 ? 'Selesai' : 'Pending',
            ];
        });

        return response()->json(['data' => $data]);
    }

    public function process($id)
    {
        if (auth()->user()->role != 1) {
            abort(403, 'Hanya Super Admin yang dapat memproses reset password.');
        }

        $request_ = PasswordResetRequest::findOrFail($id);

        if ($request_->status == 1) {
            return response()->json(['success' => false, 'message' => 'Permintaan ini sudah diproses sebelumnya.'], 422);
        }

        $newPassword = Str::random(8);

        $request_->user->update([
            'update_time' => now(),
            'update_id' => auth()->id(),
            'password' => Hash::make($newPassword),
        ]);

        $request_->update([
            'update_time' => now(),
            'update_id' => auth()->id(),
            'status' => 1,
        ]);

        return response()->json([
            'success' => true,
            'message' => 'Password berhasil direset.',
            'new_password' => $newPassword,
            'username' => $request_->user->username,
        ]);
    }
}