sequenceDiagram
    autonumber
    participant C as Client
    participant R as Route
    participant ProfilesController as ProfilesController
    participant Follow as Follow
    participant DB as Database
    
    C->>R: GET /resource/{id}
    R->>+ProfilesController: show(id)
    ProfilesController->>+Follow: find(id) / findOrFail(id)
    Follow->>+DB: SELECT * FROM table WHERE id = ?
    DB-->>-Follow: Return record
    Follow-->>-ProfilesController: Model instance
    ProfilesController-->>-R: Return JSON response
    R-->>C: 200 OK with data
    
    Note over ProfilesController,Follow: This sequence retrieves a specific resource by ID
  