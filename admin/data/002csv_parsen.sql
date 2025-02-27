USE filmeseite_cohen;

-- 1. Genres einfügen (mit Duplikatsprüfung)
LOAD DATA LOCAL INFILE 'csv/genres1.csv' IGNORE INTO TABLE `genres`
CHARACTER SET 'utf8mb4'
FIELDS TERMINATED BY ','
LINES TERMINATED BY '\n'
IGNORE 1 LINES
(id, genre);

-- 2. Filme einfügen (Grunddaten)
LOAD DATA LOCAL INFILE 'csv/filme.csv' INTO TABLE `filme`
CHARACTER SET 'utf8mb4'
FIELDS TERMINATED BY ',' OPTIONALLY ENCLOSED BY '"'
LINES TERMINATED BY '\n'
IGNORE 1 ROWS
(titel, erscheinungs_jahr, jugendfreigabe, @erscheinungs_datum, @laufzeit, @genre,  @director, @writer, @actors, plot, @language, @country, @awards, poster, @ratings, @metascore, @imdbRating, @imdbVotes, imdbID, @type, @dvd, @boxoffice, @production, @website, @response)
SET
    erscheinungs_datum = STR_TO_DATE(@erscheinungs_datum, '%d %M %Y'),
    laufzeit = NULLIF(REPLACE(@laufzeit, ' min', ''), ''),
    metascore = NULLIF(@metascore, 'N/A'),
    imdbbewertung = NULLIF(@imdbRating, 'N/A'),
    imdbvotes = NULLIF(REPLACE(@imdbVotes, ',', ''), 'N/A'),
    boxoffice = NULLIF(REPLACE(REPLACE(@boxoffice, '$', ''), ',', ''), 'N/A');

-- 3. Helfer-Prozedur zum Einfügen von Genres und Verknüpfungen (Corrected Delimiter)
DROP PROCEDURE IF EXISTS InsertFilmGenres;

DELIMITER //
CREATE PROCEDURE InsertFilmGenres(IN film_imdbid VARCHAR(255), IN genre_string VARCHAR(255))
BEGIN
    DECLARE genre_item VARCHAR(255);
    DECLARE pos INT DEFAULT 1;
    DECLARE len INT;
    DECLARE film_id_val INT;

    SELECT id INTO film_id_val FROM filme WHERE imdbid = film_imdbid;

    SET len = LENGTH(genre_string);

    WHILE pos <= len DO
        SET genre_item = SUBSTRING_INDEX(SUBSTRING_INDEX(genre_string, ',', pos), ',', -1);
        SET genre_item = TRIM(genre_item);

        IF genre_item != '' THEN
            INSERT IGNORE INTO genres (genre) VALUES (genre_item);

            INSERT IGNORE INTO filme_genres (film_id, genre_id)
            SELECT film_id_val, id FROM genres WHERE genre = genre_item;
        END IF;
        SET pos = pos + 1;
    END WHILE;
END //
DELIMITER ;  -- Reset delimiter to semicolon


-- 4.  Temporäre Tabelle für die Genre-Strings
CREATE TEMPORARY TABLE IF NOT EXISTS temp_film_genres (
    imdbid VARCHAR(255),
    genres VARCHAR(1024)
);

-- 5. Lade die Genre-Strings in die temporäre Tabelle
LOAD DATA LOCAL INFILE 'csv/filme.csv' INTO TABLE temp_film_genres
CHARACTER SET utf8mb4
FIELDS TERMINATED BY ',' OPTIONALLY ENCLOSED BY '"'
LINES TERMINATED BY '\n'
IGNORE 1 ROWS
(@titel, @erscheinungs_jahr, @jugendfreigabe, @erscheinungs_datum, @laufzeit, genres, @director, @writer, @actors, @plot, @language, @country, @awards, @poster, @ratings, @metascore, @imdbRating, @imdbVotes, imdbID, @type, @dvd, @boxoffice, @production, @website, @response);

-- 6. Rufe die Prozedur für jeden Film auf (Corrected Logic)
-- This loop processes the genres from the temp table.
SET @row_num := 0;  -- Initialize before the loop

SET @sql = NULL; -- Initialize @sql

SELECT
  GROUP_CONCAT(DISTINCT
    CONCAT(
      'CALL InsertFilmGenres(''',
      imdbid,
      ''', ''',
      REPLACE(genres, '''', '\\'''),  -- Escape single quotes
      ''');'
    )
  ) INTO @sql
FROM temp_film_genres;

-- Prepare and execute the dynamically generated SQL
PREPARE stmt FROM @sql;
EXECUTE stmt;
DEALLOCATE PREPARE stmt;

-- 7.  Schauspieler-Verarbeitung (Simplified and Corrected)

-- Create a temporary table to hold the split actor names
CREATE TEMPORARY TABLE temp_actors (
    film_id INT,
    actor_name VARCHAR(255)
);

-- Load and split actor names
INSERT INTO temp_actors (film_id, actor_name)
SELECT f.id,
       TRIM(SUBSTRING_INDEX(SUBSTRING_INDEX(REPLACE(f.actors, ',', '|'), '|', n.n), '|', -1)) AS actor_name
FROM (
    SELECT id, imdbid,
    REPLACE(REPLACE(REPLACE(@actors,', ',','),',', '|'), '\'', '') as actors
    FROM filme
      JOIN (SELECT @row_num := @row_num + 1 AS row_number, @actors, imdbID
      FROM (
            SELECT imdbID, erscheinungs_jahr, jugendfreigabe, @erscheinungs_datum, @laufzeit, @genre, @director, @writer, @actors, plot, @language, @country, @awards, poster, @ratings, @metascore, @imdbRating, @imdbVotes, imdbID, @type, @dvd, @boxoffice, @production, @website, @response
            FROM (SELECT * FROM (
                SELECT titel, erscheinungs_jahr, jugendfreigabe, erscheinungs_datum, laufzeit, Genre, Director, Writer, Actors, Plot, Language, Country, Awards, Poster, Ratings, Metascore, imdbRating, imdbVotes, imdbID, Type, DVD, BoxOffice, Production,Website, Response
                FROM (SELECT * FROM  `filme`
                      UNION ALL
                      SELECT  titel, erscheinungs_jahr, jugendfreigabe, erscheinungs_datum, laufzeit, @genre, @director, @writer, @actors, plot, @language, @country, @awards, poster, @ratings, @metascore, @imdbRating, @imdbVotes, imdbID, @type, @dvd, @boxoffice, @production, @website, @response
                      FROM (
                          SELECT * FROM (SELECT  titel, erscheinungs_jahr, jugendfreigabe, @erscheinungs_datum as erscheinungs_datum, @laufzeit as laufzeit, @genre, @director, @writer, @actors, plot, @language, @country, @awards, poster, @ratings, @metascore, @imdbRating, @imdbVotes, imdbID, @type, @dvd, @boxoffice, @production, @website, @response
                          FROM (SELECT titel, erscheinungs_jahr, jugendfreigabe, erscheinungs_datum, laufzeit, Genre, Director, Writer, Actors, Plot, Language, Country, Awards, Poster, Ratings, Metascore, imdbRating, imdbVotes, imdbID, Type, DVD, BoxOffice, Production,Website, Response FROM filme) AS t13
                          ) t14
                      ) t15
                  ) AS t16
              ) AS combined_data
            WHERE combined_data.imdbID IS NOT NULL
            GROUP BY combined_data.imdbID
        ) AS final_data
      ) AS t

) AS f
CROSS JOIN
     (SELECT 1 AS n UNION ALL SELECT 2 UNION ALL SELECT 3 UNION ALL SELECT 4 UNION ALL SELECT 5
      UNION ALL SELECT 6 UNION ALL SELECT 7 UNION ALL SELECT 8 UNION ALL SELECT 9 UNION ALL SELECT 10
      UNION ALL SELECT 11 UNION ALL SELECT 12 UNION ALL SELECT 13 UNION ALL SELECT 14 UNION ALL SELECT 15
      UNION ALL SELECT 16 UNION ALL SELECT 17 UNION ALL SELECT 18 UNION ALL SELECT 19 UNION ALL SELECT 20) n
ON LENGTH(REPLACE(f.actors, '|', '')) <= LENGTH(f.actors) - n.n + 1;  -- Corrected condition

-- Insert new actors into the schauspieler table (IGNORE duplicates)
INSERT IGNORE INTO schauspieler (schauspieler)
SELECT DISTINCT actor_name FROM temp_actors;

-- Create the associations in filme_schauspieler
INSERT IGNORE INTO filme_schauspieler (film_id, schauspieler_id)
SELECT ta.film_id, s.id
FROM temp_actors ta
JOIN schauspieler s ON ta.actor_name = s.schauspieler;

-- Clean up temporary tables
DROP TEMPORARY TABLE IF EXISTS temp_film_genres;
DROP TEMPORARY TABLE IF EXISTS temp_actors;

-- Load other tables (sprachen, land, director, autoren, auszeichnungen, bewertungen)
-- Similar principles apply:
--   - Use LOAD DATA with appropriate options (IGNORE, NULLIF, etc.)
--   - If you have comma-separated values, use a temporary table and string splitting.
--   - Prioritize INSERT IGNORE to avoid duplicate entries.  Use UNIQUE constraints.

LOAD DATA LOCAL INFILE 'csv/filme.csv' IGNORE INTO TABLE `sprachen`
CHARACTER SET 'utf8mb4'
FIELDS TERMINATED BY ',' OPTIONALLY ENCLOSED BY '"'
LINES TERMINATED BY '\n'
IGNORE 1 LINES
(@titel, @erscheinungs_jahr, @jugendfreigabe, @erscheinungs_datum, @laufzeit, @genres, @director, @writer, @actors, @plot, Language, @country, @awards, @poster, @ratings, @metascore, @imdbRating, @imdbVotes, @imdbID, @type, @dvd, @boxoffice, @production, @website, @response)
SET
     sprache = NULLIF(Language, 'N/A');

CREATE TEMPORARY TABLE temp_sprachen (
  film_id INT,
  sprache_name VARCHAR(255)
);

INSERT INTO temp_sprachen (film_id, sprache_name)
SELECT  f.id,
        TRIM(SUBSTRING_INDEX(SUBSTRING_INDEX(REPLACE(sprachen.sprache, ',', '|'), '|', numbers.n), '|', -1)) AS sprache_name
FROM    (SELECT * FROM filme) AS f
CROSS JOIN
         (SELECT 1 AS n UNION ALL SELECT 2 UNION ALL SELECT 3 UNION ALL SELECT 4 UNION ALL SELECT 5
         UNION ALL SELECT 6 UNION ALL SELECT 7 UNION ALL SELECT 8 UNION ALL SELECT 9 UNION ALL SELECT 10
         UNION ALL SELECT 11 UNION ALL SELECT 12 UNION ALL SELECT 13 UNION ALL SELECT 14 UNION ALL SELECT 15
         UNION ALL SELECT 16 UNION ALL SELECT 17 UNION ALL SELECT 18 UNION ALL SELECT 19 UNION ALL SELECT 20) numbers
JOIN sprachen ON sprachen.sprache = f.sprache
ON LENGTH(REPLACE(sprachen.sprache, '|', '')) <= LENGTH(sprachen.sprache) - numbers.n + 1;

INSERT IGNORE INTO sprachen (sprache)
SELECT DISTINCT sprache_name FROM temp_sprachen;

INSERT IGNORE INTO filme_sprachen (film_id, sprache_id)
SELECT ts.film_id, s.id
FROM temp_sprachen ts
JOIN sprachen s ON ts.sprache_name = s.sprache;

DROP TEMPORARY TABLE IF EXISTS temp_sprachen;



LOAD DATA LOCAL INFILE 'csv/filme.csv' INTO TABLE `land`
 CHARACTER SET 'utf8mb4'
 FIELDS TERMINATED BY ','
 LINES TERMINATED BY '\n'
 IGNORE 1 LINES
 (@titel, @erscheinungs_jahr, @jugendfreigabe, @erscheinungs_datum, @laufzeit, @genres, @director, @writer, @actors, @plot, @Language, Country, @awards, @poster, @ratings, @metascore, @imdbRating, @imdbVotes, @imdbID, @type, @dvd, @boxoffice, @production, @website, @response)
 SET
    land = Country;

CREATE TEMPORARY TABLE temp_land (
  film_id INT,
  land_name VARCHAR(255)
);

INSERT INTO temp_land (film_id, land_name)
SELECT  f.id,
        TRIM(SUBSTRING_INDEX(SUBSTRING_INDEX(REPLACE(land.land, ',', '|'), '|', numbers.n), '|', -1)) AS land_name
FROM    (SELECT * FROM filme) AS f
CROSS JOIN
         (SELECT 1 AS n UNION ALL SELECT 2 UNION ALL SELECT 3 UNION ALL SELECT 4 UNION ALL SELECT 5
         UNION ALL SELECT 6 UNION ALL SELECT 7 UNION ALL SELECT 8 UNION ALL SELECT 9 UNION ALL SELECT 10
         UNION ALL SELECT 11 UNION ALL SELECT 12 UNION ALL SELECT 13 UNION ALL SELECT 14 UNION ALL SELECT 15
         UNION ALL SELECT 16 UNION ALL SELECT 17 UNION ALL SELECT 18 UNION ALL SELECT 19 UNION ALL SELECT 20) numbers
JOIN land ON land.land = f.land
ON LENGTH(REPLACE(land.land, '|', '')) <= LENGTH(land.land) - numbers.n + 1;

INSERT IGNORE INTO land (land)
SELECT DISTINCT land_name FROM temp_land;

INSERT IGNORE INTO filme_land (film_id, land_id)
SELECT tl.film_id, l.id
FROM temp_land tl
JOIN land l ON tl.land_name = l.land;

DROP TEMPORARY TABLE IF EXISTS temp_land;



LOAD DATA LOCAL INFILE 'csv/filme.csv' INTO TABLE `director`
 CHARACTER SET 'utf8mb4'
 FIELDS TERMINATED BY ','
 LINES TERMINATED BY '\n'
 IGNORE 1 LINES
 (@titel, @erscheinungs_jahr, @jugendfreigabe, @erscheinungs_datum, @laufzeit, @genres, Director, @writer, @actors, @plot, @Language, @Country, @awards, @poster, @ratings, @metascore, @imdbRating, @imdbVotes, @imdbID, @type, @dvd, @boxoffice, @production, @website, @response)
    SET
        director = Director;

CREATE TEMPORARY TABLE temp_director (
  film_id INT,
  director_name VARCHAR(255)
);

INSERT INTO temp_director (film_id, director_name)
SELECT  f.id,
        TRIM(SUBSTRING_INDEX(SUBSTRING_INDEX(REPLACE(director.director, ',', '|'), '|', numbers.n), '|', -1)) AS director_name
FROM    (SELECT