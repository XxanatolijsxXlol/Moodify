sequenceDiagram
    autonumber
    participant C as Client
    participant R as Route
    participant ThemeController as ThemeController
    participant Theme as Theme
    participant DB as Database
    
    C->>R: GET /resource
    R->>+ThemeController: index()
    ThemeController->>+Theme: all() / get() / paginate()
    Theme->>+DB: SELECT * FROM table
    DB-->>-Theme: Return records
    Theme-->>-ThemeController: Collection of models
    ThemeController-->>-R: Return JSON response
    R-->>C: 200 OK with data
    
    Note over ThemeController,Theme: This sequence retrieves a list of resources
  