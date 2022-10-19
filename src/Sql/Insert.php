<?php

declare(strict_types=1);

namespace QueryFactory\Sql;

use QueryFactory\Common\Exceptions\DataException;
use QueryFactory\Common\Insert as CommonInsert;

/**
 * @todo insert select
 */
abstract class Insert extends CommonInsert
{
    public function build(): self
    {
        $build = [
            $this->buildFrom(),
            $this->buildCols()
        ];
        $build = array_filter($build);
        $this->statement = $this->joinValues("\n", $build);
        return $this;
    }

    /**
     * @todo make it better :-)
     *
     * @return string
     */
    public function buildCols(): string
    {
        if ($this->checkProperty(['empty'], 'columns')) {
            throw new DataException("!Cols cannot be empty");
        }
        $values = $keys = [];
        foreach ($this->columns as $column) {
            $column = explode('=', $column);
            $keys[] = reset($column);
            $values[] = end($column);
        }

        return '(' . $this->joinValues(',', $keys) . ') VALUES(' . $this->joinValues(',', $values) . ')';
    }

    public function buildFrom(): string
    {
        return 'INSERT INTO ' . $this->table;
    }
}
