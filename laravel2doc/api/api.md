# API Documentation

## Project: laravel/laravel

Laravel Version: v11.44.7

Generated: 6/5/2025, 8:59:25 AM

## Table of Contents

- [auth](#auth)
- [web](#web)

## auth

| Method | Endpoint | Handler | Description |
|--------|----------|---------|-------------|
| GET | register | RegisteredUserController::class, 'create' | List register |
| POST | register | RegisteredUserController::class, 'store' | Create a new register |
| GET | login | AuthenticatedSessionController::class, 'create' | List login |
| POST | login | AuthenticatedSessionController::class, 'store' | Create a new login |
| GET | forgot-password | PasswordResetLinkController::class, 'create' | List forgot-password |
| POST | forgot-password | PasswordResetLinkController::class, 'store' | Create a new forgot-password |
| GET | reset-password/{token} | NewPasswordController::class, 'create' | Retrieve a specific {token} |
| POST | reset-password | NewPasswordController::class, 'store' | Create a new reset-password |
| GET | verify-email | EmailVerificationPromptController::class)
        ->name('verification.notice');

    Route::get('verify-email/{id}/{hash}', VerifyEmailController::class)
        ->middleware(['signed', 'throttle:6,1' | List verify-email |
| POST | email/verification-notification | EmailVerificationNotificationController::class, 'store' | Create a new verification-notification |
| GET | confirm-password | ConfirmablePasswordController::class, 'show' | List confirm-password |
| POST | confirm-password | ConfirmablePasswordController::class, 'store' | Create a new confirm-password |
| PUT | password | PasswordController::class, 'update' | Update a specific password |
| POST | logout | AuthenticatedSessionController::class, 'destroy' | Create a new logout |

### GET register

**Handler:** RegisteredUserController::class, 'create'

**Description:** List register

---

### POST register

**Handler:** RegisteredUserController::class, 'store'

**Description:** Create a new register

---

### GET login

**Handler:** AuthenticatedSessionController::class, 'create'

**Description:** List login

---

### POST login

**Handler:** AuthenticatedSessionController::class, 'store'

**Description:** Create a new login

---

### GET forgot-password

**Handler:** PasswordResetLinkController::class, 'create'

**Description:** List forgot-password

---

### POST forgot-password

**Handler:** PasswordResetLinkController::class, 'store'

**Description:** Create a new forgot-password

---

### GET reset-password/{token}

**Handler:** NewPasswordController::class, 'create'

**Description:** Retrieve a specific {token}

---

### POST reset-password

**Handler:** NewPasswordController::class, 'store'

**Description:** Create a new reset-password

---

### GET verify-email

**Handler:** EmailVerificationPromptController::class)
        ->name('verification.notice');

    Route::get('verify-email/{id}/{hash}', VerifyEmailController::class)
        ->middleware(['signed', 'throttle:6,1'

**Description:** List verify-email

---

### POST email/verification-notification

**Handler:** EmailVerificationNotificationController::class, 'store'

**Description:** Create a new verification-notification

---

### GET confirm-password

**Handler:** ConfirmablePasswordController::class, 'show'

**Description:** List confirm-password

---

### POST confirm-password

**Handler:** ConfirmablePasswordController::class, 'store'

**Description:** Create a new confirm-password

---

### PUT password

**Handler:** PasswordController::class, 'update'

**Description:** Update a specific password

---

### POST logout

**Handler:** AuthenticatedSessionController::class, 'destroy'

**Description:** Create a new logout

---

## web

| Method | Endpoint | Handler | Description |
|--------|----------|---------|-------------|
| GET | / | PostsController::class, 'index' | List resource |
| GET | /profiles/{user} | ProfilesController::class, 'show' | Retrieve a specific {user} |
| GET | /api/search | SearchController::class, 'apiSearch' | List search |
| GET | /search | SearchController::class, 'index' | List search |
| POST | /posts/{post}/comments | PostsController::class, 'storeComment' | Create a new comments |
| GET | /posts/{post}/comments | PostsController::class, 'getComments' | Retrieve a specific comments |
| POST | /posts/{post}/like | PostsController::class, 'like' | Create a new like |
| GET | /profile | ProfileController::class, 'index' | List profile |
| GET | /profile/edit | ProfileController::class, 'edit' | List edit |
| PATCH | /profile | ProfileController::class, 'update' | Update a specific profile |
| DELETE | /profile | ProfileController::class, 'destroy' | Delete a specific profile |
| GET | /p/create | PostsController::class, 'create' | List create |
| POST | /p | PostsController::class, 'store' | Create a new p |
| GET | /p/{post} | PostsController::class, 'show' | Retrieve a specific {post} |
| POST | /users/follow/{user} | FollowController::class, 'store' | Create a new {user} |
| DELETE | /users/unfollow/{user} | FollowController::class, 'destroy' | Delete a specific {user} |
| GET | /messages | MessageController::class, 'index' | List messages |
| GET | /messages/{conversation} | MessageController::class, 'show' | Retrieve a specific {conversation} |
| POST | /messages/start | MessageController::class, 'start' | Create a new start |
| POST | /messages/send | MessageController::class, 'send' | Create a new send |
| POST | /messages/{message}/delivered | MessageController::class, 'markAsDelivered' | Create a new delivered |
| POST | /conversations/{conversation}/mark-as-read | MessageController::class, 'markAsRead' | Create a new mark-as-read |
| POST | /conversations/{conversation}/mark-messages-as-read | MessageController::class, 'markSpecificMessagesAsRead' | Create a new mark-messages-as-read |
| GET | /notifications | NotificationController::class, 'index' | List notifications |
| POST | /notifications/read | NotificationController::class, 'markAsRead' | Create a new read |
| GET | themes | ThemeController::class, 'index' | List themes |
| GET | themes/create | ThemeController::class, 'create' | List create |
| POST | themes | ThemeController::class, 'store' | Create a new themes |
| POST | themes/{theme}/activate | ThemeController::class, 'activate' | Create a new activate |
| DELETE | themes/{theme} | ThemeController::class, 'destroy' | Delete a specific {theme} |
| POST | /themes/activate/default | ThemeController::class, 'activateDefault' | Create a new default |

### GET /

**Handler:** PostsController::class, 'index'

**Description:** List resource

---

### GET /profiles/{user}

**Handler:** ProfilesController::class, 'show'

**Description:** Retrieve a specific {user}

---

### GET /api/search

**Handler:** SearchController::class, 'apiSearch'

**Description:** List search

---

### GET /search

**Handler:** SearchController::class, 'index'

**Description:** List search

---

### POST /posts/{post}/comments

**Handler:** PostsController::class, 'storeComment'

**Description:** Create a new comments

---

### GET /posts/{post}/comments

**Handler:** PostsController::class, 'getComments'

**Description:** Retrieve a specific comments

---

### POST /posts/{post}/like

**Handler:** PostsController::class, 'like'

**Description:** Create a new like

---

### GET /profile

**Handler:** ProfileController::class, 'index'

**Description:** List profile

---

### GET /profile/edit

**Handler:** ProfileController::class, 'edit'

**Description:** List edit

---

### PATCH /profile

**Handler:** ProfileController::class, 'update'

**Description:** Update a specific profile

---

### DELETE /profile

**Handler:** ProfileController::class, 'destroy'

**Description:** Delete a specific profile

---

### GET /p/create

**Handler:** PostsController::class, 'create'

**Description:** List create

---

### POST /p

**Handler:** PostsController::class, 'store'

**Description:** Create a new p

---

### GET /p/{post}

**Handler:** PostsController::class, 'show'

**Description:** Retrieve a specific {post}

---

### POST /users/follow/{user}

**Handler:** FollowController::class, 'store'

**Description:** Create a new {user}

---

### DELETE /users/unfollow/{user}

**Handler:** FollowController::class, 'destroy'

**Description:** Delete a specific {user}

---

### GET /messages

**Handler:** MessageController::class, 'index'

**Description:** List messages

---

### GET /messages/{conversation}

**Handler:** MessageController::class, 'show'

**Description:** Retrieve a specific {conversation}

---

### POST /messages/start

**Handler:** MessageController::class, 'start'

**Description:** Create a new start

---

### POST /messages/send

**Handler:** MessageController::class, 'send'

**Description:** Create a new send

---

### POST /messages/{message}/delivered

**Handler:** MessageController::class, 'markAsDelivered'

**Description:** Create a new delivered

---

### POST /conversations/{conversation}/mark-as-read

**Handler:** MessageController::class, 'markAsRead'

**Description:** Create a new mark-as-read

---

### POST /conversations/{conversation}/mark-messages-as-read

**Handler:** MessageController::class, 'markSpecificMessagesAsRead'

**Description:** Create a new mark-messages-as-read

---

### GET /notifications

**Handler:** NotificationController::class, 'index'

**Description:** List notifications

---

### POST /notifications/read

**Handler:** NotificationController::class, 'markAsRead'

**Description:** Create a new read

---

### GET themes

**Handler:** ThemeController::class, 'index'

**Description:** List themes

---

### GET themes/create

**Handler:** ThemeController::class, 'create'

**Description:** List create

---

### POST themes

**Handler:** ThemeController::class, 'store'

**Description:** Create a new themes

---

### POST themes/{theme}/activate

**Handler:** ThemeController::class, 'activate'

**Description:** Create a new activate

---

### DELETE themes/{theme}

**Handler:** ThemeController::class, 'destroy'

**Description:** Delete a specific {theme}

---

### POST /themes/activate/default

**Handler:** ThemeController::class, 'activateDefault'

**Description:** Create a new default

---

