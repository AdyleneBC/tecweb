<?php

require_once __DIR__ . '/../vendor/autoload.php';

use MYAPI\Read\Read;

$id = intval($_POST['id'] ?? 0);

$prodObj = new Read('marketzone');
$prodObj->single($id);
echo $prodObj->getData();
