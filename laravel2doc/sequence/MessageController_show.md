sequenceDiagram
    autonumber
    participant C as Client
    participant R as Route
    participant MessageController as MessageController
    participant Model as Model
    participant DB as Database
    
    C->>R: GET /resource/{id}
    R->>+MessageController: show(id)
    MessageController->>+Model: find(id) / findOrFail(id)
    Model->>+DB: SELECT * FROM table WHERE id = ?
    DB-->>-Model: Return record
    Model-->>-MessageController: Model instance
    MessageController-->>-R: Return JSON response
    R-->>C: 200 OK with data
    
    Note over MessageController,Model: This sequence retrieves a specific resource by ID
  