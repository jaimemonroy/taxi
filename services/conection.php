<?php
/**
 * Clase de conexion con la base de datos
*
* */
class conection{
	private $host = "localhost";
	private $user = "vessingc_taxiuser";
	private $password = "MonagasMonroy";
	private $db = "vessingc_taxi";
	private $con=null;
	public function conection(){
		$this->con = pg_connect("host=".$this->host." dbname=".$this->db." user=".$this->user." password=".$this->password);
		if (!$this->con) die("Ocurrio un error al intentar la conexion");
	}//function conexion
	public function getConexion(){
		return $this->con;
	}
	public function closeConexion(){
		pg_close($this->con);
	}
} //class
?>