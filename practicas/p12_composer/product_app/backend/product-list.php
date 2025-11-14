<?php

require_once __DIR__ . '/../vendor/autoload.php';

use MYAPI\Read\Read;

$prodObj = new Read('marketzone');
$prodObj->list();
echo $prodObj->getData();
