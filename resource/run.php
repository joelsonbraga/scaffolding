<?php

require_once '../lib/default.php';
if (isset($_POST['action']) && $_POST['action'] === 'generate') {
    require_once __DIR__ . '/Generate.php';

    (new Generate($_REQUEST))->run();
}

header('location:../resource.php?msg=Resource gerado com sucesso. É só copiá-lo de dentro da pasta generated.');
