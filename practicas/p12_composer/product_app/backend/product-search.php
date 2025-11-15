<?php

require_once __DIR__ . '/../vendor/autoload.php';

use MYAPI\Read\Read;

$q = trim($_GET['search'] ?? '');

$prodObj = new Read('marketzone');
$prodObj->search($q);
echo $prodObj->getData();
