# Film Applikation

Das ist ein Forum um sich mit anderen Filmophilen annonym austauschen zu können.

## Features

- Benutzerauthentifizierung (Login/Registrierung)
- Admin-Backend
- Kommentarbereich unter jedem Video mit Consistentem Speicher
- Rechtliche Seiten (AGB, Impressum, Datenschutz)
- Große auswahl an Filmen

## Technologien

- PHP 8.0+
- MySQL 8.0+
- HTML5 & CSS3
- PDO für Datenbankzugriff
- AJAX 

## Installation

Folgen Sie diesen Schritten, um das Projekt einzurichten:


1. **Projekt in XAMPP kopieren**
   - Kopieren Sie alle Projektdateien in das: `C:\xampp\htdocs` Verzeichnis

2. **Datenbank erstellen**
   - Starten Sie XAMPP Control Panel
   - Starten Sie Apache und MySQL
   - Öffnen Sie in Ihrem Browser:
     http://localhost/filmeseite

3. **Datenbank konfigurieren**
   - Führen sie folgendes Programm aus

   C:\xampp\htdocs\filmeseite\admin\data\create_database.bat

   - Nach erfolgreicher Konfiguration wird die Datenbankverbindung automatisch eingerichtet

### Erste Anmeldung

Nach erfolgreicher Installation können Sie sich mit folgenden Zugangsdaten als Administrator einloggen:

**Als Administrator:**
- benutzername: admin
- Passwort: admin

Ansonsten fügen sie einen administratrt pber die MYSQL injection in die Datenbank ein

## Sicherheit

- SQL-Injection Prevention durch PDO Prepared Statements
- XSS-Protection durch htmlspecialchars()
- Input Validation und Sanitization
- Session-Management

## Entwicklung

- UTF-8 Encoding
- Kommentierung des Codes auf Deutsch
- Fehlerbehandlung durch try-catch
- Benutzerfreundliche Oberfläche


## Lizenz

Dieses Projekt hat keine Lizension.
