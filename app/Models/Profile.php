<?php
namespace App\Models;

use Illuminate\Database\Eloquent\Model;

class Profile extends Model
{
    // Define the fields that can be mass-assigned
    protected $fillable = [
        'title',
        'description',
        'url',
        'image', // Add all relevant fields
    ];

    // Define the relationship with the User model
    public function user()
    {
        return $this->belongsTo(User::class);
    }
}
