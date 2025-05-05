# Film Applikation

Willkommen zur Film-Applikation – einem Forum für Filmbegeisterte, die sich anonym über ihre Lieblingsfilme austauschen möchten.

## Überblick

Diese Plattform bietet eine sichere und benutzerfreundliche Umgebung, um Filme zu entdecken, zu diskutieren und zu bewerten. Mit Funktionen wie Benutzerauthentifizierung, einem Admin-Backend und einem Kommentarbereich unter jedem Film bietet die Applikation alles, was das Filmherz begehrt.

## Features

- **Benutzerauthentifizierung:**
  - Einfache Registrierung und sicherer Login-Prozess.
  - Anonyme Teilnahme an Diskussionen.

- **Admin-Backend:**
  - Verwaltung von Filmen, Benutzern und Kommentaren.
  - Möglichkeit, Inhalte zu moderieren und die Plattform zu pflegen.

- **Kommentarbereich:**
  - Interaktive Kommentarbereiche unter jedem Film.
  - Konsistente Speicherung der Kommentare für eine nahtlose Benutzererfahrung.

- **Rechtliche Seiten:**
  - Zugriff auf wichtige rechtliche Dokumente wie AGB, Impressum und Datenschutzbestimmungen.

- **Große Filmauswahl:**
  - Umfangreiche und vielfältige Sammlung von Filmen zur Auswahl.

## Technologien

- **Backend:**
  - PHP 8.0+
  - MySQL 8.0+
  - PDO (PHP Data Objects) für sicheren Datenbankzugriff

- **Frontend:**
  - HTML5 & CSS3
  - AJAX für dynamische Interaktionen

## Installation

Um die Film-Applikation lokal einzurichten, folgen Sie bitte diesen Schritten:

1. **Projektdateien kopieren:**
   - Kopieren Sie den gesamten Projektordner in das Verzeichnis `C:\xampp\htdocs`.

2. **Datenbank erstellen:**
   - Starten Sie das XAMPP Control Panel.
   - Aktivieren Sie die Module "Apache" und "MySQL".
   - Öffnen Sie Ihren Webbrowser und navigieren Sie zu `http://localhost/filmeseite`.

3. **Datenbank konfigurieren:**
   - Führen Sie das Skript zur Datenbankerstellung aus:
     ```
     C:\xampp\htdocs\filmeseite\admin\data\create_database.bat
     ```
   - Das Skript erstellt die erforderliche Datenbank und konfiguriert die Datenbankverbindung automatisch.

## Sicherheit

Die Sicherheit der Applikation hat höchste Priorität. Es wurden folgende Maßnahmen implementiert:

- **SQL-Injection-Schutz:** Verwendung von PDO Prepared Statements, um SQL-Injection-Angriffe zu verhindern.
- **XSS-Schutz:** Anwendung von `htmlspecialchars()` zur sicheren Darstellung von Benutzereingaben und zum Schutz vor Cross-Site Scripting (XSS).
- **Input Validation und Sanitization:** Strenge Überprüfung und Bereinigung aller Benutzereingaben, um schädliche Daten zu filtern.
- **Session-Management:** Sichere Verwaltung von Benutzersitzungen, um unbefugten Zugriff zu verhindern.

## Entwicklung

Während der Entwicklung wurden folgende Prinzipien befolgt:

- **UTF-8-Kodierung:** Durchgängige Verwendung von UTF-8 zur Unterstützung verschiedener Zeichensätze.
- **Code-Kommentierung:** Ausführliche Kommentare im Code, um die Wartbarkeit und Verständlichkeit zu verbessern.
- **Fehlerbehandlung:** Einsatz von Try-Catch-Blöcken zur robusten Fehlerbehandlung und Vermeidung von Programmabstürzen.
- **Benutzerfreundlichkeit:** Gestaltung einer intuitiven und ansprechenden Benutzeroberfläche.

## Zusätzliche Informationen

- **Dokumentation:** Detaillierte Dokumentation finden Sie im Ordner `C:\xampp\htdocs\filmeseite\admin`.
- **Datenbank-Dump:** Ein Abbild des Datenbankzustands vom 16.02.2025 ist als `dump.sql` im Projektverzeichnis verfügbar.

## Lizenz

Dieses Projekt ist nicht lizenziert. Alle Rechte vorbehalten.
