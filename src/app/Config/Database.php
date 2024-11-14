<?php

namespace App\Config;

use PDO;

class Database {

    private $host;
    private $db_name;
    private $username;
    private $password;
    public $conn;

    public function __construct() {

        $this->host = "mysql";
        $this->db_name = "crud_database";
        $this->username = "root";
        $this->password = "root";

    }

    public function getConnection() {

        $this->conn = null;

        try {
              $this->conn = new PDO(
                        "mysql:host={$this->host};dbname={$this->db_name}",
                        $this->username,
                        $this->password
                    );
                    $this->conn->setAttribute(PDO::ATTR_ERRMODE, PDO::ERRMODE_EXCEPTION);
             } catch (PDOException $exception) {
                    echo "Erro de conexão: " . $exception->getMessage();
             }

             return $this->conn;

    }

}

?>
