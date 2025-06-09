sequenceDiagram
    autonumber
    participant C as Client
    participant R as Route
    participant PostsController as PostsController
    participant User as User
    participant DB as Database
    
    C->>R: Request
    R->>+PostsController: getComments()
    Note over PostsController: Process request
    alt Uses database
      PostsController->>+User: operation()
      User->>+DB: Database query
      DB-->>-User: Return data
      User-->>-PostsController: Return result
    else Direct response
      Note over PostsController: Process without database
    end
    PostsController-->>-R: Return response
    R-->>C: Response
    
    Note over PostsController: Generic operation flow
  