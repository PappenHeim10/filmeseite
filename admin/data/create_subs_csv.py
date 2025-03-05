import csv
import os
import re  # Importiere das re-Modul für reguläre Ausdrücke

def csv_spalten_extrahieren(eingabe_datei, ausgabe_dateien, spalten_auswahl, einzigartige_werte=None):
    """
    Extrahiert Spalten aus einer CSV-Datei und speichert sie in neuen CSV-Dateien.
    Behandelt Ratings, indem separate Spalten für jede Quelle erstellt werden.
    """
    if einzigartige_werte is None:
        einzigartige_werte = []

    try:
        with open(eingabe_datei, 'r', newline='', encoding='utf-8') as eingabe_csv:
            leser = csv.reader(eingabe_csv)
            kopfzeile = next(leser)

            # 1. Dynamische Anpassung der Kopfzeile und spalten_auswahl
            neue_kopfzeile = kopfzeile[:]  # Kopie der ursprünglichen Kopfzeile
            neue_spalten_auswahl = {datei: spalten[:] for datei, spalten in spalten_auswahl.items()}

            for i, spalte in enumerate(kopfzeile):
                if "_Rating" in spalte:  # Check auf Rating-Spalten
                    rating_quelle = spalte.replace("_Rating", "")
                    if any(rating_quelle in s for s in neue_spalten_auswahl.get(datei_dir + 'bewertungen.csv', [])): # Check ob Rating Quelle in bewertungen.csv
                        continue # wenn ja, dann überspringe
                    neue_spalten_auswahl.setdefault(datei_dir + 'bewertungen.csv', []).append(spalte)  # Füge dynamisch hinzu
                    if datei_dir + 'bewertungen.csv' not in neue_kopfzeile:
                        neue_kopfzeile.extend([spalte]) # Füge zur Kopzeile hinzu
            #print(neue_spalten_auswahl) # zum Debuggen
            # 2. Index-Ermittlung (angepasst)
            spalten_indizes = {}
            for ausgabe_datei, spalten_namen in neue_spalten_auswahl.items():
                try:
                    spalten_indizes[ausgabe_datei] = [neue_kopfzeile.index(spalte) for spalte in spalten_namen]
                except ValueError as e:
                    print(f"Fehler: Spalte '{e.args[0].split('\'')[1]}' nicht in der Kopfzeile gefunden.  Datei: {ausgabe_datei}")
                    # Optional:  Hier könnte man das Programm abbrechen, wenn eine Spalte fehlt
                    return

            # 3. Datei-Initialisierung (angepasst)
            ausgabe_schreiber = {}
            bereits_gesehene_werte = {datei: set() for datei in ausgabe_dateien if datei in einzigartige_werte}

            for ausgabe_datei in ausgabe_dateien:
                # Erstelle Verzeichnisse, falls nicht vorhanden
                ausgabe_verzeichnis = os.path.dirname(ausgabe_datei)
                if not os.path.exists(ausgabe_verzeichnis):
                    os.makedirs(ausgabe_verzeichnis, exist_ok=True)
                try:
                    ausgabe_schreiber[ausgabe_datei] = csv.writer(open(ausgabe_datei, 'w', newline='', encoding='utf-8'))
                except Exception as e:
                    print(f"Fehler beim Öffnen von '{ausgabe_datei}' zum Schreiben: {e}")
                    return  # Abbruch, wenn eine Datei nicht geöffnet werden kann

                if ausgabe_datei in neue_spalten_auswahl: # Schreibe Header nur wenn Spalten vorhanden
                    ausgabe_schreiber[ausgabe_datei].writerow(neue_spalten_auswahl[ausgabe_datei])

            # 4. Daten schreiben (bleibt weitgehend gleich, mit Tupel-Anpassung)
            for zeile in leser:
                for ausgabe_datei, indizes in spalten_indizes.items():
                    ausgewaehlte_spalten = [zeile[i] for i in indizes]
                    werte_als_tupel = tuple(ausgewaehlte_spalten)

                    if ausgabe_datei in einzigartige_werte:
                        if werte_als_tupel not in bereits_gesehene_werte[ausgabe_datei]:
                            ausgabe_schreiber[ausgabe_datei].writerow(ausgewaehlte_spalten)
                            bereits_gesehene_werte[ausgabe_datei].add(werte_als_tupel)
                    else:
                        ausgabe_schreiber[ausgabe_datei].writerow(ausgewaehlte_spalten)

            # Leere Zeilen (optional - für Kompatibilität mit dem alten Code)
            for schreiber in ausgabe_schreiber.values():
                schreiber.writerow([]) #optionale leere Zeilen

    except FileNotFoundError:
        print(f"Fehler: Die Datei '{eingabe_datei}' wurde nicht gefunden.")
    except ValueError as e:
        print(f"Fehler: {e}")
    except Exception as e:
        print(f"Ein unerwarteter Fehler ist aufgetreten: {e}")
    except IndexError as e:
        print(f"Index Fehler: {e} Stellen sie sicher, dass die Spalten vorhanden sind und die korrekte Anzahl an Spalten haben.")

# --- Beispielaufruf (unverändert, außer Pfad) ---
datei_dir = 'admin/data/csv/'

eingabe_datei = datei_dir + 'alle_filme.csv'
ausgabe_dateien = [datei_dir + 'filme.csv', datei_dir + 'schauspieler.csv', datei_dir + 'directoren.csv', datei_dir + 'laender.csv', datei_dir + 'sprachen.csv', datei_dir + 'autoren.csv', datei_dir + 'bewertungen.csv']
spalten_auswahl = {
    datei_dir + 'filme.csv': ['Title', 'Year', 'Rated', 'Released', 'Runtime', 'Plot', 'Poster', 'Metascore', 'imdbRating', 'imdbVotes', 'imdbID', 'BoxOffice'],
    datei_dir + 'schauspieler.csv': ['Actors'],
    datei_dir + 'directoren.csv': ['Director'],
    datei_dir + 'sprachen.csv': ['Language'],
    datei_dir + 'autoren.csv': ['Writer'],
    datei_dir + 'laender.csv': ['Country']
}

einzigartige_werte = [datei_dir + 'schauspieler.csv', datei_dir + 'directoren.csv', datei_dir + 'laender.csv', datei_dir + 'sprachen.csv', datei_dir + 'autoren.csv']

csv_spalten_extrahieren(eingabe_datei, ausgabe_dateien, spalten_auswahl, einzigartige_werte)

print("CSV-Spalten extrahiert und geschrieben.")