<?php 
require __DIR__ . '/vendor/autoload.php';

use Ramsey\Uuid\Uuid;



$uuid = Uuid::uuid4();

echo $uuid;
echo "<hr>";

echo "<pre>";
printf(
    "UUID: %s\nVersion: %d\n",
    $uuid->toString(),
    $uuid->getFields()->getVersion()
);
echo "</pre>";
?>