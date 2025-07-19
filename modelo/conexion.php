<?php
class Conexion
{
    protected $conn;
    public function __construct(){
        $this->conn = new mysqli("sql306.infinityfree.com","if0_39511120","AVfkj7Cr1SaVI","if0_39511120_bdic");
        if($this->conn->connect_errno)
{
    echo "No hay conexión: (" . $this->conn->connect_errno . ") " . $this->conn->connect_error;
}
        $this->conn->set_charset("utf8");
    }
}
?>