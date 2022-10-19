<?php
require_once(__DIR__
    . DIRECTORY_SEPARATOR
    . '..'
    . DIRECTORY_SEPARATOR
    . 'bootstrap.php');

require_once(__DIR__ . DIRECTORY_SEPARATOR . 'connection.php');

use QueryFactory\QueryFactory;

$qf = new QueryFactory('mysql');

$insert = $qf->insert();
$insert->table('brand')
    ->cols([['data = %s', ['10']]])
    ->join('route', [['brand.brand_id = route.object_id']])
    ->build();

$sth = $connection->prepare($insert->getStatement());
$sth->execute($insert->getBindValues());
$result = $connection->lastInsertId();
dumpe([$result, $insert->getStatement(), $insert->getBindValues(), $insert]);
