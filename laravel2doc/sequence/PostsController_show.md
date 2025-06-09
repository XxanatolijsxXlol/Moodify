sequenceDiagram
    autonumber
    participant C as Client
    participant R as Route
    participant PostsController as PostsController
    participant Model as Model
    participant DB as Database
    
    C->>R: GET /resource/{id}
    R->>+PostsController: show(id)
    PostsController->>+Model: find(id) / findOrFail(id)
    Model->>+DB: SELECT * FROM table WHERE id = ?
    DB-->>-Model: Return record
    Model-->>-PostsController: Model instance
    PostsController-->>-R: Return JSON response
    R-->>C: 200 OK with data
    
    Note over PostsController,Model: This sequence retrieves a specific resource by ID
  