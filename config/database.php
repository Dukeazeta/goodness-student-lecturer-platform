<?php

$localConfig = __DIR__ . '/database.local.php';
if (is_file($localConfig)) {
    return require $localConfig;
}

$databaseUrl = getenv('DATABASE_URL') ?: '';
$parsedUrl = $databaseUrl ? parse_url($databaseUrl) : false;

if ($parsedUrl && isset($parsedUrl['scheme']) && in_array($parsedUrl['scheme'], ['mysql', 'mariadb'], true)) {
    $query = [];
    if (!empty($parsedUrl['query'])) {
        parse_str($parsedUrl['query'], $query);
    }

    return [
        'host' => $parsedUrl['host'] ?? '127.0.0.1',
        'port' => isset($parsedUrl['port']) ? (int) $parsedUrl['port'] : 3306,
        'database' => isset($parsedUrl['path']) ? ltrim($parsedUrl['path'], '/') : 'goodness_platform',
        'username' => isset($parsedUrl['user']) ? urldecode($parsedUrl['user']) : 'root',
        'password' => isset($parsedUrl['pass']) ? urldecode($parsedUrl['pass']) : '',
        'charset' => $query['charset'] ?? 'utf8mb4',
    ];
}

function env_or_default(array $keys, $default)
{
    foreach ($keys as $key) {
        $value = getenv($key);
        if ($value !== false && $value !== '') {
            return $value;
        }
    }

    return $default;
}

return [
    'host' => env_or_default(['DB_HOST', 'MYSQL_HOST', 'PXXL_DB_HOST'], '127.0.0.1'),
    'port' => (int) env_or_default(['DB_PORT', 'MYSQL_PORT', 'PXXL_DB_PORT'], 3307),
    'database' => env_or_default(['DB_DATABASE', 'DB_NAME', 'MYSQL_DATABASE', 'MYSQL_DB', 'PXXL_DB_NAME'], 'goodness_platform'),
    'username' => env_or_default(['DB_USERNAME', 'DB_USER', 'MYSQL_USERNAME', 'MYSQL_USER', 'PXXL_DB_USER'], 'root'),
    'password' => env_or_default(['DB_PASSWORD', 'MYSQL_PASSWORD', 'PXXL_DB_PASSWORD'], ''),
    'charset' => env_or_default(['DB_CHARSET'], 'utf8mb4'),
];
