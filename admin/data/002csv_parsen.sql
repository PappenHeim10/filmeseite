USE filmeseite_cohen;


-- genres werden eingefügt 
LOAD DATA LOCAL INFILE 'csv/genres1.csv' INTO TABLE  `genres`
CHARACTER SET 'utf8mb4'
FIELDS TERMINATED BY ','
LINES TERMINATED BY '\n'
IGNORE 1 LINES
(id, genre);

