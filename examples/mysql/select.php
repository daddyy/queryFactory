<?php
require_once(__DIR__
    . DIRECTORY_SEPARATOR
    . '..'
    . DIRECTORY_SEPARATOR
    . 'bootstrap.php');

require_once(__DIR__ . DIRECTORY_SEPARATOR . 'connection.php');

use QueryFactory\QueryFactory;

$qf = new QueryFactory('mysql');

$select = $qf->select();
$select->table('route')
    ->conditions([
        ['%? = route_id OR route_id = %i', ["1", "2"]],
        ['route_id = %? OR route_id = %i', ["1", "2"]],
        ['route_id = %i', ['1']]
    ])
    ->cols(['*', ['concat(%i, %s) as ahoj', ['10', 'ahoj']]])
    ->condition('route_id = %? OR route_id = %i', ["1", "2"])
    ->join('brand', [['brand.brand_id = route.object_id'], ['route.object_id = %i', [1]]])
    ->groupBy(['route_id'])
    ->distinct()
    ->build();
$sth = $connection->prepare($select->getStatement());
$sth->execute($select->getBindValues());
$result = $sth->fetchAll(PDO::FETCH_ASSOC);
dumpe([$result, $select->getStatement(), $select->getBindValues(), $select]);
