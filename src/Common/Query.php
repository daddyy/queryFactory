<?php

declare(strict_types=1);

namespace QueryFactory\Common;

use Exception;
use QueryFactory\Common\Interfaces\Query as InterfacesQuery;
use QueryFactory\Common\Interfaces\Delete;
use QueryFactory\Common\Interfaces\Insert;
use QueryFactory\Common\Interfaces\Parts\Condition;
use QueryFactory\Common\Interfaces\Select;
use QueryFactory\Common\Interfaces\Update;
use QueryFactory\QueryQuoter;

abstract class Query implements InterfacesQuery
{
    protected string $statement;
    protected string $table = '';
    protected array $bindValues = [];
    protected array $joins = [];
    protected array $limits = [];
    protected array $groupBy = [];
    protected array $conditions = [];
    protected array $columns = [];
    protected array $orderBy = [];

    public function __construct(private ?QueryQuoter $queryQuoter = null)
    {
    }

    public function __toString()
    {
        return $this->getStatement();
    }

    public function getQueryQuoter(): ?QueryQuoter
    {
        return  $this->queryQuoter;
    }

    private function resetByPropertyName(string $propertyName): Select|Update|Insert|Delete
    {
        $this->{$propertyName} = [];
        return $this;
    }

    private function addByPropertyName(string $propertyName, string $propertyValue, array $bindValues = []): self
    {
        $this->{$propertyName}[] = $this->queryQuoter->quote(
            $this->addBindValues(
                $propertyValue,
                $bindValues
            )
        );
        return $this;
    }

    protected function addBindValues(string $propertyName, array $bindValues = []): string
    {
        return $this->addBindsAndgetPropertyName($propertyName, $bindValues);
    }

    private function convertBindValueByType(string $bindType, string|int|float|array|bool $bindValue)
    {
        return match ($bindType) {
            'string' => strval($bindValue),
            'int' => intval($bindValue),
            'bool' => boolval($bindValue),
            'float' => floatval($bindValue),
            'array' => (array) $bindValue,
            default => $bindValue,
        };
    }

    protected function addBindsAndgetPropertyName(string $propertyName, array $bindValues): string
    {
        $parts = [];
        $incrementBind = 0;
        $increment = count($this->bindValues);
        $partsFromString = preg_split('/(%\?|%s|%i|%b|%f|%a)/', $propertyName, -1, PREG_SPLIT_DELIM_CAPTURE);
        foreach ($partsFromString as $part) {
            $typeOf = $this->getTypeTheOfBind($part);
            if (is_null($typeOf)) {
                $parts[] = $part;
            } elseif ($typeOf) {
                $parts[] = $bindName = ':_bind_' . $typeOf . '_' . $increment++;
                $this->bindValues[$bindName] = $this->convertBindValueByType($typeOf, $bindValues[$incrementBind]);
                $incrementBind++;
            }
        }
        return join('', $parts);
    }

    private function getTypeTheOfBind(string $bindType): ?string
    {
        return match ($bindType) {
            '%s' => 'string',
            '%i' => 'int',
            '%b' => 'bool',
            '%f' => 'float',
            '%a' => 'array',
            '%?' => 'default',
            default => null
        };
    }

    protected function getValueAndBindValues(array|string $params): array
    {
        if (is_array($params)) {
            if (count($params) > 2 || empty($params)) {
                throw new Exception("Error Processing Request", 1);
            }
            return [
                'value' => reset($params),
                'bindValues' => count($params) == 2 ? end($params) : [],

            ];
        } else {
            return [
                'value' => $params,
                'bindValues' => [],
            ];
        }
    }

    public function table(string $table): Select|Insert|Update|Delete
    {
        $this->table = $table;
        return $this;
    }

    protected function checkProperty(array $callbacks, string $propertyName): bool
    {
        $result = [];
        $values = $this->$propertyName;
        foreach ($callbacks as $callback) {
            switch ($callback) {
                case 'empty':
                    $result[$callback] = empty($values);
                    break;

                default:
                    $result[$callback] = call_user_func_array($callback, $values);
                    break;
            }
        }
        return !in_array(false, $result);
    }

    public function resetColumns(): Select|Update|Insert
    {
        return $this->resetByPropertyName('columns');
    }

    public function resetLimit(): Select|Update|Delete
    {
        return $this->resetByPropertyName('limits');
    }
    public function resetConditions(): Select|Update|Delete
    {
        return $this->resetByPropertyName('conditions');
    }

    public function resetGroupBy(): Select
    {
        return $this->resetByPropertyName('groupBy');
    }

    public function resetOrderBy(): Select|Update|Delete
    {
        return $this->resetByPropertyName('orderBy');
    }

    public function resetJoins(): Select|Update|Insert
    {
        return $this->resetByPropertyName('joins');
    }

    public function col(string $column, array $bindValues = []): Select|Update|Insert
    {
        return $this->addByPropertyName('columns', $column, $bindValues);
    }

    public function limit(string $limit): Select|Update|Delete
    {
        return $this->addByPropertyName('limits', $limit);
    }

    public function condition(string $condition, array $bindValues = []): Select|Update|Delete
    {
        return $this->addByPropertyName('conditions', $condition, $bindValues);
    }

    public function groupBy(array $groupBy): Select
    {
        $this->groupBy = $groupBy;
        return $this;
    }

    public function orderBy(array $orderBy): Select|Update|Delete
    {
        $this->orderBy = $orderBy;
        return $this;
    }

    public function join(string $table, array $conditions, string $typeJoin = 'inner'): Select|Update|Insert
    {
        $this->joins[] = [
            'type' => $typeJoin,
            'table' => $table,
            'conditions' => $conditions
        ];
        return $this;
    }

    public function cols(array $columns): Select|Update|Insert
    {
        foreach ($columns as $columnValue) {
            $value = $this->getValueAndBindValues($columnValue);
            $this->col($value['value'], $value['bindValues']);
        }
        return $this;
    }

    public function limits(array $limits): Select|Update|Delete
    {
        foreach ($limits as $limit) {
            $this->limit($limit);
        }
        return $this;
    }

    public function conditions(array $conditions): Select|Delete|Update
    {
        foreach ($conditions as $conditionValue) {
            $value = $this->getValueAndBindValues($conditionValue);
            $this->condition($value['value'], $value['bindValues']);
        }
        return $this;
    }

    public function joins(array $joins): Select|Update|Insert
    {
        foreach ($joins as $table => $conditions) {
            $this->join($table, $conditions);
        }
        return $this;
    }

    public function getStatement(): string
    {
        return $this->statement;
    }

    public function getBindValues(): array
    {
        return $this->bindValues;
    }

    protected function joinValues(string $glue, array $array, string $prefix = ''): string
    {
        return (count($array) ? $prefix : '') . join($glue, $array);
    }

    protected function setStatement(string $string): self
    {
        $this->statement = $string;
        return $this;
    }
}
