<?php

namespace App\Http\Controllers;

use App\Helpers\ActivityLogHelper;
use Illuminate\Http\RedirectResponse;
use Illuminate\Http\Request;
use Illuminate\Support\Facades\Hash;
use Illuminate\Validation\Rules\Password;
use Illuminate\View\View;

class AhliProfileController extends Controller
{
    public function show(Request $request): View
    {
        $user = $request->user();

        abort_unless((int) $user->role === 2, 403);

        $user->load('ahliKariah.masjid');

        return view('user.profile', [
            'user' => $user,
            'ahli' => $user->ahliKariah,
        ]);
    }

    public function updatePassword(Request $request): RedirectResponse
    {
        $user = $request->user();

        abort_unless((int) $user->role === 2, 403);

        $validated = $request->validate([
            'current_password' => ['required', 'string'],
            'password' => [
                'required',
                'string',
                'different:current_password',
                'confirmed',
                Password::min(8)->letters()->numbers()->symbols(),
            ],
        ], [
            'current_password.required' => 'Kata laluan semasa diperlukan.',
            'password.required' => 'Kata laluan baharu diperlukan.',
            'password.different' => 'Kata laluan baharu mesti berbeza daripada kata laluan semasa.',
            'password.confirmed' => 'Pengesahan kata laluan baharu tidak sepadan.',
            'password.min' => 'Kata laluan baharu mestilah sekurang-kurangnya 8 aksara.',
            'password.letters' => 'Kata laluan baharu mesti mempunyai sekurang-kurangnya satu huruf.',
            'password.numbers' => 'Kata laluan baharu mesti mempunyai sekurang-kurangnya satu nombor.',
            'password.symbols' => 'Kata laluan baharu mesti mempunyai sekurang-kurangnya satu simbol.',
        ]);

        if (!Hash::check($validated['current_password'], $user->password)) {
            return back()->withErrors([
                'current_password' => 'Kata laluan semasa tidak betul.',
            ]);
        }

        $user->password = Hash::make($validated['password']);
        $user->save();

        ActivityLogHelper::logPasswordChange($user->id);

        return back()->with('success', 'Kata laluan anda berjaya ditukar.');
    }
}
