<?php
error_reporting(E_ALL);
ini_set('display_errors', 1);

require_once 'lib/Database/ConnectionDatabase.php';
require_once 'lib/Database/ResponseData.php';
require_once 'Services/NaturezaOperacaoService.php';

$natureza = new \Services\NaturezaOperacaoService();


echo '<pre>';
print_r($natureza->findAll());
echo '</pre>';