# Link Shortener With Analytics

URL shortener with click analytics built with PHP 8, MySQL, and Bootstrap 5.

## Screenshots

<img width="1918" height="944" alt="image" src="https://github.com/user-attachments/assets/b42680d8-51bf-460e-8e2f-8e1932027a23" />

<img width="1918" height="940" alt="image" src="https://github.com/user-attachments/assets/54427118-f5b9-4613-bb45-6f2fc719235b" />

<img width="1919" height="944" alt="image" src="https://github.com/user-attachments/assets/61b33d21-ae11-4a7c-bdcc-edaf9591cd7e" />

<img width="1919" height="939" alt="image" src="https://github.com/user-attachments/assets/74673bd9-3742-4b56-bc88-26d64cf8182d" />

## Features

- Shorten any URL with a unique code
- Click analytics: IP address, User-Agent, timestamp
- User registration and authentication (Argon2ID)
- CSRF protection on all forms
- Admin panel for managing all links
- Responsive design (Bootstrap 5)
- Docker setup

## Tech Stack

- PHP 8.5, PDO
- MySQL 8.0
- Bootstrap 5
- Docker, Apache

## Getting Started

### 1. Clone

```bash
git clone https://github.com/LankoDm/Link_Shortener_With_Analytics.git
cd Link_Shortener_With_Analytics
```

### 2. Configure

```bash
cp .env.example .env
```

Edit `.env` and set your database credentials.

### 3. Run

```bash
docker compose up -d
```

Wait about 30 seconds for MySQL to start on first run.

### 4. Create tables

```bash
docker exec url_shortener_web php /var/www/html/config/setup_database_table.php
```

### 5. Add favicon

Place a `favicon.ico` file into the `public/` directory.

### 6. Open

```
http://localhost:8080
```

## Environment Variables

- `MYSQL_ROOT_PASSWORD` — MySQL root password
- `MYSQL_DATABASE` — database name (default: shortener_db)
- `MYSQL_USER` — database user
- `MYSQL_PASSWORD` — database user password
