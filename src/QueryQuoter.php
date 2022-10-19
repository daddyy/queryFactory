<?php

declare(strict_types=1);

namespace QueryFactory;

use QueryFactory\Common\Exceptions\DriverException;
use QueryFactory\Interfaces\QueryQuoter as InterfacesQueryQuoter;

class QueryQuoter implements InterfacesQueryQuoter
{
    public function __construct(private ?string $nameSuffix = null, private ?string $namePrefix = null)
    {
    }

    public function setQuotesByDriver(string $driver): self
    {
        switch (strtolower($driver)) {
            case 'mysql':
                $prefix = $suffix = '`';
                break;
            case 'sqlsrv':
                $prefix = "[";
                $suffix = ']';
                break;
            case 'sqlite':
                $prefix = $suffix = '"';
                break;
            case 'pgsql':
                $prefix = $suffix = '"';
                break;
            default:
                throw new DriverException("Unrecognize driver ($driver) use setters");
        }
        $this->setNamePrefix($prefix)->setNameSuffix($suffix);
        return  $this;
    }

    public function quote(string $string): string
    {
        return $string;
    }

    /**
     * @return string
     */
    public function getNameSuffix(): string
    {
        return $this->nameSuffix;
    }

    /**
     * @param string $nameSuffix 
     * @return QueryQuoter
     */
    public function setNameSuffix(string $nameSuffix): self
    {
        $this->nameSuffix = $nameSuffix;
        return $this;
    }

    /**
     * @return string
     */
    public function getNamePrefix(): string
    {
        return $this->namePrefix;
    }

    /**
     * @param string $namePrefix 
     * @return QueryQuoter
     */
    public function setNamePrefix(string $namePrefix): self
    {
        $this->namePrefix = $namePrefix;
        return $this;
    }
}
