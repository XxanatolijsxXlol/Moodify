classDiagram
  class Comment {
    +user_id
    +post_id
    +body
  }
  class Conversation {
    +user1_id
    +user2_id
  }
  class Follow {
    +follower_id
    +followee_id
  }
  class Like {
    +user_id
    +post_id
  }
  class Message {
    +name
    +text
    +user_id
    +conversation_id
    +status
    +// Add status
        delivered_at
    +// Add delivered_at
        read_at
    +// Add read_at
  }
  class Notification {
    +user_id
    +actor_id
    +type
    +subject_id
    +subject_type
    +message
    +read
  }
  class Post {
    +user_id
    +caption
    +image
  }
  class Profile {
    +title
    +description
    +url
    +image
    +// Add all relevant fields
  }
  class Theme {
    +name
    +css_path
    +creator_id
    +is_public
    +thumbnail
    +description
  }
  class User {
    +name
    +email
    +password
    +username
  }
  class AuthenticatedSessionController {
    <<Controller>>
    +create()
    +store(LoginRequest $request)
    +destroy(Request $request)
  }
  class ConfirmablePasswordController {
    <<Controller>>
    +show()
    +store(Request $request)
  }
  class EmailVerificationNotificationController {
    <<Controller>>
    +store(Request $request)
  }
  class EmailVerificationPromptController {
    <<Controller>>
    +__invoke(Request $request)
  }
  class NewPasswordController {
    <<Controller>>
    +create(Request $request)
    +store(Request $request)
  }
  class PasswordController {
    <<Controller>>
    +update(Request $request)
  }
  class PasswordResetLinkController {
    <<Controller>>
    +create()
    +store(Request $request)
  }
  class RegisteredUserController {
    <<Controller>>
    +create()
    +store(Request $request)
  }
  class VerifyEmailController {
    <<Controller>>
    +__invoke(EmailVerificationRequest $request)
  }
  class Controller {
    <<Controller>>
  }
  class FollowController {
    <<Controller>>
    +store(Request $request, User $user)
    +destroy(Request $request, User $user)
  }
  class MessageController {
    <<Controller>>
    +index()
    +show(Conversation $conversation)
    +start(Request $request)
    +send(Request $request)
    +markAsDelivered(Request $request, Message $message)
    +markAsRead(Request $request, Conversation $conversation)
    +markSpecificMessagesAsRead(Request $request, Conversation $conversation)
  }
  class NotificationController {
    <<Controller>>
    +index()
    +markAsRead(Request $request)
  }
  class PostsController {
    <<Controller>>
    +create()
    +index()
    +store(Request $request)
    +getComments(Post $post, Request $request)
    +show(Post $post)
    +like(Post $post)
    +storeComment(Request $request, Post $post)
  }
  class ProfileController {
    <<Controller>>
    +edit(Request $request)
    +index(Request $request)
    +update(Request $request)
    +destroy(Request $request)
  }
  class ProfilesController {
    <<Controller>>
    +show($user)
  }
  class SearchController {
    <<Controller>>
    +index(Request $request)
    +apiSearch(Request $request)
  }
  class ThemeController {
    <<Controller>>
    +index()
    +create()
    +store(Request $request)
    +activateDefault(Theme $theme)
    +activate(Theme $theme)
    +destroy(Theme $theme)
  }
  Comment <-- User : user
  Comment <-- Post : post
  Conversation --* Message : messages
  Conversation <-- User : user1
  Conversation <-- User : user2
  Like <-- User : user
  Like <-- Post : post
  Message <-- User : user
  Message <-- Conversation : conversation
  Notification <-- User : user
  Notification <-- User : actor
  Notification --> Unknown : subject
  Post --* Like : likes
  Post --* Comment : comments
  Post <-- User : user
  Profile <-- User : user
  Theme <-- User : creator
  Theme <--* User : users
  User --> Profile : profile
  User --* Conversation : conversations
  User --* Message : messages
  User --* Post : posts
  User <--* User : following
  User <--* User : followers
  User <--* Theme : themes
