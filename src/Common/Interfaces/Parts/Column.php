<?php

namespace QueryFactory\Common\Interfaces\Parts;

use QueryFactory\Common\Interfaces\Insert;
use QueryFactory\Common\Interfaces\Select;
use QueryFactory\Common\Interfaces\Update;

interface Column
{
    public function resetColumns(): Select|Update|Insert;
    public function col(string $column, array $bindValues = []): Select|Update|Insert;
    public function cols(array $columns): Select|Update|Insert;
    public function buildCols(): string;
}
