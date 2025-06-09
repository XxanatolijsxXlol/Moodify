sequenceDiagram
    autonumber
    participant C as Client
    participant R as Route
    participant ThemeController as ThemeController
    participant Model as Model
    participant DB as Database
    
    C->>R: DELETE /resource/{id}
    R->>+ThemeController: destroy(id)
    ThemeController->>+Model: find(id)
    Model->>+DB: SELECT * FROM table WHERE id = ?
    DB-->>-Model: Return record
    Model-->>-ThemeController: Model instance
    ThemeController->>+Model: delete()
    Model->>+DB: DELETE FROM table WHERE id = ?
    DB-->>-Model: Success
    Model-->>-ThemeController: Success
    ThemeController-->>-R: Return JSON response
    R-->>C: 204 No Content
    
    Note over ThemeController,Model: This sequence removes a resource
  