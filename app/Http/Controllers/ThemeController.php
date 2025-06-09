<?php

namespace App\Http\Controllers;

use App\Models\Theme;
use Illuminate\Http\Request;
use Illuminate\Support\Facades\Auth;
use Illuminate\Support\Facades\Storage;
use Illuminate\Support\Facades\Log; // Added for debugging

class ThemeController extends Controller
{
    public function index()
    {
        $themes = Theme::where('is_public', true)->orWhere('creator_id', Auth::id())->get();
        $activeTheme = Auth::user()->themes()->wherePivot('is_active', true)->first();
        return view('themes.index', compact('themes', 'activeTheme'));
    }

    public function create()
    {
        return view('themes.create');
    }

    public function store(Request $request)
    {
        // Log the request to see what 'is_public' comes in as (for debugging)
        Log::info('Incoming request data for theme store:', $request->all());

        // Validate the incoming request
        $validated = $request->validate([
            'name' => 'required|string|max:255',
            // Correct validation rule for a checkbox: it's either present ('on') or not.
            // 'boolean' will automatically cast 'on' to true, and if the field is absent (unchecked),
            // due to 'nullable', it will not be included in $validated.
            'is_public' => 'nullable|boolean',
            'css_content' => 'required|string',
            'thumbnail' => 'nullable|image|mimes:jpeg,png,jpg|max:2048',
        ]);

        // Fix: 'is_public' will be present in $validated as true if checked,
        // but it will be ABSENT from $validated if unchecked.
        // We need to explicitly set it to false if it's not present.
        $isPublic = $validated['is_public'] ?? false;
        
        // Log the determined isPublic value
        Log::info('Determined isPublic value:', ['is_public' => $isPublic]);

        $user = Auth::user();
        $themeName = str_replace(' ', '-', strtolower($validated['name']));
        
        // Handle thumbnail upload
        $thumbnailPath = null;
        if ($request->hasFile('thumbnail')) {
            // Ensure the themes/thumbnails directory exists
            Storage::disk('public')->makeDirectory('themes/thumbnails');
            // Store thumbnail with unique filename (e.g., userId-timestamp-filename.jpg)
            $thumbnailPath = $request->file('thumbnail')->store('themes/thumbnails', 'public');
        }

        // Correct path for storage/app/public/themes/css
        $cssPath = "themes/css/{$user->id}-{$themeName}.css";
        // Path to store in database
        $dbCssPath = "storage/themes/css/{$user->id}-{$themeName}.css"; // This path is relative to the public disk

        // Generate CSS file content
        $cssContent = "/* ==================== */\n";
        $cssContent .= "/* {$validated['name']} THEME OVERRIDE */\n";
        $cssContent .= "/* ==================== */\n\n";
        $cssContent .= $validated['css_content'];

        // Ensure the themes/css directory exists
        Storage::disk('public')->makeDirectory('themes/css');
        // Save CSS file to storage/app/public/themes/css
        Storage::disk('public')->put($cssPath, $cssContent);

        // Save theme
        $theme = Theme::create([
            'name' => $validated['name'],
            'css_path' => $dbCssPath,
            'creator_id' => $user->id,
            'is_public' => $isPublic, // Use the correctly determined boolean value
            'thumbnail' => $thumbnailPath,
        ]);

        // Attach theme to user and set as active
        // First, deactivate all other themes for this user
        $user->themes()->updateExistingPivot($user->themes()->wherePivot('is_active', true)->pluck('theme_id')->toArray(), ['is_active' => false]);
        // Then, attach the new theme if not already attached, and set it as active
        $user->themes()->syncWithoutDetaching([$theme->id => ['is_active' => true, 'selected_at' => now()]]);
        // If the theme was already attached but inactive, this will set it as active.

        return redirect()->route('themes.index')->with('success', 'Theme created successfully!');
    }
 public function activateDefault(Theme $theme)


    {
        $user = Auth::user();

        // Logic to set the default theme.
        // This assumes your User model has a `theme_id` foreign key
        // that can be null or a special value for the default theme.
        $user->themes()->detach(); // Or a specific ID for your 'default' theme entry if you have one
        $user->save();

        return redirect()->route('themes.index')->with('success', 'Default theme activated successfully!');
    }

    public function activate(Theme $theme)
    {
        $user = Auth::user();
        // Deactivate current active theme for the user
        $user->themes()->updateExistingPivot($user->themes()->wherePivot('is_active', true)->pluck('theme_id')->toArray(), ['is_active' => false]);

        // Attach the new theme if not already attached, and set it as active
        $user->themes()->syncWithoutDetaching([$theme->id => ['is_active' => true, 'selected_at' => now()]]);

        return back()->with('success', 'Theme activated successfully!');
    }

 
    public function destroy(Theme $theme)
    {
        // Authorize deletion (only creator or admin)
        if (Auth::id() !== $theme->creator_id && !Auth::user()->is_admin) {
            return back()->with('error', 'You are not authorized to delete this theme.');
        }

        // Delete the theme (triggers booted::deleting if set up, or handle manually here)
        // Manual deletion of files if not handled by model events:
        if ($theme->css_path) {
            Storage::disk('public')->delete(str_replace('storage/', '', $theme->css_path));
        }
        if ($theme->thumbnail) {
            Storage::disk('public')->delete($theme->thumbnail);
        }

        // Detach theme from all users
        $theme->users()->detach();

        // Delete the theme record
        $theme->delete();

        return redirect()->route('themes.index')->with('success', 'Theme deleted successfully!');
    }
}