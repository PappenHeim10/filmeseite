DROP DATABASE IF EXISTS filmsite_cohen;


CREATE DATABASE filmsite_cohen DEFAULT CHARSET=utf8mb4 COLLATE=utf8mb4_general_ci;

USE filmsite_cohen


CREATE TABLE films (
    id INT NOT NULL AUTO_INCREMENT,
    title VARCHAR(50),
)