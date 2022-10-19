<?php

declare(strict_types=1);

namespace QueryFactory\Sql;

use QueryFactory\Common\Exceptions\DataException;
use QueryFactory\Common\Interfaces\Select as InterfacesSelect;
use QueryFactory\Common\Select as CommonSelect;

class Select extends CommonSelect implements InterfacesSelect
{
    public function build(): self
    {
        $build = [
            $this->buildCols(),
            $this->buildFrom(),
            $this->buildJoins(),
            $this->buildConditions(),
            $this->buildGroupBy(),
            $this->buildOrderBy(),
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

    public function buildGroupBy(): string
    {
        return $this->joinValues(',', $this->groupBy, 'GROUP BY ');
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
        return $this->joinValues(',', $this->columns, 'SELECT ' . $this->buildColsExtension());
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
        return 'FROM ' . $this->table;
    }

    public function buildColsExtension(): string
    {
        return $this->joinValues(' ', [
            $this->distinct ? 'DISTINCT' : null,
            !empty($this->top) ? $this->buildTop() : null,
        ]);
    }

    protected function buildTop(): string
    {
        if (substr($this->top, -1) == "%") {
            return $this->top ?  'TOP( ' . rtrim($this->top, "%") . ')PERCENT' : null;
        }
        return $this->top ?  'TOP( ' . $this->top . ')' : null;
    }

    public function distinct(): self
    {
        $this->distinct = true;
        return $this;
    }

    public function top(int|string $top): self
    {
        $this->top = $top;
        return $this;
    }
}
