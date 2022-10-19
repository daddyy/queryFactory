<?php

namespace QueryFactory\Common\Interfaces\Parts;

use QueryFactory\Common\Interfaces\Delete;
use QueryFactory\Common\Interfaces\Select;
use QueryFactory\Common\Interfaces\Update;

interface Limit
{
    public function resetLimit(): Select|Update|Delete;
    public function limit(string $limit): Select|Update|Delete;
    public function limits(array $limits): Select|Update|Delete;
    public function buildLimit(): string;
}
