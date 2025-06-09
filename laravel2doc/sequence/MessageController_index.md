sequenceDiagram
    autonumber
    participant C as Client
    participant R as Route
    participant MessageController as MessageController
    participant User as User
    participant DB as Database
    
    C->>R: GET /resource
    R->>+MessageController: index()
    MessageController->>+User: all() / get() / paginate()
    User->>+DB: SELECT * FROM table
    DB-->>-User: Return records
    User-->>-MessageController: Collection of models
    MessageController-->>-R: Return JSON response
    R-->>C: 200 OK with data
    
    Note over MessageController,User: This sequence retrieves a list of resources
  