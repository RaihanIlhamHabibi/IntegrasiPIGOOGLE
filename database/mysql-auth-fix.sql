-- MySQL authentication fix for Laravel (auth_gssapi_client issue)
-- Run this as a MySQL administrator user.

CREATE DATABASE IF NOT EXISTS `laravel_bda` DEFAULT CHARACTER SET utf8mb4 COLLATE utf8mb4_unicode_ci;

-- Option 1: update the existing user to native password auth
ALTER USER 'root'@'localhost' IDENTIFIED WITH mysql_native_password BY '';

-- Option 2: create a dedicated Laravel user
CREATE USER IF NOT EXISTS 'laravel'@'127.0.0.1' IDENTIFIED WITH mysql_native_password BY 'secret_password';
GRANT ALL PRIVILEGES ON `laravel_bda`.* TO 'laravel'@'127.0.0.1';

FLUSH PRIVILEGES;
