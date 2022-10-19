<?php
require_once(__DIR__
    . DIRECTORY_SEPARATOR
    . '..'
    . DIRECTORY_SEPARATOR
    . 'bootstrap.php');

require_once(__DIR__ . DIRECTORY_SEPARATOR . 'connection.php');

use QueryFactory\QueryFactory;

$qf = new QueryFactory('mysql');

$update = $qf->update();
$update->table('brand')
    ->cols([['brand.deleted = %b', [0]]])
    ->join('route', [['brand.brand_id = route.object_id']])
    ->condition('route_id in (%i)', [join(',', [1, 2, 3, 4, 5, 6])])
    ->build();

$sth = $connection->prepare($update->getStatement());
dump($update->getStatement());
$sth->execute($update->getBindValues());
$result = $sth->rowCount();
dumpe([$result, $update->getStatement(), $update->getBindValues(), $update]);
