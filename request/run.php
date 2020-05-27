<?php
error_reporting(E_ALL);
ini_set('display_errors', 1);

if (isset($_POST['action']) && $_POST['action'] === 'generate') {
    require_once __DIR__ . '/Generate.php';

    (new Generate($_REQUEST))->run();
}

header('location:./request.php?msg=Request gerado com sucesso. É só copiá-lo de dentro da pasta generated.');
