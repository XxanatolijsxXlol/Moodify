sequenceDiagram
    autonumber
    participant C as Client
    participant R as Route
    participant FollowController as FollowController
    participant User as User
    participant DB as Database
    
    C->>R: DELETE /resource/{id}
    R->>+FollowController: destroy(id)
    FollowController->>+User: find(id)
    User->>+DB: SELECT * FROM table WHERE id = ?
    DB-->>-User: Return record
    User-->>-FollowController: Model instance
    FollowController->>+User: delete()
    User->>+DB: DELETE FROM table WHERE id = ?
    DB-->>-User: Success
    User-->>-FollowController: Success
    FollowController-->>-R: Return JSON response
    R-->>C: 204 No Content
    
    Note over FollowController,User: This sequence removes a resource
  