<?php declare(strict_types=1);

namespace Ajrockr\PdoDbm\Database\Drivers;

use Ajrockr\PdoDbm\Database\DatabaseConfig;
use PDO;

interface DatabaseDriverInterface {
    public function connect(DatabaseConfig $config): PDO;
}