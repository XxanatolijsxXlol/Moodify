sequenceDiagram
    autonumber
    participant C as Client
    participant R as Route
    participant ThemeController as ThemeController
    participant User as User
    participant DB as Database
    
    C->>R: Request
    R->>+ThemeController: activateDefault()
    Note over ThemeController: Process request
    alt Uses database
      ThemeController->>+User: operation()
      User->>+DB: Database query
      DB-->>-User: Return data
      User-->>-ThemeController: Return result
    else Direct response
      Note over ThemeController: Process without database
    end
    ThemeController-->>-R: Return response
    R-->>C: Response
    
    Note over ThemeController: Generic operation flow
  