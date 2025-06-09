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
