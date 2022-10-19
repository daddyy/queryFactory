<?php

namespace QueryFactory\Interfaces;

interface QueryQuoter
{
    /**
     * Quote prefix
     * @example *`*table`.*`*tableColumn`
     * @return string
     */
    public function getNamePrefix(): string;
    /**
     * Quote suffix
     * @example `table*`*.`tableColumn*`*
     * @return string
     */
    public function getNameSuffix(): string;
    /**
     * Quote string
     * @example *`*table*`*.*`*tableColumn*`*
     */
    public function quote(string $string): string;
}
