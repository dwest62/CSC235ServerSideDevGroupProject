<?php

class DBHandler
{
    private mysqli $conn;
    private bool $connected;
    private ?string $hostname;
    private ?string $username;
    private ?string $password;
    private ?string $database;
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
            self::getParam($hostname, "host"),
            self::getParam($userName, "user"),
            self::getParam($password, "password"),
            $database
        );

        if($conn->connect_error) {
            die("Connection Error: $conn->connect_error");
        }
        $this->conn = $conn;
        $this->connected = true;
    }

    public function addProcedure($sql): string
    {
        if(!$this->connected) {$this->openConnection();}
        return $this->conn->query($sql) ? "Success" : "Failed: {$this->conn->error}";
    }



    public function openConnection(): void
    {
        $this->conn= new mysqli($this->hostname, $this->username, $this->password, $this->database);
        if($this->conn->connect_error) {
            die("Connection Error: {$this->conn->connect_error}");
        }
        $this->connected = true;
    }

    public function closeConnection():void
    {
        $this->conn->close();
        $this->connected = false;
    }
    public function createDatabase(string $dbName): bool
    {

        $sql = "CREATE DATABASE IF NOT EXISTS $dbName";
        if(!$this->conn->error)
        {
            $this->conn->select_db($dbName);
        }
        return $this->conn->query($sql);
    }

    public function databaseExists(string $dbName): bool
    {
        $sql = "SELECT schema_name FROM information_schema.schemata WHERE SCHEMA_NAME LIKE '$dbName'";
        return $this->conn->query($sql)->num_rows;
    }


    public function dropDatabase(string $dbName): bool
    {
        $sql =
            "DROP DATABASE $dbName";
        return $this->conn->query($sql);
    }

    private function getParam(?string $param, string $type): string
    {
        if ($param || $param = ini_get("mysql.default_$type")):
            return $param;
        else: die("Connection Error: Invalid Server Parameter for $type");
        endif;
    }

    public function getConn(): mysqli
    {
        return $this->conn;
    }

    public function resetConn(): mysqli
    {
        if($this->isConnected())
        {
            $this->conn->close();
        }
        $this->openConnection();
        return $this->conn;
    }

    public function displayQuerySuccess($result): string
    {
        return $result ? "Success" : "Failed: {$this->conn->error}";
    }

    /**
     * @return bool
     */
    public function isConnected(): bool
    {
        return $this->connected;
    }
}