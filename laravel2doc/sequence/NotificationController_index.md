sequenceDiagram
    autonumber
    participant C as Client
    participant R as Route
    participant NotificationController as NotificationController
    participant Model as Model
    participant DB as Database
    
    C->>R: GET /resource
    R->>+NotificationController: index()
    NotificationController->>+Model: all() / get() / paginate()
    Model->>+DB: SELECT * FROM table
    DB-->>-Model: Return records
    Model-->>-NotificationController: Collection of models
    NotificationController-->>-R: Return JSON response
    R-->>C: 200 OK with data
    
    Note over NotificationController,Model: This sequence retrieves a list of resources
  