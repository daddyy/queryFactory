<?php

namespace QueryFactory\Common\Interfaces;

use QueryFactory\Common\Interfaces\Query;
use QueryFactory\Common\Interfaces\Parts\Condition;
use QueryFactory\Common\Interfaces\Parts\Join;
use QueryFactory\Common\Interfaces\Parts\Limit;

interface Delete extends Query, Limit, Condition, Join
{
}
