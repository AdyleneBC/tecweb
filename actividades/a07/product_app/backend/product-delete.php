<?php

use TECWEB\MYAPI\Products;

require_once __DIR__ . '/myapi/Products.php';

$id = intval($_POST['id'] ?? 0);
$prodObj = new Products('marketzone', 'root', 'adylene');
$prodObj->delete($id);
echo $prodObj->getData();
