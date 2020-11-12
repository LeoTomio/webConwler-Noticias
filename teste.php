
<?php

require './util/RadioCidadeCrawler.php';

$rad = new RadioCidadeCrawler();
$paragrafos = $rad->getParagrafos();

print_r($paragrafos);

