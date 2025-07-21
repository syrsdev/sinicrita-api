
# sinicrita API

Ini merupakan backend service(api) untuk platform sinicrita yang dibuat dan dikembangkan menggunakan Laravel 12 dan didukung dengan ekosistem laravel seperti: sanctum dan reverb

## Tech Stack

**Framework & Library:** Laravel 12 (sanctum, reverb)

**Database:** MySQL

## Features

- Authentication (Sanctum cookies based)
- Post API
- Direct Message API (realtime)
- Call (realtime)
- User Management (Admin)
- etc

## 🔗 Frontend Link

<https://github.com/syrsdev/sinicrita-web>

## Installation

Clone Project

```bash
  git clone https://github.com/syrsdev/sinicrita-api
  cd sinicrita-api
```

Open & Install sinicrita-api

```bash
  npm install
  &
  composer install
```

Copy .env file

```bash
   cp .env.example .env 
```

Database & App setup

```bash
   php artisan migrate
   or with seeder
   php artisan migrate:fresh --seed
    
   php artisan key:generate
   php artisan reverb:generate-app-key
```

## Run Locally

Terminal 1

```bash
  php artisan serve --host localhost
```

Terminal 2

```bash
  php artisan reverb:start
```

Terminal 3

```bash
  php artisan queue:work
```
