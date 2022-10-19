<?php

declare(strict_types=1);

namespace QueryFactory\Sql;

use QueryFactory\Common\Exceptions\DataException;
use QueryFactory\Common\Interfaces\Update as InterfacesUpdate;
use QueryFactory\Common\Update as CommonUpdate;

abstract class Update extends CommonUpdate implements InterfacesUpdate
{
    public function build(): self
    {
        $build = [
            $this->buildFrom(),
            $this->buildJoins(),
            $this->buildCols(),
            $this->buildConditions(),
            $this->buildLimit(),
        ];
        $build = array_filter($build);
        $this->statement = $this->joinValues("\n", $build);
        return $this;
    }

    public function buildJoins(): string
    {
        $joins = [];
        foreach ($this->joins as $join) {
            $joins[] = $this->buildJoin($join);
        }
        return $this->joinValues("\n", $joins);
    }

    public function buildJoin(array $join): string
    {
        $conditions = [];
        foreach ($join['conditions'] as $condition) {
            $condition = $this->getValueAndBindValues($condition);
            $condition = $this->addBindValues($condition['value'], $condition['bindValues']);
            $condition = $this->getQueryQuoter()->quote($condition);
            $conditions[] =  '(' . $condition . ')';
        }
        $join = $this->joinValues(
            "\n\n",
            [
                $this->joinValues('', [$join['type'] . ' JOIN ', $join['table']]),
                $this->joinValues(' AND ', $conditions, ' ON ')
            ]
        );

        return $join;
    }

    public function buildOrderBy(): string
    {
        return $this->joinValues(',', $this->orderBy, 'ORDER BY ');
    }

    public function buildCols(): string
    {
        if ($this->checkProperty(['empty'], 'columns')) {
            throw new DataException("!Cols cannot be empty");
        }
        return $this->joinValues(',', $this->columns, 'SET ');
    }

    public function buildConditions(): string
    {
        $conditions = [];
        foreach ($this->conditions as $condtion) {
            $conditions[] = '(' . $condtion . ')';
        }
        return ($this->joinValues("\n\tAND ", $conditions, 'WHERE '));
    }

    public function buildLimit(): string
    {
        return $this->joinValues(", ", $this->limits);
    }

    public function buildFrom(): string
    {
        return 'UPDATE ' . $this->table;
    }
}
