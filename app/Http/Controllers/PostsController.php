<?php
namespace App\Http\Controllers;

use App\Models\Post;
use App\Models\User;
use App\Models\Notification;
use Illuminate\Http\Request;
use Intervention\Image\ImageManager;
use Intervention\Image\Drivers\Gd\Driver;
use Illuminate\Support\Facades\Auth;
use Illuminate\Support\Facades\Storage;
use Illuminate\Support\Facades\Log;
use App\Events\NotificationEvent;

class PostsController extends Controller
{
    public function create()
    {
        return view('posts.create');
    }

    public function index()
    {
        $activeTheme = null;
        $posts = collect();
        $suggestedUsers = collect();

        if (auth()->check()) {
            $activeTheme = Auth::user()->themes()->wherePivot('is_active', true)->first();
            $usersFollowed = auth()->user()->following()->pluck('follows.followee_id');

            if ($usersFollowed->isEmpty()) {
                $posts = Post::with('user', 'likes')
                    ->latest()
                    ->paginate(4);
            } else {
                $posts = Post::with('user', 'likes')
                    ->orderByRaw('
                        CASE 
                            WHEN user_id IN (' . $usersFollowed->implode(',') . ') THEN 0 
                            ELSE 1 
                        END, 
                        created_at DESC
                    ')
                    ->paginate(4);
            }

            $suggestedUsers = User::whereNotIn('id', $usersFollowed)
                ->where('id', '!=', auth()->id())
                ->with('profile')
                ->inRandomOrder()
                ->limit(5)
                ->get();
        } else {
            $posts = Post::with('user', 'likes')
                ->latest()
                ->paginate(4);
        }

        return view('posts.index', compact('posts', 'suggestedUsers', 'activeTheme'));
    }

    public function store(Request $request)
    {
        $data = $request->validate([
            'caption' => 'required|string|max:255',
            'image' => 'required|image|mimes:jpeg,png,jpg|max:5120',
            'location' => 'nullable|string|max:100',
        ]);

        $imagePath = $request->file('image')->store('uploads', 'public');
        $manager = new ImageManager(new Driver());

        try {
            $image = $manager->read(Storage::disk('public')->path($imagePath));
            $image->cover(1080, 1080);
            $image->save();
        } catch (\Exception $e) {
            Log::error("Image processing failed: " . $e->getMessage());
            Storage::disk('public')->delete($imagePath);
            return back()->withInput()->withErrors(['image' => 'Failed to process image. Please try again.']);
        }

        auth()->user()->posts()->create([
            'caption' => $data['caption'],
            'image' => $imagePath,
            'location' => $data['location'] ?? null,
        ]);

        return redirect('/')->with('success', 'Post created successfully!');
    }

    public function getComments(Post $post, Request $request)
    {
        try {
            $offset = $request->query('offset', 0);
            $limit = $request->query('limit', 5);

            $comments = $post->comments()
                ->with('user.profile')
                ->latest()
                ->skip($offset)
                ->take($limit)
                ->get()
                ->map(function ($comment) {
                    return [
                        'id' => $comment->id,
                        'user_id' => $comment->user_id,
                        'user' => $comment->user ? $comment->user->name : 'Unknown User',
                        'body' => $comment->body,
                        'profile_image' => ($comment->user && $comment->user->profile)
                            ? ($comment->user->profile->image ? asset('storage/' . $comment->user->profile->image) : asset('images/default-profile.png'))
                            : asset('images/default-profile.png'),
                        'created_at' => $comment->created_at ? $comment->created_at->diffForHumans() : 'Unknown time',
                    ];
                });

            return response()->json([
                'comments' => $comments,
                'total' => $post->comments()->count(),
            ]);
        } catch (\Exception $e) {
            Log::error("Error fetching comments for post {$post->id}: " . $e->getMessage());
            return response()->json(['error' => 'Failed to load comments.'], 500);
        }
    }

    public function show(Post $post)
    {
        $post->load(['user.profile', 'likes', 'comments' => function ($query) {
            $query->with('user.profile')->latest();
        }]);

        $liked = auth()->check() ? $post->likedBy(auth()->user()) : false;

        return view('posts.show', compact('post', 'liked'));
    }

    public function like(Post $post)
    {
        $user = Auth::user();
        if (!$user) {
            return response()->json(['error' => 'Unauthenticated.'], 401);
        }

        try {
            $liked = $post->likedBy($user);

            if ($liked) {
                $post->likes()->where('user_id', $user->id)->delete();
                $isLiked = false;
            } else {
                $post->likes()->create(['user_id' => $user->id]);
                $isLiked = true;

                if ($post->user_id != $user->id) {
                    try {
                        $notification = Notification::create([
                            'user_id' => $post->user_id,
                            'actor_id' => $user->id,
                            'type' => 'like',
                            'subject_id' => $post->id,
                            'subject_type' => Post::class,
                            'message' => "{$user->name} liked your post.",
                            'read' => false,
                        ]);
                        NotificationEvent::dispatch($notification);
                    } catch (\Exception $e) {
                        Log::error("Failed to create like notification: " . $e->getMessage());
                    }
                }
            }

            return response()->json([
                'liked' => $isLiked,
                'count' => $post->likes()->count(),
            ]);
        } catch (\Exception $e) {
            Log::error("Error processing like for post {$post->id}: " . $e->getMessage());
            return response()->json(['error' => 'Failed to process like/unlike.'], 500);
        }
    }

    public function storeComment(Request $request, Post $post)
    {
        $user = Auth::user();
        if (!$user) {
            return response()->json(['error' => 'Unauthenticated.'], 401);
        }

        $request->validate([
            'body' => 'required|string|max:255',
        ]);

        try {
            $comment = $post->comments()->create([
                'user_id' => $user->id,
                'body' => $request->body,
            ]);

            $comment->load('user.profile');

            if ($post->user_id != $user->id) {
                try {
                    $notification = Notification::create([
                        'user_id' => $post->user_id,
                        'actor_id' => $user->id,
                        'type' => 'comment',
                        'subject_id' => $post->id,
                        'subject_type' => Post::class,
                        'message' => "{$user->name} commented on your post.",
                        'read' => false,
                    ]);
                    NotificationEvent::dispatch($notification);
                } catch (\Exception $e) {
                    Log::error("Failed to create comment notification: " . $e->getMessage());
                }
            }

            return response()->json([
                'success' => true,
                'comment' => [
                    'id' => $comment->id,
                    'user_id' => $comment->user_id,
                    'user' => $comment->user ? $comment->user->name : 'Unknown User',
                    'body' => $comment->body,
                    'profile_image' => ($comment->user && $comment->user->profile)
                        ? ($comment->user->profile->image ? asset('storage/' . $comment->user->profile->image) : asset('images/default-profile.png'))
                        : asset('images/default-profile.png'),
                    'created_at' => $comment->created_at->diffForHumans(),
                    'post_id' => $post->id,
                ],
                'total_comments' => $post->comments()->count(),
            ], 201);
        } catch (\Exception $e) {
            Log::error("Error processing comment for post {$post->id}: " . $e->getMessage());
            return response()->json(['error' => 'Failed to post comment.'], 500);
        }
    }

    public function destroy(Post $post)
    {
        // Check if the authenticated user owns the post
        if (auth()->user()->id !== $post->user_id) {
            return redirect()->back()->withErrors(['error' => 'Unauthorized.']);
        }

        try {
            // Delete the post image from storage
            if ($post->image) {
                Storage::disk('public')->delete($post->image);
            }

            // Delete the post (cascading deletes will handle comments, likes, and notifications)
            $post->delete();

            return redirect("/profiles/" . auth()->user()->id)->with('success', 'Post deleted successfully.');
        } catch (\Exception $e) {
            Log::error("Error deleting post {$post->id}: " . $e->getMessage());
            return redirect()->back()->withErrors(['error' => 'Failed to delete post.']);
        }
    }
}