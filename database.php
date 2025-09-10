<?php
class Database
{
    private $host = "localhost";
    private $user = "root";
    private $pass = "";
    private $dbname = "payment_db";
    private $conn;

    // Constructor: Connect automatically when the object is created
    public function __construct()
    {
        $this->connect();
    }

    // Create MySQL connection
    private function connect()
    {
        $this->conn = new mysqli(
            $this->host,
            $this->user,
            $this->pass,
            $this->dbname
        );

        if ($this->conn->connect_error) {
            die("Database Connection Failed: " . $this->conn->connect_error);
        }
    }

    // Get connection object
    public function getConnection()
    {
        return $this->conn;
    }

    // Run SELECT query
    public function select($query)
    {
        $result = $this->conn->query($query);
        if ($result->num_rows > 0) {
            return $result->fetch_all(MYSQLI_ASSOC);
        }
        return [];
    }

    // Run INSERT / UPDATE / DELETE query
    public function execute($query)
    {
        if ($this->conn->query($query) === TRUE) {
            return true;
        } else {
            return false;
        }
    }

    // Close connection
    public function close()
    {
        $this->conn->close();
    }
}
?>