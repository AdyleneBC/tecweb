<?php

require_once __DIR__ . '/../vendor/autoload.php';

use MYAPI\Delete\Delete;

$id = intval($_POST['id'] ?? 0);

$prodObj = new Delete('marketzone');
$prodObj->delete($id);
echo $prodObj->getData();
