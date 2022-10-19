<?php

namespace QueryFactory\Common\Interfaces\Parts;

use QueryFactory\Common\Interfaces\Delete;
use QueryFactory\Common\Interfaces\Insert;
use QueryFactory\Common\Interfaces\Select;
use QueryFactory\Common\Interfaces\Update;

interface Join
{
    public function resetJoins(): Select|Update|Insert;
    public function join(string $table, array $conditions, string $typeJoin = 'inner'): Select|Update|Insert;
    public function joins(array $joins): Select|Update|Insert;
    public function buildJoins(): string;
}
