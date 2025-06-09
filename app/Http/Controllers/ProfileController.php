<?php
namespace App\Http\Controllers;

use Illuminate\Http\Request;
use Illuminate\Http\RedirectResponse;
use Illuminate\Support\Facades\Auth;
use Illuminate\Support\Facades\Redirect;
use App\Models\User;
use App\Models\Profile;

class ProfileController extends Controller
{
    public function edit(Request $request): \Illuminate\View\View
    {
        return view('profile.edit', ['user' => $request->user()]);
    }
    
    public function index(Request $request)
    {
        $user = $request->user(); // Get the logged-in user

        // The profile will be auto-created with default image if it doesn’t exist
        return view('profile.index', ['user' => $user]);
    }
    
    public function update(Request $request): RedirectResponse
    {
        $user = auth()->user();

        $validatedData = $request->validate([
            'title'       => 'nullable|string|max:255',
            'description' => 'nullable|string|max:255',
            'url'         => 'nullable|url|max:255',
            'image'       => 'nullable|image|mimes:jpg,jpeg,png,gif|max:2048',
        ]);

        if ($request->hasFile('image')) {
            $validatedData['image'] = $request->file('image')->store('profile_images', 'public');
        }

        $user->profile()->updateOrCreate(
            ['user_id' => $user->id],
            $validatedData
        );

        return redirect()->route('profile.show')->with('status', 'Profile updated successfully!');
    }
    
    public function destroy(Request $request): RedirectResponse
    {
        $request->validateWithBag('userDeletion', ['password' => ['required', 'current_password']]);
        $user = $request->user();
        Auth::logout();
        $user->delete();
        $request->session()->invalidate();
        $request->session()->regenerateToken();
        return Redirect::to('/');
    }
}