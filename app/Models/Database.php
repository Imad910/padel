<?php

class Database
{
        private static $connection;

        public static function getConnection()
        {
                if (!self::$connection) {
                        self::$connection = new mysqli('localhost', 'root', '', 'padel');
                        if (self::$connection->connect_error) {
                                throw new RuntimeException('Connexion à la base de données impossible.');
                        }
                        self::$connection->set_charset('utf8mb4');
                }

                return self::$connection;
        }
}
