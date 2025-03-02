import csv

def csv_spalten_extrahieren(eingabe_datei, ausgabe_dateien, spalten_auswahl, einzigartige_werte=None):
    """
    Extrahiert Spalten aus einer CSV-Datei und speichert sie in neuen CSV-Dateien.

    Args:
        eingabe_datei (str): Pfad zur Eingabe-CSV-Datei.
        ausgabe_dateien (dict): Dictionary mit Ausgabedateinamen als Schlüssel.
        spalten_auswahl (dict): Dictionary mit Ausgabedateinamen und zu extrahierenden Spalten.
        einzigartige_werte (list): Liste der Ausgabedateinamen, für die eindeutige Werte gespeichert werden sollen.
    """
    if einzigartige_werte is None:
        einzigartige_werte = []

    try:
        with open(eingabe_datei, 'r', newline='', encoding='utf-8') as eingabe_csv:
            leser = csv.reader(eingabe_csv)
            kopfzeile = next(leser)

            spalten_indizes = {}
            for ausgabe_datei, spalten_namen in spalten_auswahl.items():
                spalten_indizes[ausgabe_datei] = [kopfzeile.index(spalte) for spalte in spalten_namen]

            ausgabe_schreiber = {}
            bereits_gesehene_werte = {datei: set() for datei in ausgabe_dateien if datei in einzigartige_werte}

            for ausgabe_datei in ausgabe_dateien:
                ausgabe_schreiber[ausgabe_datei] = csv.writer(open(ausgabe_datei, 'w', newline='', encoding='utf-8'))
                ausgabe_schreiber[ausgabe_datei].writerow(spalten_auswahl[ausgabe_datei])

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

            for schreiber in ausgabe_schreiber.values():
                schreiber.writerow([])

    except FileNotFoundError:
        print(f"Fehler: Die Datei '{eingabe_datei}' wurde nicht gefunden.")
    except ValueError as e:
        print(f"Fehler: {e}")
    except Exception as e:
        print(f"Ein unerwarteter Fehler ist aufgetreten: {e}")

datei_dir = 'admin/data/csv/'

eingabe_datei = datei_dir + 'alle_filme.csv'
ausgabe_dateien = [datei_dir + 'filme.csv', datei_dir + 'schauspieler.csv', datei_dir + 'directoren.csv', datei_dir + 'laender.csv', datei_dir + 'sprachen.csv', datei_dir + 'autoren.csv', datei_dir + 'bewertungen.csv']
spalten_auswahl = {
    datei_dir + 'filme.csv': ['Title', 'Year', 'Rated', 'Released', 'Runtime', 'Plot', 'Poster', 'Metascore', 'imdbRating', 'imdbVotes', 'imdbID', 'BoxOffice'],
    datei_dir + 'schauspieler.csv': ['Actors'],
    datei_dir + 'directoren.csv': ['Director'],
    datei_dir + 'bewertungen.csv': ['Ratings'],
    datei_dir + 'sprachen.csv': ['Language'],
    datei_dir + 'autoren.csv': ['Writer'],
    datei_dir + 'laender.csv': ['Country']
}
einzigartige_werte = [datei_dir + 'schauspieler.csv', datei_dir + 'directoren.csv', datei_dir + 'laender.csv', datei_dir + 'sprachen.csv', datei_dir + 'autoren.csv']

csv_spalten_extrahieren(eingabe_datei, ausgabe_dateien, spalten_auswahl, einzigartige_werte)