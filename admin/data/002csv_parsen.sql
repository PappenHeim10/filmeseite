USE filmeseite_cohen;

LOAD DATA LOCAL INFILE 'csv/genres1.csv' IGNORE INTO TABLE `genres`
CHARACTER SET 'utf8mb4'
FIELDS TERMINATED BY ','
LINES TERMINATED BY '\n'
IGNORE 1 LINES
(genre); -- Nur die Spalte 'genre' laden



-- filme
LOAD DATA LOCAL INFILE 'csv/filme.csv'
INTO TABLE `filme`
CHARACTER SET 'utf8mb4'
FIELDS TERMINATED BY ',' OPTIONALLY ENCLOSED BY '"'
LINES TERMINATED BY '\n'
IGNORE 1 ROWS
(titel, erscheinungs_jahr, jugendfreigabe, erscheinungs_datum, laufzeit, plot, poster, metascore, @imdb_rating, imdbvotes, imdbid, boxoffice)
SET imdbbewertung = NULLIF(REPLACE(@imdb_rating, ',', '.'), ''); -- Komma durch Punkt ersetzen, und leere Strings zu NULL



-- schauspieler
LOAD DATA LOCAL INFILE 'csv/schauspieler.csv' IGNORE INTO TABLE `schauspieler`
CHARACTER SET 'utf8mb4'
FIELDS TERMINATED BY ','
LINES TERMINATED BY '\n'
IGNORE 1 LINES
(schauspieler);

-- sprachen
LOAD DATA LOCAL INFILE 'csv/sprachen.csv' IGNORE INTO TABLE `sprachen`
CHARACTER SET 'utf8mb4'
FIELDS TERMINATED BY ','
LINES TERMINATED BY '\n'
IGNORE 1 LINES
(sprache);

-- land
LOAD DATA LOCAL INFILE 'csv/laender.csv' IGNORE INTO TABLE `land`
CHARACTER SET 'utf8mb4'
FIELDS TERMINATED BY ','
LINES TERMINATED BY '\n'
IGNORE 1 LINES
(land);

-- director
LOAD DATA LOCAL INFILE 'csv/directoren.csv' IGNORE INTO TABLE `director`
CHARACTER SET 'utf8mb4'
FIELDS TERMINATED BY ','
LINES TERMINATED BY '\n'
IGNORE 1 LINES
(director);

-- autoren
LOAD DATA LOCAL INFILE 'csv/autoren.csv' IGNORE INTO TABLE `autoren`
CHARACTER SET 'utf8mb4'
FIELDS TERMINATED BY ','
LINES TERMINATED BY '\n'
IGNORE 1 LINES
(autor);

-- bewertungen
LOAD DATA LOCAL INFILE 'csv/bewertungen.csv' IGNORE INTO TABLE `bewertungen`
CHARACTER SET 'utf8mb4'
FIELDS TERMINATED BY ','
LINES TERMINATED BY '\n'
IGNORE 1 LINES
(quelle, bewertung);




LOAD DATA LOCAL INFILE 'csv/join_tables/filme_genres.csv' IGNORE INTO TABLE `filme_genres`
CHARACTER SET 'utf8mb4'
FIELDS TERMINATED BY ','
LINES TERMINATED BY '\n'
IGNORE 1 LINES
(@imdbid, @genre)  -- Read into variables
SET film_id = (SELECT id FROM filme WHERE imdbid = @imdbid),
    genre_id = (SELECT id FROM genres WHERE genre = @genre);

LOAD DATA LOCAL INFILE 'csv/join_tables/filme_schauspieler.csv' IGNORE INTO TABLE `filme_schauspieler`
CHARACTER SET 'utf8mb4'
FIELDS TERMINATED BY ','
LINES TERMINATED BY '\n'
IGNORE 1 LINES
(@imdbid, @schauspieler)
SET film_id = (SELECT id FROM filme WHERE imdbid = @imdbid),
      schauspieler_id = (SELECT id FROM schauspieler WHERE schauspieler = @schauspieler);

-- Repeat similar LOAD DATA for other join tables (filme_sprachen, filme_land, film_director, filme_autoren)
-- ... (using the corresponding CSV files and tables) ...
LOAD DATA LOCAL INFILE 'csv/join_tables/filme_sprachen.csv' IGNORE INTO TABLE `filme_sprachen`
CHARACTER SET 'utf8mb4'
FIELDS TERMINATED BY ','
LINES TERMINATED BY '\n'
IGNORE 1 LINES
(@imdbid, @sprache)  -- Read into variables
SET film_id = (SELECT id FROM filme WHERE imdbid = @imdbid),
    sprache_id = (SELECT id FROM sprachen WHERE sprache = @sprache);

LOAD DATA LOCAL INFILE 'csv/join_tables/filme_land.csv' IGNORE INTO TABLE `filme_land`
CHARACTER SET 'utf8mb4'
FIELDS TERMINATED BY ','
LINES TERMINATED BY '\n'
IGNORE 1 LINES
(@imdbid, @land)  -- Read into variables
SET film_id = (SELECT id FROM filme WHERE imdbid = @imdbid),
    land_id = (SELECT id FROM land WHERE land = @land);

LOAD DATA LOCAL INFILE 'csv/join_tables/film_director.csv' IGNORE INTO TABLE `film_director`
CHARACTER SET 'utf8mb4'
FIELDS TERMINATED BY ','
LINES TERMINATED BY '\n'
IGNORE 1 LINES
(@imdbid, @director)  -- Read into variables
SET film_id = (SELECT id FROM filme WHERE imdbid = @imdbid),
    director_id = (SELECT id FROM director WHERE director = @director);

LOAD DATA LOCAL INFILE 'csv/join_tables/filme_autoren.csv' IGNORE INTO TABLE `filme_autoren`
CHARACTER SET 'utf8mb4'
FIELDS TERMINATED BY ','
LINES TERMINATED BY '\n'
IGNORE 1 LINES
(@imdbid, @autor)  -- Read into variables
SET film_id = (SELECT id FROM filme WHERE imdbid = @imdbid),
    autor_id = (SELECT id FROM autoren WHERE autor = @autor);


