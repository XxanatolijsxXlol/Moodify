sequenceDiagram
    autonumber
    participant C as Client
    participant R as Route
    participant MessageController as MessageController
    participant Conversation as Conversation
    participant DB as Database
    
    C->>R: Request
    R->>+MessageController: send()
    Note over MessageController: Process request
    alt Uses database
      MessageController->>+Conversation: operation()
      Conversation->>+DB: Database query
      DB-->>-Conversation: Return data
      Conversation-->>-MessageController: Return result
    else Direct response
      Note over MessageController: Process without database
    end
    MessageController-->>-R: Return response
    R-->>C: Response
    
    Note over MessageController: Generic operation flow
  