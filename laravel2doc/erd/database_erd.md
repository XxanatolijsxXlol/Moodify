erDiagram
  comment {
    int id PK "Primary key"
    int user_id FK "References user"
    int post_id FK "References post"
    string body
    datetime created_at
    datetime updated_at
  }
  conversation {
    int id PK "Primary key"
    int user1_id FK "References user"
    int user2_id FK "References user"
    datetime created_at
    datetime updated_at
  }
  follow {
    int id PK "Primary key"
    int follower_id FK "References follower"
    int followee_id FK "References followee"
    datetime created_at
    datetime updated_at
  }
  like {
    int id PK "Primary key"
    int user_id FK "References user"
    int post_id FK "References post"
    datetime created_at
    datetime updated_at
  }
  message {
    int id PK "Primary key"
    string name
    string text
    int user_id FK "References user"
    int conversation_id FK "References conversation"
    string status
    datetime delivered_at
    datetime read_at
    datetime created_at
    datetime updated_at
  }
  notification {
    int id PK "Primary key"
    int user_id FK "References user"
    int actor_id FK "References user"
    string type
    int subject_id FK "References subject"
    string subject_type
    string message
    string read
    datetime created_at
    datetime updated_at
  }
  post {
    int id PK "Primary key"
    int user_id FK "References user"
    string caption
    string image
    datetime created_at
    datetime updated_at
  }
  profile {
    int id PK "Primary key"
    string title
    string description
    string url
    string image
    int user_id FK "References user"
    datetime created_at
    datetime updated_at
  }
  theme {
    int id PK "Primary key"
    string name
    string css_path
    int creator_id FK "References creator"
    boolean is_public
    string thumbnail
    string description
    int user_id FK "References user"
    datetime created_at
    datetime updated_at
  }
  user {
    int id PK "Primary key"
    string name
    string email
    string password
    string username
    int user1_id FK "References user1"
    int user2_id FK "References user2"
    int follower_id FK "References follower"
    int followee_id FK "References followee"
    datetime created_at
    datetime updated_at
  }
  comment }|--|| user : "user"
  comment }|--|| post : "post"
  conversation ||--|{ message : "messages"
  conversation }|--|| user : "user1"
  conversation }|--|| user : "user2"
  like }|--|| user : "user"
  like }|--|| post : "post"
  message }|--|| user : "user"
  message }|--|| conversation : "conversation"
  notification }|--|| user : "user"
  notification }|--|| user : "actor"
  post ||--|{ like : "likes"
  post ||--|{ comment : "comments"
  post }|--|| user : "user"
  profile }|--|| user : "user"
  theme }|--|| user : "creator"
  theme }|--|{ user : "users"
  user ||--o| profile : "profile"
  user ||--|{ conversation : "conversations"
  user ||--|{ message : "messages"
  user ||--|{ post : "posts"
  user }|--|{ user : "following"
  user }|--|{ user : "followers"
  user }|--|{ theme : "themes"
