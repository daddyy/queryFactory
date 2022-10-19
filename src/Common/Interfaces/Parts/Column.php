<?php

namespace QueryFactory\Common\Interfaces\Parts;

use QueryFactory\Common\Interfaces\Insert;
use QueryFactory\Common\Interfaces\Select;
use QueryFactory\Common\Interfaces\Update;

interface Column
{
    /**
     * reset the columns
     *
     * @return Select|Update|Insert
     */
    public function resetColumns(): Select|Update|Insert;
    /**
     * add the column to object
     * @example col('TABLE_COLUMN')
     * @example col('TABLE_COLUMN as NAME')
     * @example col(['concat(%i, %s, TABLE_COLUMN) as NAME', [10, 50]])
     *
     * @param string $column
     * @param array $bindValues
     * @return Select|Update|Insert
     */
    public function col(string $column, array $bindValues = []): Select|Update|Insert;
    /**
     * array of columns
     * @see col()
     *
     * @param array $columns
     * @return Select|Update|Insert
     */
    public function cols(array $columns): Select|Update|Insert;
    /**
     * from the object columns prepare and build the SELECT clause
     *
     * @return string
     */
    public function buildCols(): string;
}
