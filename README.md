# TaskFlow API

A multi-tenant SaaS task management REST API built with Laravel 13 and Sanctum.

## Tech Stack

- PHP 8.3
- Laravel 13
- MySQL
- Laravel Sanctum (API authentication)
- dedoc/scramble (API documentation)

## Features

- Multi-tenant architecture — complete data isolation between teams
- Sanctum token-based authentication
- FormRequest validation
- Auto-generated API documentation

## Requirements

Before cloning, make sure you have these installed:
- PHP 8.2 or higher
- Composer
- MySQL
- Node.js & npm

## Setup Steps

### 1. Clone the repository
git clone https://github.com/officialamansinha/TaskFlow.git
cd TaskFlow

### 2. Install PHP dependencies
composer install

### 3. Install Node dependencies
npm install

### 4. Create your environment file
cp .env.example .env

### 5. Generate application key
php artisan key:generate

### 6. Configure your database
Open .env and update these 4 lines:
DB_DATABASE=taskflow
DB_USERNAME=root
DB_PASSWORD=

### 7. Run migrations
php artisan migrate

### 8. Start the development server
php artisan serve

## API Documentation
Once the server is running, visit:
http://127.0.0.1:8000/docs/api

## Authentication
All routes except /register and /login require a Bearer token.
1. POST /api/register — create an account
2. POST /api/login — get your token
3. Pass token as: Authorization: Bearer {token}