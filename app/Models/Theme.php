<?php

namespace App\Models;

use Illuminate\Database\Eloquent\Model;
use Illuminate\Support\Facades\Storage;

class Theme extends Model
{
    protected $fillable = ['name', 'css_path', 'creator_id', 'is_public', 'thumbnail', 'description'];

    public function creator()
    {
        return $this->belongsTo(User::class)->withDefault(['username' => 'System']);
    }

    public function users()
    {
        return $this->belongsToMany(User::class, 'user_themes')
                    ->withPivot('is_active', 'selected_at')
                    ->withTimestamps();
    }

    protected static function booted()
    {
        static::deleting(function ($theme) {
            // Detach all users from this theme in user_themes
            $theme->users()->detach();

            // Delete thumbnail file if it exists
            if ($theme->thumbnail) {
                Storage::disk('public')->delete($theme->thumbnail);
            }

            // Delete CSS file if it exists
            if ($theme->css_path) {
                // Remove 'storage/' prefix for Storage::delete()
                $cssPath = str_replace('storage/', '', $theme->css_path);
                Storage::disk('public')->delete($cssPath);
            }
        });
    }
}