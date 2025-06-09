sequenceDiagram
    autonumber
    participant C as Client
    participant R as Route
    participant FollowController as FollowController
    participant V as Validator
    participant User as User
    participant DB as Database
    
    C->>R: POST /resource
    R->>+FollowController: store(request)
    FollowController->>+V: validate(request)
    V-->>-FollowController: validated data
    FollowController->>+User: create(data)
    User->>+DB: INSERT INTO table
    DB-->>-User: Return new record
    User-->>-FollowController: New model instance
    FollowController-->>-R: Return JSON response
    R-->>C: 201 Created with data
    
    Note over FollowController,User: This sequence creates a new resource
  