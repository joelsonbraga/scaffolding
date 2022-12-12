<?php
require_once 'Services/NaturezaOperacaoService.php';

$natureza = new \Services\NaturezaOperacaoService();


$natureza->findAll();