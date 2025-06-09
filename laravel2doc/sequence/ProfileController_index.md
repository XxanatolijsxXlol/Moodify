sequenceDiagram
    autonumber
    participant C as Client
    participant R as Route
    participant ProfileController as ProfileController
    participant Model as Model
    participant DB as Database
    
    C->>R: GET /resource
    R->>+ProfileController: index()
    ProfileController->>+Model: all() / get() / paginate()
    Model->>+DB: SELECT * FROM table
    DB-->>-Model: Return records
    Model-->>-ProfileController: Collection of models
    ProfileController-->>-R: Return JSON response
    R-->>C: 200 OK with data
    
    Note over ProfileController,Model: This sequence retrieves a list of resources
  