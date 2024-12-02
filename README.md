# AritmaPlay - C242-PS102
## Table of Contents

1. [What is AritmaPlay?](#AritmaPlay)
2. [Technology](#Technology)
3. [Installation](#Installation)
4. [Database Configuration](#Database-Configuration)
5. [Running the Project](#Running-the-Project)
6. [API Endpoints](#API-Endpoints)


## AritmaPlay

This project aims to create an interactive mathematics learning application for elementary school children in Indonesia, utilizing handwriting recognition technology for solving math problems directly on screens. The application incorporates gamification, allowing children to earn experience points (EXP) for solving problems, with a leaderboard to boost motivation. It also features Gemini's generative AI, providing encouraging feedback to foster a positive learning atmosphere. Focused on basic math operations like addition, subtraction, multiplication, and division, the app offers a fun, engaging, and educational alternative to social media, enhancing math literacy and promoting positive digital behavior among children.

## Technology
The AritmaPlay project is built using the following technologies:

PHP: A widely-used scripting language designed for server-side web development, capable of creating dynamic and interactive web pages.

Laravel: A popular PHP framework with a focus on simplicity and elegance, providing tools for routing, authentication, and database management.

MySQL: A reliable and fast relational database management system commonly used for managing structured data in web applications.

Sanctum: A lightweight Laravel package for API authentication, supporting SPAs, mobile apps, and token-based APIs with simplicity.

Bcrypt: A secure hashing library for passwords, offering built-in salting and resistance to brute-force attacks.

## Installation

To set up the project locally, follow these steps:

Clone the repository:

```bash
git clone https://github.com/AritmaPlay/aritmaplay-backend-api
```

Install the dependencies:

```bash
composer install
npm install
```

Edit a .env file in the root directory and add the following environment variables:

```bash
DB_CONNECTION=mysql
DB_HOST=127.0.0.1
DB_PORT=3306
DB_DATABASE=laravel
DB_USERNAME=root
DB_PASSWORD=
CLOUD_SCHEDULER_TOKEN=your_cloud_scheduler_token
```

## Database Configuration

The project uses MySQL as the database. Ensure that you have MySQL installed and running on your machine.

```bash
php artisan migrate
```

## Running the Project

Start the server:

```bash
php artisan serve
```
The server will run on http://127.0.0.1:8000.

## API Endpoints

Here are the available API endpoints for the AritmaPlay project:

### User Management

Register a new user:

``` bash
POST /api/register
```

Login a user:

``` bash
POST /api/login
```

Logout a user:

``` bash
POST /api/logout
```

Get all user:
``` bash
GET /api/user
```

Get user details:

``` bash
GET /api/user/{id}
```

### Quiz Management

Create a quiz:

```bash
POST /api/quiz
```

Get all quiz:

``` bash
GET /api/quiz
```

Get an quiz by ID:

``` bash
GET /api/quiz/{id}
```

Get an quiz by user ID:

``` bash
GET /api/quiz/user/{id}
```

### Leaderboard

Create a leaderboard:

``` bash
POST /api/leaderboard
```

Get all leaderboard:

``` bash
GET /api/leaderboard
```

Get an leaderboard by ID:

``` bash
GET /api/leaderboard/{id}
```

Get active leaderboard:

``` bash
GET /api/leaderboard-active
```

Change now active leaderboard to inactive and create new active leaderboard
```bash
POST /run-scheduled-task
```

### Leaderboard Entry

Create a leaderboard entry:

``` bash
POST /api/leaderboard-entry
```

Get all leaderboard:

``` bash
GET /api/leaderboard-entry
```

Get an leaderboard by ID:

``` bash
GET /api/leaderboard-entry/{id}
```

