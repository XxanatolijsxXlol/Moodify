<?php
namespace App\Policies;

    use App\Models\Conversation;
    use App\Models\User;
    use Illuminate\Auth\Access\HandlesAuthorization;

    class ConversationPolicy
    {
        use HandlesAuthorization;

        public function view(User $user, Conversation $conversation)
        {
            return $user->id === $conversation->user1_id || $user->id === $conversation->user2_id;
        }
    }
