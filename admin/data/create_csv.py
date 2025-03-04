import json
import csv
import os
from classes import EmptyJSONError


def json_dateien_zu_einer_csv(ordner_pfad, csv_datei_pfad):
    """
    Liest alle JSON-Dateien in einem Ordner, extrahiert und transformiert Ratings,
    und schreibt alles in eine einzige CSV-Datei.
    """
    if not os.path.isdir(ordner_pfad):
        print(f"Fehler: '{ordner_pfad}' ist kein gültiger Ordner.")
        return

    all_data = []
    all_keys = set()

    for dateiname in os.listdir(ordner_pfad):
        if not dateiname.endswith(".json"):
            continue

        json_datei_pfad = os.path.join(ordner_pfad, dateiname)
        try:
            with open(json_datei_pfad, 'r', encoding='utf-8') as json_datei:
                daten = json.load(json_datei)

            if not isinstance(daten, dict):  # Einzelnes Dictionary, keine Liste!
                print(f"Warnung: '{dateiname}' enthält kein Dictionary. Überspringe.")
                continue
            if not daten:
                print(f"Warnung: '{dateiname}' ist leer. Überspringe.")
                continue

            # Ratings verarbeiten
            ratings = daten.pop("Ratings", [])  # Entferne 'Ratings' und liefere [] falls nicht vorhanden
            if isinstance(ratings, list):
                for rating in ratings:
                    if isinstance(rating, dict) and "Source" in rating and "Value" in rating:
                        # Erstelle Spaltennamen wie 'IMDB_Rating'
                        source = rating["Source"].replace(" ", "_").replace(".", "")  # Für gültige Spaltennamen
                        value = rating["Value"]
                        daten[f"{source}_Rating"] = value  # Füge als neue Spalte hinzu
            # Füge andere keys hinzu
            all_keys.update(daten.keys())
            all_data.append(daten)

        except (json.JSONDecodeError, FileNotFoundError, TypeError) as e:
            print(f"Fehler beim Lesen von '{dateiname}': {e}. Überspringe.")
        except Exception as e:
            print(f"Unerwarteter Fehler beim Lesen von '{dateiname}': {e}. Überspringe.")


    if not all_data:
        raise EmptyJSONError("Keine gültigen Daten in den JSON-Dateien gefunden.")
    if not all_keys:
        raise ValueError("Keine gültigen Schlüssel in den JSON-Dateien gefunden.")

    all_keys = sorted(list(all_keys))


    csv_verzeichnis = os.path.dirname(csv_datei_pfad)
    if not os.path.exists(csv_verzeichnis):
        try:
            os.makedirs(csv_verzeichnis, exist_ok=True)
        except OSError as e:
            raise OSError(f"Fehler beim Erstellen des Verzeichnisses '{csv_verzeichnis}': {e}")

    with open(csv_datei_pfad, 'w', newline='', encoding='utf-8') as csv_datei:
        writer = csv.DictWriter(csv_datei, fieldnames=all_keys)
        writer.writeheader()
        writer.writerows(all_data)
        print(f"CSV-Datei erfolgreich erstellt: {csv_datei_pfad}")

# Beispielaufruf (angepasst)
json_ordner = 'admin/data/library_in_JSON'
csv_datei = 'admin/data/csv/alle_filme.csv'

json_dateien_zu_einer_csv(json_ordner, csv_datei)
print("Alle JSON-Dateien wurden in einer CSV-Datei zusammengeführt.")



def create_join_table_csvs(json_folder, output_folder):
    """
    Processes JSON files in a folder to create CSV files for join tables
    (filme_genres, filme_schauspieler, etc.).
    """
    if not os.path.isdir(json_folder):
        print(f"Error: '{json_folder}' is not a valid folder.")
        return

    # Create output directory if it doesn't exist
    os.makedirs(output_folder, exist_ok=True)

    # Initialize CSV writers for each join table
    genre_writer = csv.writer(open(os.path.join(output_folder, 'filme_genres.csv'), 'w', newline='', encoding='utf-8'))
    actor_writer = csv.writer(open(os.path.join(output_folder, 'filme_schauspieler.csv'), 'w', newline='', encoding='utf-8'))
    language_writer = csv.writer(open(os.path.join(output_folder, 'filme_sprachen.csv'), 'w', newline='', encoding='utf-8'))
    country_writer = csv.writer(open(os.path.join(output_folder, 'filme_land.csv'), 'w', newline='', encoding='utf-8'))
    director_writer = csv.writer(open(os.path.join(output_folder, 'film_director.csv'), 'w', newline='', encoding='utf-8'))
    author_writer = csv.writer(open(os.path.join(output_folder, 'filme_autoren.csv'), 'w', newline='', encoding='utf-8'))
    #bewertungen_writer = csv.writer(open(os.path.join(output_folder, 'filme_bewertungen.csv'), 'w', newline='', encoding='utf-8')) # Add if needed


    # Write headers
    genre_writer.writerow(['imdbid', 'genre'])  # Use imdbid as the link
    actor_writer.writerow(['imdbid', 'schauspieler'])
    language_writer.writerow(['imdbid', 'sprache'])
    country_writer.writerow(['imdbid', 'land'])
    director_writer.writerow(['imdbid', 'director'])
    author_writer.writerow(['imdbid', 'autor'])
    #bewertungen_writer.writerow(['imdbid', 'quelle', 'bewertung']) # add if needed

    for filename in os.listdir(json_folder):
        if filename.endswith(".json"):
            json_file_path = os.path.join(json_folder, filename)
            try:
                with open(json_file_path, 'r', encoding='utf-8') as f:
                    data = json.load(f)

                if not isinstance(data, dict) or 'imdbID' not in data:
                    print(f"Warning: Skipping {filename} - Invalid format or missing imdbID.")
                    continue

                imdbid = data['imdbID']

                # --- Genres ---
                genres = data.get('Genre', '')  # Get the comma-separated genres
                if genres:
                    for genre in genres.split(','):
                        genre = genre.strip()  # Remove leading/trailing spaces
                        if genre:  # Avoid empty strings
                            genre_writer.writerow([imdbid, genre])

                # --- Actors ---
                actors = data.get('Actors', '')
                if actors:
                    for actor in actors.split(','):
                        actor = actor.strip()
                        if actor:
                            actor_writer.writerow([imdbid, actor])

                # --- Languages ---
                languages = data.get('Language', '')
                if languages:
                    for language in languages.split(','):
                        language = language.strip()
                        if language:
                            language_writer.writerow([imdbid, language])

                # --- Countries ---
                countries = data.get('Country', '')
                if countries:
                    for country in countries.split(','):
                        country = country.strip()
                        if country:
                            country_writer.writerow([imdbid, country])
                # --- Directors ---
                directors = data.get('Director', '')
                if directors:
                    for director in directors.split(','):
                        director = director.strip()
                        if director:
                            director_writer.writerow([imdbid, director])

                # --- Authors ---
                authors = data.get('Writer', '')
                if authors:
                    for author in authors.split(','):
                        author = author.strip()
                        if author:
                            author_writer.writerow([imdbid, author])

                # --- Ratings --- # Add if needed
                #ratings = data.get('Ratings', [])
                #for rating in ratings:
                 #   if isinstance(rating, dict) and 'Source' in rating and 'Value' in rating:
                  #      quelle = rating['Source']
                   #     bewertung = rating['Value']
                    #    bewertungen_writer.writerow([imdbid, quelle, bewertung])

            except (json.JSONDecodeError, FileNotFoundError, TypeError) as e:
                print(f"Error processing {filename}: {e}")
            except Exception as e:
                print(f"Unexpected error with {filename}: {e}")


# Example Usage:
json_input_folder = 'admin/data/library_in_JSON'
csv_output_folder = 'admin/data/csv/join_tables'  # A new subfolder for join tables
create_join_table_csvs(json_input_folder, csv_output_folder)