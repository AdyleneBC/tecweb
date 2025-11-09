<?php

use TECWEB\MYAPI\Products;

require_once __DIR__ . '/myapi/Products.php';

$q = trim($_GET['search'] ?? '');   
$prodObj = new Products('marketzone', 'root', 'adylene');
$prodObj->search($q);
echo $prodObj->getData();
?>