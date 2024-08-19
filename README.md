# Symfony 7 Project

## Introduction

This project is built using Symfony 7. Follow the instructions below to set up and run the application on your local machine.

## Prerequisites

Before you start, make sure you have the following installed:

- **PHP 8.2** or higher
- **Composer** (latest version)
- **Symfony CLI** (optional, but recommended)
- **Database** (e.g., MySQL, PostgreSQL) if your application requires one

## Installation

### 1. Clone the Repository

Clone the project to download its contents:

```bash
git clone  https://github.com/rose-me/symfony7-crud.git
cd your-repository-name
```

### 2. Install Dependencies

Use Composer to install the necessary dependencies:

_Comment or Uncomment repositories.packages.url in composer.json_
In some cases, due to country-specific restrictions, you might need to modify the composer.json

```bash
composer install
```

### 3. Set Up Environment Variables

Adjust the database connection and other environment-specific settings in the .env file as needed.

Example:

`DATABASE_URL="mysql://db_user:db_password@127.0.0.1:3306/db_name?serverVersion=8.0"`

### 4. Create the Database

Use Composer to install the necessary dependencies:

```bash
php bin/console doctrine:database:create
php bin/console doctrine:migrations:migrate

```

### 5. Run the Frontend dependencies

If your project uses frontend assets (JavaScript, CSS, etc.), you'll need to install the dependencies listed in package.json. First, make sure you install `Node.js`:

Using npm:
```bash
npm install
```

Using Yarn:
```bash
yarn install
```

### 6. Run Scripts to build the Frontend assests

_Development Build_:

Using npm: 
```bash
npm run dev
```

Using Yarn:
```bash
yarn dev
```
_Production Build_:

Using npm: 
```bash
npm run build
```

Using Yarn:
```bash
yarn build
```

### 7. Run the Symfony Local Server

Start the Symfony local web server:

```bash
symfony server:start
```

Alternatively, you can use PHP's built-in server:

```bash
php -S localhost:8000 -t public
```

### 8. Access the Application

Open your web browser and go to `http://localhost:8000` to view the application.
