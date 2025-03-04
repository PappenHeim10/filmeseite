import json
import csv

def json_zu_csv_mit_einmaligen_keys(json_datei_pfad, csv_datei_pfad):
    try:
        with open(json_datei_pfad, 'r', encoding='utf-8') as json_datei:
            daten = json.load(json_datei)

        if not daten:
            print("Die JSON-Datei ist leer.")
            return

        keys = daten[0].keys()  # Extrahiere die Keys aus dem ersten Dictionary

        with open(csv_datei_pfad, 'w', newline='', encoding='utf-8') as csv_datei:
            writer = csv.DictWriter(csv_datei, fieldnames=keys)

            writer.writeheader()  # Schreibe die Keys als Header (erste Zeile)
            writer.writerows(daten)  # Schreibe die Daten
            print("CSV-Datei erfolgreich erstellt.")
    except FileNotFoundError:
        print(f"Fehler: Die JSON-Datei '{json_datei_pfad}' wurde nicht gefunden.")
    except json.JSONDecodeError:
        print(f"Fehler: Die JSON-Datei '{json_datei_pfad}' ist ungültig.")
    except Exception as e:
        print(f"Ein unerwarteter Fehler ist aufgetreten: {e}")


json_zu_csv_mit_einmaligen_keys('admin/data/library_in_JSON', 'admin/data/csv/alle_film_daten.csv')