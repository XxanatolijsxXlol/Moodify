sequenceDiagram
    autonumber
    participant C as Client
    participant R as Route
    participant SearchController as SearchController
    participant User as User
    participant DB as Database
    
    C->>R: GET /resource
    R->>+SearchController: index()
    SearchController->>+User: all() / get() / paginate()
    User->>+DB: SELECT * FROM table
    DB-->>-User: Return records
    User-->>-SearchController: Collection of models
    SearchController-->>-R: Return JSON response
    R-->>C: 200 OK with data
    
    Note over SearchController,User: This sequence retrieves a list of resources
  