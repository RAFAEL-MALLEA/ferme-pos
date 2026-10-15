<?php

namespace Config;

use CodeIgniter\Database\Config;

/**
 * Nota de la reconstruccion:
 * El proyecto original fallaba en logs reales con:
 *   "Error connecting to the database: Unknown database 'ferme'"
 *   "Access denied for user '****'@'localhost' (using password: NO)"
 * Es decir, el .env nunca tenia credenciales validas cargadas.
 * Aqui TODO se lee desde .env (nunca hardcodeado), y DBDebug se deja
 * en true para que un error de conexion se vea de inmediato en vez
 * de fallar en silencio mas adelante.
 */
class Database extends Config
{
    public string $defaultGroup = 'default';
public string $filesPath = APPPATH . 'Database' . DIRECTORY_SEPARATOR;
    public array $default = [
        'DSN'          => '',
        'hostname'     => 'localhost',
        'username'     => '',
        'password'     => '',
        'database'     => 'ferme',
        'DBDriver'     => 'MySQLi',
        'DBPrefix'     => '',
        'pConnect'     => false,
        'DBDebug'      => true,
        'charset'      => 'utf8mb4',
        'DBCollat'     => 'utf8mb4_general_ci',
        'swapPre'      => '',
        'encrypt'      => false,
        'compress'     => false,
        'strictOn'     => false,
        'failover'     => [],
        'port'         => 3306,
        'foreignKeys'  => true,
        'busyTimeout'  => 1000,
    ];

    public array $tests = [
        'DSN'         => '',
        'hostname'    => '127.0.0.1',
        'username'    => '',
        'password'    => '',
        'database'    => ':memory:',
        'DBDriver'    => 'SQLite3',
        'DBPrefix'    => 'db_',
        'pConnect'    => false,
        'DBDebug'     => true,
        'charset'     => 'utf8',
        'DBCollat'    => '',
        'swapPre'     => '',
        'encrypt'     => false,
        'compress'    => false,
        'strictOn'    => false,
        'failover'    => [],
        'port'        => 3306,
        'foreignKeys' => true,
    ];

    public function __construct()
    {
        parent::__construct();
    }
}
