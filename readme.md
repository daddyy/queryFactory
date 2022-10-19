# Query Factory

## install
```shell
composer install
composer update
```

## init usage
```php
require_once(__DIR__ . DIRECTORY_SEPARATOR . '..' . DIRECTORY_SEPARATOR . 'vendor' . DIRECTORY_SEPARATOR . 'autoload.php');

use QueryFactory\QueryFactory;
$qf = new QueryFactory('mysql');
```

### Common object
Common method for all statements insert/update/select/delete

- ***$queryObject->table(string $tableName): self*** set the name of table (file) for storage
- ***$queryObject->getStatement(): string*** return the string of statement for execution
- ***$queryObject->getBindValues(): array*** return the array of bind values

### Select
- ***$queryObject->col(string $column, array $bindValues): self***
- ***$queryObject->cols(array $columns, array $bindValues): self***
