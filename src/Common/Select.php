<?php

namespace QueryFactory\Common;

use QueryFactory\Common\Interfaces\Select as InterfacesSelect;

abstract class Select extends Query implements InterfacesSelect
{
    protected bool $distinct = false;
    protected int|string $top;
}
