[![codecov](https://codecov.io/github/Krobes/gameaffinity/graph/badge.svg?token=764M1XO82Z)](https://codecov.io/github/Krobes/gameaffinity)
![PHP](https://img.shields.io/badge/PHP-8.3-blue)
![Symfony](https://img.shields.io/badge/Symfony-6.4.7-black)
![Docker](https://img.shields.io/badge/Docker-26.1.3-lightblue)

# Project Gameaffinity

<div style="text-align: center; padding-bottom: 5px;">
    <img src="images/logo.png" alt="Gameaffinity_Logo" width="200"/>
</div>

Gameaffinity is a web application where you can browse through our extensive
database of over 2000 games and perform advanced searches by developer,
genre, platform, and more. Additionally, enjoy the benefits of registering,
such as creating private lists to organize your favorite games or those you
wish to play, or public lists to share your tastes and recommendations with
others. Other advantages of registering include rating games and accessing our
chatbot section, where our AI can assist you with game recommendations or
opinions.

## Table of Contents

- [Versions](#versions)
- [Prerequisites](#prerequisites)
- [Installation](#installation)
- [Deployment](#deployment)
- [Usage](#usage)
- [Testing](#testing)
- [Contributing](#contributing)
- [Sources of Information](#sources-of-information)
- [Contact](#contact)

## Versions

### Symfony

I chose version 6.4 because it is the latest version in the 6.x series and will be maintained until almost 2027, with
additional time for security fixes.

### Docker

Initially, I started deploying my containers using Docker version 23. However, after experiencing a security breach
through the PHP container and port 9000, I upgraded to version 26.1.3 and close ports, which resolved the issues.

## Prerequisites

Before getting started, make sure you have the following prerequisites installed:

- Docker

Additionally, to run dockers we use next ports:

- Nginx: 81
- MySQL: 4306

If any of these ports are not available, follow these steps:

1. Open the `docker-compose.yml` file in your preferred text editor.

2. Locate the section where the nginx service is defined.

3. Modify the port mapping so that the nginx container listens on an available port instead of the default port. For
   example:
   ```yaml
   services:
       nginx:
         container_name: nginx
         image: nginx:stable-alpine
         ports:
             - '8080:80'

## Installation

Clone the repository to your local machine:

```sh
git clone https://github.com/Krobes/gameaffinity.git
cd gameaffinity
docker-compose up -d

````

## Deployment

### Env file

```env
APP_ENV=prod
APP_SECRET=your_app_secret_here

# Example configuration for SQLite
# DATABASE_URL="sqlite:///%kernel.project_dir%/var/data.db"

# Example configuration for MySQL
DATABASE_URL="mysql://user:password@127.0.0.1:3306/database_name?serverVersion=8.0&charset=utf8mb4"

# Example configuration for MariaDB
# DATABASE_URL="mysql://user:password@127.0.0.1:3306/database_name?serverVersion=10.5&charset=utf8mb4"

# Example configuration for PostgreSQL
# DATABASE_URL="postgresql://user:password@127.0.0.1:5432/database_name?serverVersion=13&charset=utf8"

MAILER_DSN=smtp://username:password@smtp.example.com:587

````

### Composer Install

It's important to have the .env configured before running the following command, as one of the bundles used: Dotenv,
requires the existence of the file for its installation.

#### If you have installed composer in your system do it:

```sh
composer install
```

#### If not:

```sh
docker exec -it *name_php_container* /bin/bash
composer install
```

### Dump script dump.sql

Using database connection software like DBeaver or Navicat, or by directly dumping it into the MySQL container, we will
inject the provided script located at the project path app/test/data.

### API Chatbot

In the path app/public/js/chatbot/api.js you will need to add a valid key for the ChatGPT Best Price API from RapidApi.

URL: https://rapidapi.com/truongvuhung102/api/chatgpt-best-price

```js
const API_KEY_CHATGPT = "";
```

If you have followed these steps, you should now be able to access localhost:8081 and see Gameaffinity deployed!

## Usage

### Create a List

Creating a list allows you to organize your favorite games or keep track of titles you plan to play. You can create
private lists for your own use or public lists to share with other users.

![Create_A_List Demo](images/create_list.gif)

### Add a game to the list

Once you've created a list, you can easily add games to it. Simply navigate to the list you want to update, click on
the "Add Game" button, and then enter the title of the game you want to add. You can also add a brief description or any
other relevant information about the game.

![Add_A_Game_To_List Demo](images/add_game.gif)

### Rate games

Rating games allows you to share your opinion with other users and help them discover new titles. After playing a game,
you can assign it a rating based on your experience. This helps to create a community-driven ranking system and provides
valuable feedback to other users.

![Create_A_List Demo](images/rating.gif)

### Chatbot

The Gameaffinity website offers a variety of features and services for gaming enthusiasts. One of the key features is
the **Chatbot** section, where users can interact with a chatbot to ask questions about video games, get
recommendations, and more. The chatbot is designed to provide quick and helpful responses to enhance the user
experience.

Here is a demonstration video of the Chatbot in action:

![Chatbot Demo](images/chatbot.gif)

## Testing

I have used PHPUnit to perform unit testing of my application, covering a large part of the
Entities, Repositories, and Services of the project.

![Testing](images/coverage.png)

## Contributing

If you'd like to contribute to the project, please open an issue or send a pull request.

## Contact

I'm glad to hear from you! If you have any questions, comments, or suggestions about this project, feel free to contact
me.

You can find me at:

- Mail: [rafa_lara@hotmail.es](mailto:tu_correo_electrónico@example.com)
- LinkedIn: [Rafael Bonilla Lara](https://www.linkedin.com/in/rafael-bonilla-lara-4521a32aa/)

## Sources of Information

Sources of information and code of other users:

### Documentation

- [Twilio - Get started with Docker and Symfony](https://www.twilio.com/en-us/blog/get-started-docker-symfony)
- [PHP Unit - Official Documentation](https://docs.phpunit.de/en/9.6/fixtures.html)
- [PHP - Official Documentation](https://www.php.net/)
- [Symfony - Official Documentation](https://symfony.com/)
- [Docker - Official Documentation](https://www.docker.com/)
- [W3Schools](https://www.w3schools.com/)

### Codepen Code Used

- [Codepen 1](https://codepen.io/chrisspb/pen/QWYLbaN)
- [Codepen 2](https://codepen.io/syrizaldev/pen/xxxmdzw)
- [Codepen 3](https://codepen.io/corbpie/pen/LYNwGdE)

### Blogs and Articles

- [Dev - Creating and Running an Angular Application in a Docker Container](https://dev.to/rodrigokamada/creating-and-running-an-angular-application-in-a-docker-container-40mk)
- [Sysdig - Zoom Into Kinsing Kdevtmpfsi](https://sysdig.com/blog/zoom-into-kinsing-kdevtmpfsi/)

### Games Database

- [IGDB](https://www.igdb.com/)

### Images

- [Freepik](https://www.freepik.es/)



