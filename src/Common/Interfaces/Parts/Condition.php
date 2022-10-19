<?php

namespace QueryFactory\Common\Interfaces\Parts;

use QueryFactory\Common\Interfaces\Delete;
use QueryFactory\Common\Interfaces\Select;
use QueryFactory\Common\Interfaces\Update;

interface Condition
{
    public function resetConditions(): Select|Update|Delete;
    public function conditions(array $array): Select|Update|Delete;
    public function condition(string $string, array $bindValues = []): Select|Update|Delete;
    public function buildConditions(): string;
}
