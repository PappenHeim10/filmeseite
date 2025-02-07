DROP DATABASE IF EXISTS filmesite_cohen;


CREATE DATABASE filmesite_cohen DEFAULT CHARSET=utf8mb4 COLLATE=utf8mb4_general_ci;

USE filmesite_cohen;

CREATE TABLE filme (
    id INT AUTO_INCREMENT PRIMARY KEY,
    titel VARCHAR(255) NOT NULL UNIQUE,
    beschreibung TEXT NOT NULL,
    erscheinungs_jahr YEAR NOT NULL,
    erscheinungs_datum DATE NOT NULL,
    director VARCHAR(255) NOT NULL,
    genre VARCHAR(255) NOT NULL,
    land VARCHAR (255) NOT NULL,
    cast VARCHAR (255) ,
    auszeichnungen  VARCHAR(255),
    autor VARCHAR(255),
    laufzeit INT(20) NOT NULL,
    jugendfreigabe VARCHAR(255),
    metascore INT(20) ,
    imdbbewertung DECIMAL (6,2),
    sprache VARCHAR(255),
    imdbvotes INT(20),
    art VARCHAR(255),
    boxoffice INT(20) ,
    website VARCHAR(255)
);

CREATE TABLE genres(
    id INT PRIMARY KEY,
    genre VARCHAR(255) NOT NULL
);


CREATE TABLE users(
    id INT AUTO_INCREMENT PRIMARY KEY,
    anrede VARCHAR(255) NOT NULL,
    vorname VARCHAR(255) NOT NULL,
    nachname VARCHAR(255) NOT NULL,
    benutzername VARCHAR(255) NOT NULL UNIQUE,
    email VARCHAR(255) NOT NULL UNIQUE,
    passwort VARCHAR(255) NOT NULL
);


CREATE TABLE admin(
    id INT AUTO_INCREMENT PRIMARY KEY,
    username VARCHAR(255) NOT NULL UNIQUE,
    passwort VARCHAR(255) NOT NULL
);