<?php
namespace App\Models;

use Illuminate\Database\Eloquent\Model;

class Notification extends Model
{
    protected $fillable = ['user_id', 'actor_id', 'type', 'subject_id', 'subject_type', 'message', 'read'];

    public function user()
    {
        return $this->belongsTo(User::class);
    }

    public function actor()
    {
        return $this->belongsTo(User::class, 'actor_id');
    }

    public function subject()
    {
        return $this->morphTo();
    }

    public function getProfileImageAttribute()
    {
        // Fetch the profile for the actor_id
        $profile = $this->actor && $this->actor->profile
            ? $this->actor->profile
            : Profile::where('user_id', $this->actor_id)->first();

        // Return the profile image URL or fallback
        $image = $profile && $profile->image
            ? asset('storage/' . $profile->image)
            : asset('images/default-profile.png');

        // Log for debugging
        \Log::debug('Profile image for notification ' . $this->id, [
            'actor_id' => $this->actor_id,
            'profile_exists' => !!$profile,
            'image' => $image
        ]);

        return $image;
    }
}