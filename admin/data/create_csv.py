import os
import json
import csv

def json_zu_csv_mit_fehlenden_keys(json_ordner, csv_datei_pfad):

    alle_daten = []
    alle_keys = set()

    for dateiname in os.listdir(json_ordner):
        if dateiname.endswith(".json"):
            json_datei_pfad = os.path.join(json_ordner, dateiname)
            with open(json_datei_pfad, 'r', encoding='utf-8') as json_datei:
                daten = json.load(json_datei)
                alle_daten.append(daten)
                alle_keys.update(daten.keys())

    if not alle_daten:
        print("Keine JSON-Daten gefunden.")
        return

    # stelle sicher das die Reihenfolge der Keys immer die gleiche ist.
    gewünschte_reihenfolge = ["Title", "Year", "Rated", "Released", "Runtime", "Genre", "Director", "Writer", "Actors", "Plot", "Language", "Country", "Awards", "Poster", "Ratings", "Metascore", "imdbRating", "imdbVotes", "imdbID", "Type", "DVD", "BoxOffice", "Production","totalSeasons", "Website", "Response"]
    fehlende_keys = [key for key in gewünschte_reihenfolge if key not in alle_keys]
    alle_keys.update(fehlende_keys)

    alle_keys = gewünschte_reihenfolge

    with open(csv_datei_pfad, 'w', newline='', encoding='utf-8') as csv_datei:
        writer = csv.DictWriter(csv_datei, fieldnames=alle_keys, restval=None)
        writer.writeheader()
        writer.writerows(alle_daten)

json_ordner = 'admin/data/library_in_JSON'
csv_datei_pfad = 'admin/data/csv/alle_filme.csv'

if not os.path.exists(json_ordner):
    print(f"Fehler: Das Verzeichnis '{json_ordner}' existiert nicht.")
else:
    json_zu_csv_mit_fehlenden_keys(json_ordner, csv_datei_pfad)
    print("CSV-Datei erfolgreich erstellt.")