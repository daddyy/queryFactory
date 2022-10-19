<?php

namespace QueryFactory\Common\Interfaces;

use QueryFactory\Common\Interfaces\Parts\Column;
use QueryFactory\Common\Interfaces\Parts\Condition;
use QueryFactory\Common\Interfaces\Parts\Join;
use QueryFactory\Common\Interfaces\Parts\Limit;
use QueryFactory\Common\Interfaces\Parts\OrderBy;

interface Update extends Query, Column, Limit, Condition, Join, OrderBy
{
}
