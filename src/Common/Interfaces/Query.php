<?php

namespace QueryFactory\Common\Interfaces;

interface Query
{
    public function table(string $table): Select|Insert|Update|Delete;
    public function getStatement(): string;
    public function getBindValues(): array;
    public function build(): Select|Insert|Update|Delete;
}
