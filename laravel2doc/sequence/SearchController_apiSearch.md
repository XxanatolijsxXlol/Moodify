sequenceDiagram
    autonumber
    participant C as Client
    participant R as Route
    participant SearchController as SearchController
    participant User as User
    participant DB as Database
    
    C->>R: Request
    R->>+SearchController: apiSearch()
    Note over SearchController: Process request
    alt Uses database
      SearchController->>+User: operation()
      User->>+DB: Database query
      DB-->>-User: Return data
      User-->>-SearchController: Return result
    else Direct response
      Note over SearchController: Process without database
    end
    SearchController-->>-R: Return response
    R-->>C: Response
    
    Note over SearchController: Generic operation flow
  