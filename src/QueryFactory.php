<?php

namespace QueryFactory;

use QueryFactory\Common\Interfaces\Delete;
use QueryFactory\Common\Interfaces\Insert;
use QueryFactory\Common\Interfaces\Query;
use QueryFactory\Common\Interfaces\Select;
use QueryFactory\Common\Interfaces\Update;

class QueryFactory
{
    public function __construct(private string $driver, private ?QueryQuoter $queryQuoter = null)
    {
        if (is_null($queryQuoter)) {
            $this->queryQuoter = new QueryQuoter();
            $this->queryQuoter->setQuotesByDriver($driver);
        }
    }

    public function select(): Select
    {
        return $this->getQueryObjectByDriver('select');
    }

    public function update(): Update
    {
        return $this->getQueryObjectByDriver('update');
    }
    public function insert(): Insert
    {
        return $this->getQueryObjectByDriver('update');
    }

    public function delete(): Delete
    {
        return $this->getQueryObjectByDriver('delete');
    }

    private function getQueryObjectByDriver(string $queryType): Select|Insert|Delete|Update
    {
        $driver = $this->getDriver();
        return new (__NAMESPACE__ . '\\' .  ucfirst($driver) . '\\' . ucfirst($queryType))($this->queryQuoter);
    }

    private function getDriver(): string
    {
        return $this->driver;
    }
}
