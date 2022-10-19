<?php

namespace QueryFactory\Common\Interfaces;

use QueryFactory\Common\Interfaces\Parts\Column;
use QueryFactory\Common\Interfaces\Parts\Condition;
use QueryFactory\Common\Interfaces\Parts\Distinct;
use QueryFactory\Common\Interfaces\Parts\GroupBy;
use QueryFactory\Common\Interfaces\Parts\Join;
use QueryFactory\Common\Interfaces\Parts\Limit;
use QueryFactory\Common\Interfaces\Parts\OrderBy;

interface Select extends Query, Column, Limit, Condition, Join, OrderBy, GroupBy
{
    public function distinct(): Select;
    public function top(int|string $top): Select;
}
