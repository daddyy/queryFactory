<?php

namespace QueryFactory\Interfaces;

interface QueryQuoter
{
    public function getNamePrefix(): string;
    public function getNameSuffix(): string;
    public function quote(string $string): string;
}
