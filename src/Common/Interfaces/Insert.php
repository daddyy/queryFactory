<?php

namespace QueryFactory\Common\Interfaces;

use QueryFactory\Common\Interfaces\Query;
use QueryFactory\Common\Interfaces\Parts\Column;
use QueryFactory\Common\Interfaces\Parts\Join;

interface Insert extends Query, Column
{
}
