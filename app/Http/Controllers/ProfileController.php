<?php

namespace App\Http\Controllers;

use App\Http\Requests\ProfileRequest;
use Illuminate\View\View;

class ProfileController extends Controller
{
    /**
     * プロフィール編集画面を表示する
     */
    public function edit(): View
    {
        $user = auth()->user();
        $profile = $user->profile;

        return view('profile.edit', compact('user', 'profile'));
    }

    /**
     * プロフィール情報を更新する
     */
    public function update(ProfileRequest $request)
    {
        $user = auth()->user();

        $validated = $request->validated();

        if ($request->hasFile('profile_image')) {
            $path = $request->file('profile_image')
                ->store('profiles', 'public');
        }

        $user->update([
            'name' => $validated['name'],
        ]);

        $user->profile()->updateOrCreate(
            ['user_id' => $user->id],
            [
                'postal_code' => $validated['postal_code'],
                'address' => $validated['address'],
                'building' => $request->input('building'),
                'profile_image_path' => $path ?? $user->profile?->profile_image_path,
            ]
        );

        return redirect()->route('items.index');
    }
}
