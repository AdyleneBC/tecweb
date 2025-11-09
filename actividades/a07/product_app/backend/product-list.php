<?php

use TECWEB\MYAPI\Products;

require_once __DIR__ . '/myapi/Products.php';

$prodObj = new Products('marketzone', 'root', 'adylene');
$prodObj->list();         
echo $prodObj->getData();         
?>