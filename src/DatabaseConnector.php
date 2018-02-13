<?php

namespace Papertowel;

/**
 * Class DatabaseConnector
 * @package Papertowel
 */
class DatabaseConnector
{
    /**
     * @return \PDO
     */
    public static function createPdoConnection()
    {
        $dbHost = array_merge(['port' => 3306], parse_url(getenv('DATABASE_URL')));

        $password = $dbHost['pass'];
        $connectionString = self::buildConnectionString($dbHost);
        try {
            $conn = new \PDO('mysql:' . $connectionString, $dbHost['user'], $password);
            $conn->setAttribute(\PDO::ATTR_ERRMODE, \PDO::ERRMODE_EXCEPTION);
            $conn->setAttribute(\PDO::ATTR_DEFAULT_FETCH_MODE, \PDO::FETCH_ASSOC);
        } catch (\PDOException $e) {
            $message = $e->getMessage();
            $message = str_replace(
                [
                    getenv('DATABASE_USER'),
                    getenv('DATABASE_PASSWORD'),
                ],
                '******',
                $message
            );
            throw new \RuntimeException('Could not connect to database. Message from SQL Server: ' . $message, $e->getCode());
        }
        return $conn;
    }
    private static function buildConnectionString(array $dbHost): string
    {
        $settings = [
            'host=' . $dbHost['host'],
        ];
        $settings[] = 'port=' . $dbHost['port'];
        $settings[] = 'dbname=' . substr($dbHost['path'], 1);
        return implode(';', $settings);
    }
}
