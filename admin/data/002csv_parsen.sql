USE filmeseite_cohen;


-- genres werden eingefügt 
LOAD DATA LOCAL INFILE 'csv/genres1.csv' INTO TABLE  `genres`
CHARACTER SET 'utf8mb4'
FIELDS TERMINATED BY ','
LINES TERMINATED BY '\n'
IGNORE 1 LINES
(id, genre);


LOAD DATA LOCAL INFILE 'csv/filme.csv' INTO TABLE `filme`
CHARACTER SET 'utf8mb4'
FIELDS TERMINATED BY ','
LINES TERMINATED BY '\n'
IGNORE 1 ROWS;

INSERT INTO `filme`('titel', 'imdbid', 'plot', 'erscheinungs_jahr', 'erscheinungs_datum', 'laufzeit')-- DO!: Hier muss ich das zuende schreiben

LOAD DATA LOCAL INFILE 'csv/filme.csv' INTO TABLE `schauspieler`
 CHARACTER SET 'utf8mb4'
 FIELDS TERMINATED BY ','
 LINES TERMINATED BY '\n'
 IGNORE 1 LINES
 (Actors)
 SET
    schauspieler = REPLACE(Actors, '|', ', ');


LOAD DATA LOCAL INFILE 'csv/filme.csv' INTO TABLE `sprachen`
 CHARACTER SET 'utf8mb4'
 FIELDS TERMINATED BY ','
 LINES TERMINATED BY '\n'
 IGNORE 1 LINES
 (Language)
 SET
    sprache = Language;

LOAD DATA LOCAL INFILE 'csv/filme.csv' INTO TABLE `land`
 CHARACTER SET 'utf8mb4'
 FIELDS TERMINATED BY ','
 LINES TERMINATED BY '\n'
 IGNORE 1 LINES
 (Country)
 SET
    land = Country;

LOAD DATA LOCAL INFILE 'csv/filme.csv' INTO TABLE `director`
 CHARACTER SET 'utf8mb4'
 FIELDS TERMINATED BY ','
 LINES TERMINATED BY '\n'
 IGNORE 1 LINES
 (Director)
    SET
        director = Director;

LOAD DATA LOCAL INFILE 'csv/filme.csv' INTO TABLE `autoren`
 CHARACTER SET 'utf8mb4'
 FIELDS TERMINATED BY ','
 LINES TERMINATED BY '\n'
 IGNORE 1 LINES
 (Writer)
    SET
        autoren = Writer;

LOAD DATA LOCAL INFILE 'csv/filme.csv' INTO TABLE `auszeichnungen`
 CHARACTER SET 'utf8mb4'
 FIELDS TERMINATED BY ','
 LINES TERMINATED BY '\n'
 IGNORE 1 LINES
 (Awards)
    SET
        auszeichnungen = Awards;

LOAD DATA LOCAL INFILE 'csv/filme.csv' INTO TABLE `bewertungen`
 CHARACTER SET 'utf8mb4'
 FIELDS TERMINATED BY ','
 LINES TERMINATED BY '\n'
 IGNORE 1 LINES
 (Ratings)
    SET
        bewertungen = Ratings;
