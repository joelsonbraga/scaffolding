<?php
/**
 *
 * Database connection class.
 * Implement a singleton design pattern
 * @author Joelson Braga <joelsonbraga@gmail.com>
 * @since v0.0.1
 */
namespace Lib\DataBase;

use PDO;

final class ConnectionDatabase
{
    /**
     * @var null
     */
    private static $instance = null;

    private function __construct()
    {
    }

    private function __clone()
    {
    }

    /**
     * @return PDO|void
     */
    private static function connect()
    {
        try {
            $options = [
                PDO::ATTR_PERSISTENT => true,
                PDO::ATTR_DEFAULT_FETCH_MODE => PDO::FETCH_OBJ,
                PDO::ATTR_ERRMODE => PDO::ERRMODE_EXCEPTION
            ];
            $string = 'aG9zdD1iZHBsYXRmb3JtLnN5c3RheC5jb20uYnIgcG9ydD01NDMyIGRibmFtZT1TeXN0YXhTUEVEX0Rlc2VudiB1c2VyPXNwZWRAc3BlZDMgcGFzc3dvcmQ9QCQoISV2YmZ5ZWY3NA==';
            $dsn = 'pgsql:' . str_replace(' ', ';', base64_decode($string));

            $conn = new PDO($dsn, null, null, $options);

        } catch(\PDOException $e) {
            echo 'ERROR: ' . $e->getMessage();
        }

        return $conn;
    }

    /**
     * @return PDO|null
     */
    public static function getInstance()
    {
        if (self::$instance === null) {
            self::$instance = self::connect();
        }

        return self::$instance;
    }

}