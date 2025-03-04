DROP DATABASE IF EXISTS filmeseite_cohen;
CREATE DATABASE filmeseite_cohen DEFAULT CHARSET=utf8mb4 COLLATE=utf8mb4_general_ci;
USE filmeseite_cohen;

-- Tabellen erstellen (dein vorhandenes Schema)
CREATE TABLE filme (
    id INT AUTO_INCREMENT PRIMARY KEY,
    titel VARCHAR(255) NOT NULL,
    imdbid VARCHAR(255) NOT NULL UNIQUE,
    plot TEXT,
    erscheinungs_jahr YEAR,
    erscheinungs_datum DATE,
    laufzeit INT(20),
    jugendfreigabe VARCHAR(255),
    metascore INT(20) ,
    imdbbewertung DECIMAL (6,2),
    imdbvotes INT(20),
    boxoffice VARCHAR(255),
    poster VARCHAR(400)
);

CREATE TABLE genres(
    id INT PRIMARY KEY,
    genre VARCHAR(255) NOT NULL UNIQUE
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
    schauspieler VARCHAR(255) UNIQUE
);

CREATE TABLE filme_schauspieler(
    id INT AUTO_INCREMENT PRIMARY KEY,
    film_id INT NOT NULL,
    schauspieler_id INT NOT NULL,
    FOREIGN KEY (film_id) REFERENCES filme(id),
    FOREIGN KEY (schauspieler_id) REFERENCES schauspieler(id) ON DELETE CASCADE
);

CREATE TABLE sprachen(
    id INT AUTO_INCREMENT PRIMARY KEY,
    sprache VARCHAR(255) NOT NULL UNIQUE
);

CREATE TABLE filme_sprachen(
    id INT AUTO_INCREMENT PRIMARY KEY,
    film_id INT NOT NULL,
    sprache_id INT NOT NULL,
    FOREIGN KEY (film_id) REFERENCES filme(id) ON DELETE CASCADE,
    FOREIGN KEY (sprache_id) REFERENCES sprachen(id) ON DELETE CASCADE
);

CREATE TABLE director(
    id INT AUTO_INCREMENT PRIMARY KEY,
    director VARCHAR(255) NOT NULL UNIQUE
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
    land VARCHAR(255) NOT NULL UNIQUE  -- UNIQUE hinzugefügt
);

CREATE TABLE filme_land(
    id INT AUTO_INCREMENT PRIMARY KEY,
    film_id INT NOT NULL,
    land_id INT NOT NULL,
    FOREIGN KEY (film_id) REFERENCES filme(id) ON DELETE CASCADE,
    FOREIGN KEY (land_id) REFERENCES land(id) ON DELETE CASCADE
);



CREATE TABLE autoren(
    id INT AUTO_INCREMENT PRIMARY KEY,
    autor VARCHAR(255) NOT NULL UNIQUE
);

CREATE TABLE filme_autoren(
    id INT AUTO_INCREMENT PRIMARY KEY,
    film_id INT NOT NULL,
    autor_id INT NOT NULL,
    FOREIGN KEY (film_id) REFERENCES filme(id),
    FOREIGN KEY (autor_id) REFERENCES autoren(id)
);


CREATE TABLE bewertungen(
   id INT AUTO_INCREMENT PRIMARY KEY,
    quelle VARCHAR(255) NOT NULL,
    bewertung VARCHAR(255) NOT NULL -- Geändert zu VARCHAR, da auch Textwerte wie "6.5/10" vorkommen.
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
    benutzername VARCHAR(255) NOT NULL UNIQUE,
    passwort VARCHAR(255) NOT NULL
);

INSERT INTO admin (benutzername, passwort) VALUES ('admin', 'admin');
