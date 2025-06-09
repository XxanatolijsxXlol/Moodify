<?php
namespace App\Http\Controllers;
use App\Models\Follow;
use App\Models\User;
use Illuminate\Http\Request;

class FollowController extends Controller
{
    public function store(Request $request, User $user)
    {
        $authUser = $request->user();
        if (!$authUser) {
            // Return JSON response for unauthorized access
            return response()->json(['error' => 'Unauthorized'], 401);
        }
        if ($authUser->id == $user->id) {
            // Return JSON response for trying to follow self
            return response()->json(['error' => 'You cannot follow yourself.'], 400);
        }
        if ($authUser->isFollowing($user)) {
            // Return JSON response for already following
            return response()->json(['error' => 'You are already following this user.'], 400);
        }
        Follow::create([
            'follower_id' => $authUser->id,
            'followee_id' => $user->id,
        ]);
        // Return success JSON response with 'status' => 'followed'
        return response()->json(['message' => 'You are now following this user.', 'status' => 'followed'], 200);
    }

    public function destroy(Request $request, User $user)
    {
        $authUser = $request->user();
        if (!$authUser) {
            // Return JSON response for unauthorized access
            return response()->json(['error' => 'Unauthorized'], 401);
        }
        $followExists = $authUser->following()->wherePivot('followee_id', $user->id)->exists();
        if ($followExists) {
            $authUser->following()->detach($user->id);
            // Return success JSON response with 'status' => 'unfollowed'
            return response()->json(['message' => 'You are no longer following this user.', 'status' => 'unfollowed'], 200);
        }
        // Return JSON response for not following
        return response()->json(['error' => 'You are not following this user.'], 400);
    }
}
