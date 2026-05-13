# CampusEdgePro Laravel App

This project has been converted into a Laravel application and is configured to use MySQL.

## MySQL Setup

Update the database credentials in `.env` if your MySQL server uses a different host, port, username, password, or database name:

```env
DB_CONNECTION=mysql
DB_HOST=127.0.0.1
DB_PORT=3306
DB_DATABASE=campusedge
DB_USERNAME=root
DB_PASSWORD=
```

Create the MySQL database before running migrations:

```sql
CREATE DATABASE campusedge CHARACTER SET utf8mb4 COLLATE utf8mb4_unicode_ci;
```

Then run:

```bash
php artisan config:clear
php artisan migrate
```

## Run The Project

```bash
php artisan serve --host=127.0.0.1 --port=8000
```

Open [http://127.0.0.1:8000](http://127.0.0.1:8000).
