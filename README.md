## Get Started

This guide will walk you through the steps needed to get this project up and running on your local machine.

### Prerequisites

Before you begin, ensure you have the following installed:

- [Docker](https://www.docker.com/products/docker-desktop/)
- Docker Compose
- [Postman](https://www.postman.com/downloads/) (For testing)

### Building the Docker Environment

Build and start the containers:

```
docker-compose up -d --build
```

### Installing Dependencies

```
docker-compose exec app sh
composer install
```

### Database Setup

Set up the database:

```
bin/cake migrations migrate
```

Seed the database (For testing below)

```
bin/cake migrations seed
```

The sample users and articles data would be seeded.

### Accessing the Application

The application should now be accessible at http://localhost:34251

## How to check

### Authentication

- Open your postman tool and use this URL:

  `POST http://localhost:34251/login`
- In Headers tab, add this config

  ```
  Key: Accept
  Value: application/json
  ```
  <img width="963" alt="image" src="https://github.com/vietnezo/coding-test-php/assets/15098439/ce6733bd-f010-42d5-b31b-be0512ffd9a8">

- In Body tab, choose `raw` option, and at the right dropdown, change `Text` to `JSON` and use this data for login.

  ```
  {
    "email": "user1@example.com",
    "password": "password1"
  }
  ```
- After press `Send` button, if login is successfully, you will receive access token like this.
<img width="960" alt="image" src="https://github.com/vietnezo/coding-test-php/assets/15098439/9274aa03-9e61-4aa3-8718-b323da788691">

- In case login is failed, the response would be like this.
<img width="956" alt="image" src="https://github.com/vietnezo/coding-test-php/assets/15098439/f58d160a-4901-41f0-8ad2-946ce26b5b31">


### Article Management

- Retrieve All Articles
  - It can be accesssed by all users without login.
  - Access this URL in Posman

  `GET http://localhost:34251/articles.json`
  - In Headers tab, add this config
    ```
    Key: Accept
    Value: application/json
    ```
  - You will retrieve all article by pages (default page 1) like this.
  <img width="955" alt="image" src="https://github.com/vietnezo/coding-test-php/assets/15098439/c891cdb6-8b9c-4ed3-a3c0-ba4908051a92">

  - For next page, use params `page={page_number}` (Ex: page_number is 2,3,4,...) like this.
  <img width="956" alt="image" src="https://github.com/vietnezo/coding-test-php/assets/15098439/ee846a34-6038-4f06-8f5a-f6185c27a154">

- Retrieve a Single Article
  - It can be accesssed by all users without login.
  - Access this URL in Posman
    
  `GET http://localhost:34251/articles/{article_id}.json`

    (Ex: article_id is 1,2,3,...)
  - In Headers tab, add this config
    ```
    Key: Accept
    Value: application/json
    ```
  - You will retrieve a single article like this.
  <img width="959" alt="image" src="https://github.com/vietnezo/coding-test-php/assets/15098439/5ec009d7-a218-4da9-a9c9-84d61a91dfde">

- Create an Article
  - It can only be used by authenticated users.
  - Access this URL in Posman
  
    `POST http://localhost:34251/articles.json`
  - After you logged in, you would receive an access token in above step.
  - In Authorization tab, chooose Type: Bearer Token and fill the Token with your token received after logged in.
  <img width="957" alt="image" src="https://github.com/vietnezo/coding-test-php/assets/15098439/38c4a695-3849-4d61-aab1-4acd7286b62c">

  - In Headers tab, add this config
    ```
    Key: Accept
    Value: application/json
    ```
  - In Body tab, choose `raw` option, at the right dropdown, change `Text` to `JSON` and fill data of article.
    Title and body of an article is required.
      ```
      {
        "title": "Your title here",
        "body": "Your body here"
      }
      ```
  - After press `Send` button, article would be created like this.
  <img width="955" alt="image" src="https://github.com/vietnezo/coding-test-php/assets/15098439/298bd565-f9ce-4fad-b19f-f1b3e06a6ce2">

- Update an Article
  - It can only be used by authenticated article writer users.
  - Access this URL in Posman
  
  `PUT http://localhost:34251/articles/{article_id}.json`

    (Ex: article_id is 1,2,3,...)

  - In Authorization tab, chooose Type: Bearer Token and fill the Token with your token received after logged in.
  - In Headers tab, add this config
    ```
    Key: Accept
    Value: application/json
    ```
  - In Body tab, choose `raw` option, at the right dropdown, change `Text` to `JSON` and fill data of article which you want to update.
      ```
      {
        "title": "Your title here",
        "body": "Your body here"
      }
      ```
  - After press `Send` button, article would be updated like this.
  <img width="957" alt="image" src="https://github.com/vietnezo/coding-test-php/assets/15098439/b608b434-3958-4f76-afcb-605efa66c9b9">
  - In case you are not the writer of this article, the response would like this.
  <img width="953" alt="image" src="https://github.com/vietnezo/coding-test-php/assets/15098439/dd2aca69-0d9e-40ac-adfe-2ab465bf079c">

- Delete an Article
  - It can only be used by authenticated article writer users.
  - Access this URL in Posman
  
  `DELETE http://localhost:34251/articles/{article_id}.json`

    (Ex: article_id is 1,2,3,...)
  - In Headers tab, add this config
    ```
    Key: Accept
    Value: application/json
    ```
  - In Authorization tab, chooose Type: Bearer Token and fill the Token with your token received after logged in.
  - After press `Send` button, article would be deleted like this.
  <img width="957" alt="image" src="https://github.com/vietnezo/coding-test-php/assets/15098439/f2757113-0eaf-4c07-9302-30ff0c168f14">

  - In case you are not the writer of this article, the response would be like this.
  <img width="955" alt="image" src="https://github.com/vietnezo/coding-test-php/assets/15098439/6cb64ea6-8b1b-446a-8056-62b4a539b4a0">

### Like Feature

- It can only be used by authenticated users.
- Access this URL in Posman

  `POST http://localhost:34251/article-likes.json`
- In Headers tab, add this config
  ```
  Key: Accept
  Value: application/json
  ```
- In Body tab, choose `raw` option, at the right dropdown, change `Text` to `JSON` and fill article id which you like.
  ```
  {
    "article_id": "7"
  }
  ```
- After press `Send` button, article would be deleted like this.
<img width="950" alt="image" src="https://github.com/vietnezo/coding-test-php/assets/15098439/ed08b2bc-9973-424e-867e-049a8e26ed8e">

- In case you alredy liked this article, the response would be like this.
<img width="956" alt="image" src="https://github.com/vietnezo/coding-test-php/assets/15098439/6aafd0d1-15fa-44b1-bab3-175e16940423">

