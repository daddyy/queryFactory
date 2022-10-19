<?php

namespace QueryFactory\Common\Interfaces\Parts;

use QueryFactory\Common\Interfaces\Delete;
use QueryFactory\Common\Interfaces\Select;
use QueryFactory\Common\Interfaces\Update;

interface OrderBy
{
    public function resetOrderBy(): Select|Update|Delete;
    public function orderBy(array $order): Select|Update|Delete;
    public function buildOrderBy(): string;
}
