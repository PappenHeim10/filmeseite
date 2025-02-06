@echo off

mkdir %1

cd %1

mkdir js css data bilder include templates test admin

echo ^<?php echo "Hello World!"; ?^> > index.php

cd css
echo /* CSS Code für Hauptverzeichnis */ > style.css
cd ..

cd js
echo // JavaScript Code für Hauptverzeichnis > script.js
cd ..

cd admin

mkdir js css data include templates test

cd css
echo /* CSS Code für Admin-Verzeichnis */ > style.css
cd ..

cd js
echo // JavaScript Code für Admin-Verzeichnis > script.js
cd ..