import json
import csv
import os
from classes import EmptyJSONError

def json_zu_csv_mit_einmaligen_keys(json_datei_pfad, csv_datei_pfad):
    try:
        with open(json_datei_pfad, 'r', encoding='utf-8') as json_datei:
            daten = json.load(json_datei)

        if not isinstance(daten, list):
            raise TypeError("Die JSON-Daten müssen eine Liste von Dictionaries sein.")
        if not daten:
            raise EmptyJSONError("Die JSON-Datei ist leer.")


        # WICHTIG: Hier beginngt die richtige Magie
        keys = set()# Die Schlüssel
        for item in daten:
            if isinstance(item, dict):  # Stelle sicher, dass es ein Dictionary ist
                keys.update(item.keys()) # NOTE: Ich bin mir nicht sicher ob es nötig ist diesen teil zu habe
        if not keys:
             raise ValueError("Die JSON-Datei enthält keine gültigen Schlüssel.") #Error wenn keine Keys vorhanden sind.
        keys = sorted(list(keys))  # Sortiere für konsistente Reihenfolge

        # Überprüfe, ob das Verzeichnis für die CSV-Datei existiert.
        csv_verzeichnis = os.path.dirname(csv_datei_pfad) 
        if not os.path.exists(csv_verzeichnis):
            try: # Fall das verzeichniss nicht existiert
                os.makedirs(csv_verzeichnis, exist_ok=True) # Erstelle Verzeichnis, falls nicht vorhanden. exist_ok=True verhindert Fehler, wenn es bereits existiert.
                print(f"Verzeichnis erstellt: {csv_verzeichnis}")
            except OSError as e:
                raise OSError(f"Fehler beim Erstellen des Verzeichnisses '{csv_verzeichnis}': {e}")

        # WICHTIG: Hier wird die CSV Datei erstellt
        with open(csv_datei_pfad, 'w', newline='', encoding='utf-8') as csv_datei:
            writer = csv.DictWriter(csv_datei, fieldnames=keys) # WICHTIG: maps dictionaries onto output rows, allowing you to write data where each row is represented by a dictionary
            writer.writeheader() # es wir ein header erstellt
            writer.writerows(daten)
            print("CSV-Datei erfolgreich erstellt.")

    except FileNotFoundError:
        print(f"Fehler: Die JSON-Datei '{json_datei_pfad}' wurde nicht gefunden.")
    except json.JSONDecodeError:
        print(f"Fehler: Die JSON-Datei '{json_datei_pfad}' ist ungültig.")
    except PermissionError as e:
        print(f"Fehler: {e} - Kein Zugriffsrecht auf Datei oder Ordner.")  # Genauere Meldung
    except TypeError as e:
        print(f"Fehler: {e} - Unerwarteter Datentyp.")
    except ValueError as e: # Value Error hinzugefügt
        print(f"Fehler: {e}")
    except Exception as e:
        print(f"Ein unerwarteter Fehler ist aufgetreten: {e}")



# Testen mit relativen Pfaden (wenn du im richtigen Verzeichnis bist)
#json_datei_pfad = 'admin/data/library_in_JSON.json'  # MIT Dateiendung .json!
#csv_datei_pfad = 'admin/data/alle_filme.csv'

# Oder mit absoluten Pfaden (sicherer)
json_datei_pfad = os.path.abspath('admin/data/library_in_JSON') # MIT Dateiendung .json!
csv_datei_pfad = os.path.abspath('admin/data/csv/alle_filme.csv')

# json_zu_csv_mit_einmaligen_keys(json_datei_pfad, csv_datei_pfad)  #Auskommentieren bis der Fehler behoben ist

#Beispiel JSON

json_zu_csv_mit_einmaligen_keys(json_datei_pfad, csv_datei_pfad)