# healthqo

healthqo is a medical community and have much more futures

# Installing

### 1. Clone the project<br>

> `git clone https://github.com/abdelrahman403/healthqo-backend`

<br/>

### 2. Cd into the project

> `cd healthqo-backend`

<br/>

### 3. Install dependencies

> `composer install`<br> >`npm install`

<br/>

### 4. Create your copy of .env

> `cp .env.example .env`

<br/>

### 5. Generate your encryption key

> `php artisan key:generate`<br> > `php artisan passport:install`

<br/>

### 6. Create and empty database

i am using **xampp phpmyadmin** feel free to use **mysql pro**

<br/>

### 7. Update your copy of .env

-   DB_DATABASE = $your database name$
-   DB_USERNAME = $your username$
-   DB_PASSWORD = $your password$

<br/>

### 8. Migrate your database

> `php artisan migrate`

<br/>

### 9. [*optional*] Seed the database

(adding some dummy data just for testing)<br/>
(ready for posts only)

> `php artisan db:seed`

<br/>

### 10. Run the app

> `php artisan serve`

<br/>

## Done !

<br/>
<br/>

# Routes

## >> USERS <<

**Authanticate users**

```
GET >> /api/user/auth
PRAMS >>
    username = {username} OR email = {email}
    password = {password}
-------------------------------------
help: request a new access token for the user
```

**search for a user**

```
GET >> /api/users/find/{query}
    example: /api/users/find/john
    help: gets all users thier name starts with john
```

**get authanticated user**

```
GET >> /api/user
HEADER >>
    Autharization = Bearer {access token}
----------------------------------
help: gets the currently authanticated user
```

**creat a new user**

```
POST >> /api/user
BODY >>
    {
        "name": "{full name}",
        "username": "{username}",
        "email": "{email}",
        "password": "{password}"
    }
---------------------------------
help: create a new user and a new access token
```

**update a user**

```
PUT >> /api/user/{userid}
BODY >>
    {
        "name": "{full name}",
        "username": "{username}",
        "password": "{password}"
    }
---------------------------------
example: /api/user/1
help: update a user
```

**delete a user**

```
Delete >> /api/user/{userid}

example: /api/user/1
help: delete a user
```

<br>

## >> POSTS <<

**show all posts**

```
GET >> /api/posts

help: get all posts with pagination system
```

**get a post**

```
GET >> /api/post/{postid}

example: /api/post/1
help: get a specific post
```

**create a new post**

```
POST >> /api/post

help: creates a new post
```

**update a post**

```
PUT >> /api/post/{postid}

example: /api/post/1
help: update a specific post
```

**delete a post**

```
DELETE >> /api/post/{postid}

example: /api/post/1
help: delete a specific post
```
