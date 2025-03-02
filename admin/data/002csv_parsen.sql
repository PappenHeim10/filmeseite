USE filmeseite_cohen;


-- genres werden eingefügt 
LOAD DATA LOCAL INFILE 'csv/genres1.csv' INTO TABLE  `genres`
CHARACTER SET 'utf8mb4'
FIELDS TERMINATED BY ','
LINES TERMINATED BY '\n'
IGNORE 1 LINES
(id, genre);

LOAD DATA LOCAL INFILE 'csv/filme.csv'
INTO TABLE `filme`
CHARACTER SET 'utf8mb4'
FIELDS TERMINATED BY ','
LINES TERMINATED BY '\n'
IGNORE 1 ROWS
(titel, erscheinungs_jahr, jugendfreigabe, erscheinungs_datum, laufzeit, plot, poster, metascore, imdbbewertung, imdbid, boxoffice);


LOAD DATA LOCAL INFILE 'csv/schauspieler.csv' INTO TABLE `schauspieler`
 CHARACTER SET 'utf8mb4'
 FIELDS TERMINATED BY ','
 LINES TERMINATED BY '\n'
 IGNORE 1 LINES
 (schauspieler);


LOAD DATA LOCAL INFILE 'csv/sprachen.csv' INTO TABLE `sprachen`
 CHARACTER SET 'utf8mb4'
 FIELDS TERMINATED BY ','
 LINES TERMINATED BY '\n'
 IGNORE 1 LINES
(sprache);


LOAD DATA LOCAL INFILE 'csv/laender.csv' INTO TABLE `land`
CHARACTER SET 'utf8mb4'
FIELDS TERMINATED BY ','
    -- ENCLOSED BY '"'  <-- REMOVE THIS LINE
    -- OPTIONALLY ENCLOSED BY '"' <--  OR USE THIS (MySQL 8.0.22+)
LINES TERMINATED BY '\n'
IGNORE 1 LINES
(land);

LOAD DATA LOCAL INFILE 'csv/directoren.csv' INTO TABLE `director`
 CHARACTER SET 'utf8mb4'
 FIELDS TERMINATED BY ','
 LINES TERMINATED BY '\n'
 IGNORE 1 LINES
 (director);

LOAD DATA LOCAL INFILE 'csv/autoren.csv' INTO TABLE `autoren`
 CHARACTER SET 'utf8mb4'
 FIELDS TERMINATED BY ','
 LINES TERMINATED BY '\n'
 IGNORE 1 LINES
 (autor);
