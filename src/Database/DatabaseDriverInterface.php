<?php declare(strict_types=1);

namespace Arizzo\PdoDbm\Database;

use PDO;
interface DatabaseDriverInterface {
    public function connect(DatabaseConfig $config): PDO;
}