sequenceDiagram
    autonumber
    participant C as Client
    participant R as Route
    participant MessageController as MessageController
    participant Model as Model
    participant DB as Database
    
    C->>R: Request
    R->>+MessageController: markSpecificMessagesAsRead()
    Note over MessageController: Process request
    alt Uses database
      MessageController->>+Model: operation()
      Model->>+DB: Database query
      DB-->>-Model: Return data
      Model-->>-MessageController: Return result
    else Direct response
      Note over MessageController: Process without database
    end
    MessageController-->>-R: Return response
    R-->>C: Response
    
    Note over MessageController: Generic operation flow
  