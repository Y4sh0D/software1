<?php
class Conexion
{
    protected $conn;
    public function __construct(){
        $this->conn = new mysqli("localhost","root","","bdic",3307);
		if($this->conn->connect_errno)
{
    echo "No hay conexión: (" . $this->conn->connect_errno . ") " . $this->conn->connect_error;
}
		$this->conn->set_charset("utf8");
    }
}
?>
