
<?php

class Database {
    private $dbHost = 'localhost';
    private $dbUsername = 'root';
    private $dbPassword = '';
    private $dbName = 'totcloud_db';
    private $dbConnection;

    // Constructor para establecer la conexión
    public function __construct() {
        $this->connect();
    }

    // Método para realizar la conexión
    private function connect() {
        $this->dbConnection = mysqli_connect($this->dbHost, $this->dbUsername, $this->dbPassword, $this->dbName);

        if (mysqli_connect_errno()) {
            die("Connection failed: " . mysqli_connect_error());
        }
    }

    // Método para obtener la conexión
    public function getConnection() {
        return $this->dbConnection;
    }

    // Método para cerrar la conexión
    public function closeConnection() {
        if ($this->dbConnection) {
            mysqli_close($this->dbConnection);
        }
    }
}

$db = new Database();
$dbb= $db->getConnection();
?>