<?php

namespace QueryFactory\Common\Interfaces\Parts;

use QueryFactory\Common\Interfaces\Select;

interface GroupBy
{
    public function resetGroupBy(): Select;
    public function groupBy(array $groupBy): Select;
    public function buildGroupBy(): string;
}
