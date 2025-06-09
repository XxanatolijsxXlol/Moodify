<?php
namespace App\Models;

use Illuminate\Database\Eloquent\Factories\HasFactory;
use Illuminate\Foundation\Auth\User as Authenticatable;
use Illuminate\Notifications\Notifiable;


class User extends Authenticatable
{
    use HasFactory, Notifiable;

    /**
     * The attributes that are mass assignable.
     *
     * @var array<int, string>
     */
    protected $fillable = [
        'name',
        'email',
        'password',
        'username',
    ];

    public function conversations()
    {
        return $this->hasMany(Conversation::class, 'user1_id')
            ->orWhere('user2_id', $this->id);
    }

    public function messages()
    {
        return $this->hasMany(Message::class);
    }

    /**following function */
    public function following()
    {
        return $this->belongsToMany(User::class, 'follows', 'follower_id', 'followee_id');
    }

    public function followers()
    {
        return $this->belongsToMany(User::class, 'follows', 'followee_id', 'follower_id');
    }

    public function isFollowing(User $user)
    {
        return $this->following()->where('followee_id', $user->id)->exists();
    }

    /**
     * The attributes that should be hidden for serialization.
     *
     * @var array<int, string>
     */
    protected $hidden = [
        'password',
        'remember_token',
    ];

    /**
     * Get the attributes that should be cast.
     *
     * @return array<string, string>
     */
    protected function casts(): array
    {
        return [
            'email_verified_at' => 'datetime',
            'password' => 'hashed',
        ];
    }

    /**
     * Get the user's posts.
     */
    public function posts()
    {
        return $this->hasMany(Post::class)->orderBy('created_at', 'DESC');
    }

    /**
     * Get the user's profile, creating one with a default image if it doesn’t exist.
     */
    public function profile()
    {
        return $this->hasOne(Profile::class)->withDefault([
            'image' => 'profile_images/default-profile.png',
        ]);
    }

     public function themes()
    {
        return $this->belongsToMany(Theme::class, 'user_themes')
                    ->withPivot('is_active')
                    ->withTimestamps();
    }

    /**
     * Accessor to ensure a profile exists in the database with a default image.
     */
    public function getProfileAttribute()
    {
        // Load the profile relationship if not already loaded
        if (!$this->relationLoaded('profile')) {
            $this->load('profile');
        }

        // If no profile exists, create one with the default image
        if (!$this->relations['profile']) {
            $this->profile()->create([
                'image' => 'profile_images/default-profile.png',
            ]);
            $this->load('profile'); // Reload the relationship after creation
        }

        return $this->relations['profile'];
    }
}