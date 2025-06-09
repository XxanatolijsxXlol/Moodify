<?php
namespace App\Http\Controllers;

use App\Models\User;
use Illuminate\Http\Request;

class SearchController extends Controller
{
    // Existing page route
    public function index(Request $request)
    {
        $query = $request->input('query');
        $users = User::where(function ($q) use ($query) {
            $q->where('username', 'like', '%' . $query . '%')
              ->orWhere('name', 'like', '%' . $query . '%');
        })
        ->where('id', '!=', auth()->id())
        ->get();

        return view('search.results', compact('users', 'query'));
    }

    // New API route
    public function apiSearch(Request $request)
    {
        $query = $request->input('query');
        $users = User::where(function ($q) use ($query) {
            $q->where('username', 'like', '%' . $query . '%')
              ->orWhere('name', 'like', '%' . $query . '%');
        })
        ->where('id', '!=', auth()->id())
        ->limit(10) // Limit results for performance
        ->get()
        ->map(function ($user) {
            return [
                'id' => $user->id,
                'name' => $user->name,
                'username' => $user->username,
                'profile_image' => $user->profile && $user->profile->image ? asset('storage/' . $user->profile->image) : asset('images/default-profile.png'),
            ];
        });

        return response()->json(['users' => $users]);
    }
}