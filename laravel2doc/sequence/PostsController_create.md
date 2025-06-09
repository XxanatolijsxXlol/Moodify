sequenceDiagram
    autonumber
    participant C as Client
    participant R as Route
    participant PostsController as PostsController
    participant V as Validator
    participant Model as Model
    participant DB as Database
    
    C->>R: POST /resource
    R->>+PostsController: create(request)
    PostsController->>+V: validate(request)
    V-->>-PostsController: validated data
    PostsController->>+Model: create(data)
    Model->>+DB: INSERT INTO table
    DB-->>-Model: Return new record
    Model-->>-PostsController: New model instance
    PostsController-->>-R: Return JSON response
    R-->>C: 201 Created with data
    
    Note over PostsController,Model: This sequence creates a new resource
  