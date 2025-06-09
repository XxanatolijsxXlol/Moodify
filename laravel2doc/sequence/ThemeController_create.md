sequenceDiagram
    autonumber
    participant C as Client
    participant R as Route
    participant ThemeController as ThemeController
    participant V as Validator
    participant Model as Model
    participant DB as Database
    
    C->>R: POST /resource
    R->>+ThemeController: create(request)
    ThemeController->>+V: validate(request)
    V-->>-ThemeController: validated data
    ThemeController->>+Model: create(data)
    Model->>+DB: INSERT INTO table
    DB-->>-Model: Return new record
    Model-->>-ThemeController: New model instance
    ThemeController-->>-R: Return JSON response
    R-->>C: 201 Created with data
    
    Note over ThemeController,Model: This sequence creates a new resource
  