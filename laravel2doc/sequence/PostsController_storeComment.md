sequenceDiagram
    autonumber
    participant C as Client
    participant R as Route
    participant PostsController as PostsController
    participant Model as Model
    participant DB as Database
    
    C->>R: Request
    R->>+PostsController: storeComment()
    Note over PostsController: Process request
    alt Uses database
      PostsController->>+Model: operation()
      Model->>+DB: Database query
      DB-->>-Model: Return data
      Model-->>-PostsController: Return result
    else Direct response
      Note over PostsController: Process without database
    end
    PostsController-->>-R: Return response
    R-->>C: Response
    
    Note over PostsController: Generic operation flow
  