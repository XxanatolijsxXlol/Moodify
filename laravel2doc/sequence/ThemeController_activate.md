sequenceDiagram
    autonumber
    participant C as Client
    participant R as Route
    participant ThemeController as ThemeController
    participant Theme as Theme
    participant DB as Database
    
    C->>R: Request
    R->>+ThemeController: activate()
    Note over ThemeController: Process request
    alt Uses database
      ThemeController->>+Theme: operation()
      Theme->>+DB: Database query
      DB-->>-Theme: Return data
      Theme-->>-ThemeController: Return result
    else Direct response
      Note over ThemeController: Process without database
    end
    ThemeController-->>-R: Return response
    R-->>C: Response
    
    Note over ThemeController: Generic operation flow
  