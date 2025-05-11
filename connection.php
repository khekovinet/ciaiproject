<?php
class Connection {
    private static $instance;

    public static function get() {
        if (!isset(self::$instance)) {
            self::$instance = new self();
        }
        return self::$instance;
    }

    public function connect() {
        $params = parse_ini_file('db.ini');
        if ($params === false) {
            throw new Exception("Файл db.ini не найден или поврежден");
        }

        // Формируем DSN правильно
        $dsn = sprintf(
            "pgsql:host=%s;port=%d;dbname=%s",
            $params['host'],
            $params['port'],
            $params['database']
        );

        try {
            $pdo = new PDO(
                $dsn,
                $params['user'],
                $params['password'],
                [
                    PDO::ATTR_ERRMODE => PDO::ERRMODE_EXCEPTION,
                    PDO::ATTR_PERSISTENT => false
                ]
            );
            return $pdo;
        } catch (PDOException $e) {
            throw new Exception("Ошибка подключения: " . $e->getMessage());
        }
    }
}
?>