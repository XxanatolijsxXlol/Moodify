<?php

namespace App\Http\Controllers;

use Illuminate\Http\Request;
use Illuminate\Support\Facades\Cache;
use App\Models\User;
use Illuminate\Support\Facades\Auth; // Import Auth facade

class ProfilesController extends Controller
{
    public function show($user)
    {
        // Find the user by ID; automatically throws a 404 error if not found
        $user = User::findOrFail($user);
        $isFollowing = auth()->user() ? auth()->user()->isFollowing($user) : false;

        $postCount = Cache::remember(
            'count.posts.' . $user->id,
            now()->addSeconds(30),
            function () use ($user) {
                return $user->posts->count();
            });
            $followersCount = Cache::remember(
                'count.followers.' . $user->id,
                now()->addSeconds(30),
                function () use ($user) {
                    if (!$user->profile) {
                        logger('Profile is null for user ID: ' . $user->id);
                        return 0;
                    }
                    // This line previously returned a collection, now it should return a count
                    return $user->followers->count(); // Use the followers relationship directly
                }
            );

        $followingCount = Cache::remember(
            'count.following.' . $user->id,
            now()->addSeconds(30),
            function () use ($user) {
                return $user->following->count();
            });

        // Pass the user to the view
        return view('profile.index', [
            'user' => $user,
            'isFollowing' => $isFollowing,
            'postCount' => $postCount,
            'followersCount' =>  $followersCount,
            'followingCount' => $followingCount,
        ]);
    }

    /**
     * Get the list of followers for a given user.
     *
     * @param  \App\Models\User  $user
     * @return \Illuminate\Http\JsonResponse
     */
    public function getFollowersList(User $user)
    {
        $followers = $user->followers()->with('profile')->get();
        $authFollowingIds = [];
        if (Auth::check()) {
            $authFollowingIds = Auth::user()->following()->pluck('followee_id')->toArray();
        }
        return response()->json([
            'followers' => $followers,
            'auth_following_ids' => $authFollowingIds,
        ]);
    }

    /**
     * Get the list of users a given user is following.
     *
     * @param  \App\Models\User  $user
     * @return \Illuminate\Http\JsonResponse
     */
    public function getFollowingList(User $user)
    {
        $following = $user->following()->with('profile')->get();
        $authFollowingIds = [];
        if (Auth::check()) {
            $authFollowingIds = Auth::user()->following()->pluck('followee_id')->toArray();
        }
        return response()->json([
            'following' => $following,
            'auth_following_ids' => $authFollowingIds,
        ]);
    }
}