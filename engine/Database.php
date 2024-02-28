<?php
/**
 * Database Manager
 */
class Database
{
    private static $host = DATABASE_HOST;
    private static $username = DATABASE_USER;
    private static $password = DATABASE_PASS;
    private static $database = DATABASE_NAME;
    private static $connection;

    /**
     * Connects to database
     *
     * @return void
     */
    public static function connect(): void
    {
        self::$connection = new \mysqli(self::$host, self::$username, self::$password, self::$database);

        if (self::$connection->connect_error) {
            die("Connection failed: " . self::$connection->connect_error);
        }
    }

    /**
     * Queries the database
     *
     * @param string $sql
     * @return mixed
     */
    public static function query(string $sql): mixed
    {
        try {
            self::connect();
            return self::$connection->query($sql);
        } catch (Exception $e) {
            return null;

        }
    }

    /**
     * Queries the database for a single result
     *
     * @param string $sql
     * @return mixed
     */
    public static function fetch(string $sql): mixed
    {
        try {
            $result = self::query($sql);

            if ($result && $result->num_rows > 0) {
                $row = $result->fetch_object();
                return $row;
            }

            return null;
        } catch (Exception $e) {
            return null;

        }
    }

    /**
     * Queries the database for multiple results
     *
     * @param string $sql
     * @return mixed | bool | mysqli_result
     */
    public static function fetchAll(string $sql): mixed
    {
        $result = self::query($sql);

        $data = [];
        if ($result && $result->num_rows > 0) {
            while ($row = $result->fetch_assoc()) {
                $data[] = $row;
            }
        }

        return $data;
    }

    /**
     * Inserts data to database
     *
     * @param string $table
     * @param array $data
     * @return mixed|bool|mysqli_result
     */
    public static function insert(string $table, array $data): mixed
    {
        $keys = implode(', ', array_keys($data));
        $values = "'" . implode("', '", array_values($data)) . "'";
        $sql = "INSERT INTO $table ($keys) VALUES ($values)";

        return self::query($sql);
    }

    /**
     * Undocumented function
     *
     * @param string $table
     * @param array $data
     * @param array $conditions
     * @param string $condition
     * @return mixed|bool|mysqli_result
     */
    public static function update(string $table, array $data, $conditions = [], $condition = ' AND '): mixed
    {
        $setClause = [];
        foreach ($data as $key => $value) {
            $setClause[] = "$key = '$value'";
        }
        $setClause = implode(', ', $setClause);

        $whereClause = [];
        foreach ($conditions as $key => $value) {
            $whereClause[] = "$key = '$value'";
        }
        $whereClause = implode($condition, $whereClause);

        $sql = "UPDATE $table SET $setClause WHERE $whereClause";

        return self::query($sql);
    }

    /**
     * Gets data from database
     *
     * @param string $table
     * @param string $columns
     * @param string $condition
     * @return void
     */
    public static function select(string $table, $columns = '*', $condition = ''): mixed
    {
        $sql = "SELECT $columns FROM $table";
        if (!empty($condition)) {
            $sql .= " WHERE $condition";
        }

        return self::fetch($sql);
    }

    /**
     * Deletes data from database
     *
     * @param string $table
     * @param array $conditions
     * @param string $condition
     * @return mixed
     */
    public static function delete(string $table, $conditions = [], $condition = ' AND '): mixed
    {
        $whereClause = [];
        foreach ($conditions as $key => $value) {
            $whereClause[] = "$key = '$value'";
        }
        $whereClause = implode($condition, $whereClause);

        $sql = "DELETE FROM $table WHERE $whereClause";

        return self::query($sql);
    }

    /**
     * Escapes special characters in a string
     *
     * @param string $string
     * @return string
     */
    public static function real_escape_string(string $string): string
    {
        return self::$connection->real_escape_string($string);
    }

    /**
     * Disconnects from database
     *
     * @return void
     */
    public static function disconnect(): void
    {
        if (self::$connection) {
            self::$connection->close();
        }
    }
}
