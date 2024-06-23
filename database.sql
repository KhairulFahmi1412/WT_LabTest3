-- Create database
CREATE DATABASE IF NOT EXISTS webtech_labtest;

-- Select the database
USE webtech_labtest;

-- Create users table
CREATE TABLE IF NOT EXISTS users (
    id INT AUTO_INCREMENT PRIMARY KEY,
    name VARCHAR(255) NOT NULL,
    email VARCHAR(255) NOT NULL
);

-- Ensure the email field is unique
ALTER TABLE users
ADD CONSTRAINT unique_email UNIQUE (email);
