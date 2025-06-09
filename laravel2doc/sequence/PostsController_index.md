sequenceDiagram
    autonumber
    participant C as Client
    participant R as Route
    participant PostsController as PostsController
    participant Follow as Follow
    participant DB as Database
    
    C->>R: GET /resource
    R->>+PostsController: index()
    PostsController->>+Follow: all() / get() / paginate()
    Follow->>+DB: SELECT * FROM table
    DB-->>-Follow: Return records
    Follow-->>-PostsController: Collection of models
    PostsController-->>-R: Return JSON response
    R-->>C: 200 OK with data
    
    Note over PostsController,Follow: This sequence retrieves a list of resources
  