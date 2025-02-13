DROP DATABASE IF EXISTS filmeseite_cohen;

CREATE DATABASE filmeseite_cohen DEFAULT CHARSET=utf8mb4 COLLATE=utf8mb4_general_ci;

USE filmeseite_cohen;

CREATE TABLE filme (
    id INT AUTO_INCREMENT PRIMARY KEY,
    titel VARCHAR(255) NOT NULL,
    imdbid VARCHAR(255) NOT NULL UNIQUE,
    plot TEXT NOT NULL,
    erscheinungs_jahr YEAR,
    erscheinungs_datum DATE,
    laufzeit INT(20),
    jugendfreigabe VARCHAR(255),
    metascore INT(20) ,
    imdbbewertung DECIMAL (6,2),
    imdbvotes INT(20),
    boxoffice INT(20),
    poster VARCHAR(400)
    vollstaendig BOOLEAN DEFAULT;
);

CREATE TABLE genres(
    id INT PRIMARY KEY,
    genre VARCHAR(255) NOT NULL
);

CREATE TABLE filme_genres(
    id INT AUTO_INCREMENT PRIMARY KEY,
    film_id INT NOT NULL,
    genre_id INT NOT NULL,
    FOREIGN KEY (film_id) REFERENCES filme(id),
    FOREIGN KEY (genre_id) REFERENCES genres(id)
);

CREATE TABLE schauspieler(
    id INT AUTO_INCREMENT PRIMARY KEY,
    vorname VARCHAR(255) NOT NULL,
    nachname VARCHAR(255) NOT NULL,
    geburtsdatum DATE,
    geburtsort VARCHAR(255),
    biografie TEXT
);

CREATE TABLE filme_schauspieler(
    id INT AUTO_INCREMENT PRIMARY KEY,
    film_id INT NOT NULL,
    schauspieler_id INT NOT NULL,
    FOREIGN KEY (film_id) REFERENCES filme(id),
    FOREIGN KEY (schauspieler_id) REFERENCES schauspieler(id)
);

CREATE TABLE sprachen(
    id INT AUTO_INCREMENT PRIMARY KEY,
    sprache VARCHAR(255) NOT NULL
);

CREATE TABLE filme_sprachen(
    id INT AUTO_INCREMENT PRIMARY KEY,
    film_id INT NOT NULL,
    sprache_id INT NOT NULL,
    FOREIGN KEY (film_id) REFERENCES filme(id),
    FOREIGN KEY (sprache_id) REFERENCES sprachen(id)
);

CREATE TABLE director(
    id INT AUTO_INCREMENT PRIMARY KEY,
    vorname VARCHAR(255) NOT NULL,
    nachname VARCHAR(255) NOT NULL,
    geburtsdatum DATE,
    geburtsort VARCHAR(255),
    biografie TEXT
);

CREATE TABLE film_director(
    id INT AUTO_INCREMENT PRIMARY KEY,
    film_id INT NOT NULL,
    director_id INT NOT NULL,
    FOREIGN KEY (film_id) REFERENCES filme(id),
    FOREIGN KEY (director_id) REFERENCES director(id)
);

CREATE TABLE land(
    id INT AUTO_INCREMENT PRIMARY KEY,
    land VARCHAR(255) NOT NULL
);

CREATE TABLE filme_land(
    id INT AUTO_INCREMENT PRIMARY KEY,
    film_id INT NOT NULL,
    land_id INT NOT NULL,
    FOREIGN KEY (film_id) REFERENCES filme(id),
    FOREIGN KEY (land_id) REFERENCES land(id)
);

CREATE TABLE auszeichnungen(
    id INT AUTO_INCREMENT PRIMARY KEY,
    preis VARCHAR(255) NOT NULL
);

CREATE TABLE filme_auszeichnungen(
    id INT AUTO_INCREMENT PRIMARY KEY,
    film_id INT NOT NULL,
    preis_id INT NOT NULL,
    FOREIGN KEY (film_id) REFERENCES filme(id),
    FOREIGN KEY (preis_id) REFERENCES auszeichnungen(id)
);

CREATE TABLE autoren(
    id INT AUTO_INCREMENT PRIMARY KEY,
    vorname VARCHAR(255) NOT NULL,
    nachname VARCHAR(255) NOT NULL,
    geburtsdatum DATE,
    geburtsort VARCHAR(255),
    biografie TEXT
);

CREATE TABLE filme_autoren(
    id INT AUTO_INCREMENT PRIMARY KEY,
    film_id INT NOT NULL,
    autoren_id INT NOT NULL,
    FOREIGN KEY (film_id) REFERENCES filme(id),
    FOREIGN KEY (autoren_id) REFERENCES autoren(id)
);

CREATE TABLE bewertungen(
    id INT AUTO_INCREMENT PRIMARY KEY,
    quelle VARCHAR(255) NOT NULL,
    bewertung DECIMAL(2,1) NOT NULL,
    kommentar TEXT
);

CREATE TABLE filme_bewertungen(
    id INT AUTO_INCREMENT PRIMARY KEY,
    film_id INT NOT NULL,
    bewertung_id INT NOT NULL,
    FOREIGN KEY (film_id) REFERENCES filme(id),
    FOREIGN KEY (bewertung_id) REFERENCES bewertungen(id)
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