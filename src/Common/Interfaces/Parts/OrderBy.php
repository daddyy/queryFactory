<?php

namespace QueryFactory\Common\Interfaces\Parts;

use QueryFactory\Common\Interfaces\Select;

interface OrderBy
{
    public function resetOrderBy(): Select;
    public function orderBy(array $order): self;
    public function buildOrderBy(): string;
}
