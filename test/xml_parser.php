<?php

$dom = new DOMDocument();

$dom->load('test.xml');

print "<pre>" . htmlentities($dom->saveXML()) . "</pre>";

?>