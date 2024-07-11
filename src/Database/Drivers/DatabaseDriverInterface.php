<?php declare(strict_types=1);

namespace Arizzo\PdoDbm\Database\Drivers;

use Arizzo\PdoDbm\Database\DatabaseConfig;
use PDO;

interface DatabaseDriverInterface {
    public function connect(DatabaseConfig $config): PDO;
}