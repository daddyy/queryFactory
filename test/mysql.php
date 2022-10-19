<?php
require_once(__DIR__
    . DIRECTORY_SEPARATOR
    . '..'
    . DIRECTORY_SEPARATOR
    . 'vendor'
    . DIRECTORY_SEPARATOR
    . 'autoload.php');

use QueryFactory\QueryFactory;
use QueryFactory\QueryQuoter;

$dsn = "mysql://daddyy:asdf@localhost/test";
$dsn = (parse_url($dsn));
try {
    $connectionString = $dsn['scheme'] . ':host=' . $dsn['host'] . ';dbname=' . ltrim($dsn['path'], '/');
    $connection = new PDO(
        $connectionString,
        $dsn['user'],
        $dsn['pass']
    );
} catch (PDOException $e) {
    echo "Connection failed: " . $e->getMessage();
    die();
}

Tracy\Debugger::enable(false, realpath('../logs'));
Tracy\Debugger::$strictMode = false;
Tracy\Debugger::$maxDepth = 15;
Tracy\Debugger::$maxLength = 500;
Tracy\Debugger::$showLocation = true;

$qf = new QueryFactory($dsn['scheme']);
$select = $qf->select();
$select->table('route')
    ->conditions([['!%? = route_id OR route_id = %i', ["1", "2"]], ['route_id = %? OR route_id = %i', ["1", "2"]], ['route_id = %i', ['1']]])
    ->cols(['*', ['concat(%i, %s) as ahoj', ['10', 'ahoj']]])
    ->condition('route_id = %? OR route_id = %i', ["1", "2"])
    ->join('brand', [['brand.brand_id = route.object_id'], ['route.object_id = %i', [1]]])
    ->groupBy(['route_id'])
    ->distinct()
    ->build();
dump([$select->getStatement(), $select->getBindValues()]);
$sth = $connection->prepare($select->getStatement());
$sth->execute($select->getBindValues());
$rows = $sth->fetchAll(PDO::FETCH_ASSOC);
dump([$select, $rows]);
