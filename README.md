
<p align="center"><a href="https://laravel.com" target="_blank"><img src="https://raw.githubusercontent.com/laravel/art/master/logo-lockup/5%20SVG/2%20CMYK/1%20Full%20Color/laravel-logolockup-cmyk-red.svg" width="400" alt="Laravel Logo"></a></p>

<p align="center">
<a href="https://github.com/laravel/framework/actions"><img src="https://github.com/laravel/framework/workflows/tests/badge.svg" alt="Build Status"></a>
<a href="https://packagist.org/packages/laravel/framework"><img src="https://img.shields.io/packagist/dt/laravel/framework" alt="Total Downloads"></a>
<a href="https://packagist.org/packages/laravel/framework"><img src="https://img.shields.io/packagist/v/laravel/framework" alt="Latest Stable Version"></a>
<a href="https://packagist.org/packages/laravel/framework"><img src="https://img.shields.io/packagist/l/laravel/framework" alt="License"></a>
</p>

## About Laravel Email Parsing Project

This Laravel project is designed to parse raw email content from a database and extract plain text body content. The project consists of a command that processes email records every hour and a RESTful API with various endpoints for managing the parsed email records.

### Features

- **Automated Email Parsing:** Runs hourly to convert raw email content to plain text.
- **RESTful API Endpoints:**
  - **Register:** Create a new user.
  - **Login:** Authenticate a user.
  - **Logout:** Log out a user.
  - **Store:** Create a new email record.
  - **Get by ID:** Fetch a single email record by ID.
  - **Update:** Update a single email record by ID.
  - **Delete by ID:** Delete a single email record by ID.

## Database Structure

The database table `successful_emails` is structured as follows:

```sql
CREATE TABLE `successful_emails` (
  `id` int NOT NULL AUTO_INCREMENT,
  `affiliate_id` mediumint NOT NULL,
  `envelope` text NOT NULL,
  `from` varchar(255) NOT NULL,
  `subject` text NOT NULL,
  `dkim` varchar(255) DEFAULT NULL,
  `SPF` varchar(255) DEFAULT NULL,
  `spam_score` float DEFAULT NULL,
  `email` longtext NOT NULL,
  `raw_text` longtext NOT NULL,
  `sender_ip` varchar(50) DEFAULT NULL,
  `to` text NOT NULL,
  `timestamp` int NOT NULL,
  PRIMARY KEY (`id`),
  KEY `affiliate_index` (`affiliate_id`)
);
```

## Setup Instructions

1. **Clone the Repository:**
   ```sh
   git clone https://github.com/gargajay/email_parser.git
   cd email_parser
   ```

2. **Install Dependencies:**
   ```sh
   composer install
   ```

3. **Configure Environment:**
   Copy the `.env.example` file to `.env` and update the database credentials and other necessary settings.
   ```sh
   cp .env.example .env
   ```

4. **Run Migrations:**
   ```sh
   php artisan migrate
   ```

5. **Generate Application Key:**
   ```sh
   php artisan key:generate
   ```

## Running the Application

- **Start the Laravel Development Server:**
  ```sh
  php artisan serve
  ```

- **Run the Email Parsing Command:**
  The command will run every hour by default. You can also run it manually:
  ```sh
  php artisan emails:parse
  The command will run every hour by default. You can also run it manually:
  php artisan schedule:run
  ```

## API Endpoints

### Authentication

- **Register a new user:**
  ```sh
  POST /api/register
  ```

- **Login a user:**
  ```sh
  POST /api/login
  ```

- **Logout a user:**
  ```sh
  POST /api/logout
  ```

### Email Management

- **Store a new email record:**
  ```sh
  POST /api/emails
  ```

- **Get an email record by ID:**
  ```sh
  GET /api/emails/{id}
  ```

- **Update an email record by ID:**
  ```sh
  PUT /api/emails/{id}
  ```

- **Delete an email record by ID:**
  ```sh
  DELETE /api/emails/{id}
  ```



## Contributing

Thank you for considering contributing to the project! The contribution guide can be found in the [Laravel documentation](https://laravel.com/docs/contributions).

## License

This project is open-sourced software licensed under the [MIT license](https://opensource.org/licenses/MIT).

---

This README file includes the appropriate API endpoints as defined in the provided Postman collection.
