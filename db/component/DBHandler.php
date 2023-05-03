<?php

/**
 * DBHandler.php - class assists in managing frequent mysqli operations
 * Written by: James West - westj4@csp.edu - April, 2023
 */
class DBHandler
{
    /**
     * @var mysqli
     */
    private mysqli $conn;
    /**
     * @var bool
     */
    private bool $connected;
    /**
     * @var string|null
     */
    private ?string $hostname;
    /**
     * @var string|null
     */
    private ?string $username;
    /**
     * @var string|null
     */
    private ?string $password;
    /**
     * @var string|null
     */
    private ?string $database;

    /**
     * @param string|null $hostname
     * @param string|null $userName
     * @param string|null $password
     * @param string|null $database
     */
    public function __construct(
        ?string $hostname = NULL,
        ?string $userName = NULL,
        ?string $password = NULL,
        ?string $database = NULL
    )
    {
        $this->hostname = $hostname;
        $this->username = $userName;
        $this->password = $password;
        $this->database = $database;
        $conn = new mysqli(
            $hostname,
            $userName,
            $password,
            $database
        );

        if ($conn->connect_error) {
            die("Connection Error: $conn->connect_error");
        }
        $this->conn = $conn;
        $this->connected = true;
    }


    /**
     * @return void
     */
    public function closeConnection(): void
    {
        $this->conn->close();
        $this->connected = false;
    }


    /**
     * @param string $dbName
     * @return bool
     */
    public function createDatabase(string $dbName): bool
    {
        $sql = "CREATE DATABASE IF NOT EXISTS $dbName";
        $result = $this->conn->query($sql);
        if (!$this->conn->error) {
            $this->conn->select_db($dbName);
        }
        return $result;
    }

    /**
     * @param string $dbName
     * @return bool
     */
    public function databaseExists(string $dbName): bool
    {
        $sql = "SELECT schema_name FROM information_schema.schemata WHERE SCHEMA_NAME LIKE '$dbName'";
        $result = $this->conn->query($sql);
        return $this->conn->query($sql)->num_rows;
    }

    /**
     * @param $result
     * @return string
     */
    public function displayQuerySuccess($result): string
    {
        return $result ? "Success" : "Failed: {$this->conn->error}";
    }


    /**
     * @param string $dbName
     * @return bool
     */
    public function dropDatabase(string $dbName): bool
    {
        $sql = "DROP DATABASE $dbName";
        return $this->conn->query($sql);
    }

    /**
     * @return mysqli
     */
    public function getConn(): mysqli
    {
        return $this->conn;
    }


    /**
     * @return bool
     */
    public function isConnected(): bool
    {
        return $this->connected;
    }

    /**
     * @return void
     */
    public function openConnection(): void
    {
        $this->conn = new mysqli($this->hostname, $this->username, $this->password, $this->database);
        if ($this->conn->connect_error) {
            die("Connection Error: {$this->conn->connect_error}");
        }
        $this->connected = true;
    }

    /**
     * @return mysqli
     */
    public function resetConn(): mysqli
    {
        if ($this->isConnected()) {
            $this->conn->close();
        }
        $this->openConnection();
        return $this->conn;
    }
}
