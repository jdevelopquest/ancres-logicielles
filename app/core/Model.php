<?php
declare(strict_types=1);

namespace App\Core;

use App\CoreUtils\Database\Connection;
use App\CoreUtils\Database\Database;

class Model
{
    protected ?Database $db = null;

    public function __construct()
    {
        $databaseMariaDB = require CONFIG_PATH . "databaseMariaDB.php";
        $connection = new Connection($databaseMariaDB);
        $this->db = new Database($connection->getPDO());
    }
}
