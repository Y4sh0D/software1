<?php 
	session_start();  
	require "conexion.php";

	class Usuario_model extends Conexion{
		private $param = array();
	    public function __construct(){
	    	parent::__construct();			
	    }	

	    public function gestionar($param){
	    	$this->param = $param;
	    	switch ($this->param['param_opcion'])
			{
				case 'iniciarSesion':
					echo $this->iniciarSesion();
					break;				
			}
	    }	
	    
		private function iniciarSesion(){
			unset($_SESSION['param_idusuario']);
			unset($_SESSION['param_usuario']);
			unset($_SESSION['param_apellidos']);
			unset($_SESSION['param_nombres']);
			unset($_SESSION['param_rol']);
									
	    	$querymedico = mysqli_query($this->conn,"SELECT u.idusuario as idusuario,u.rol as rol,m.idmedico as idmedico,m.apellidos as apellidos,m.nombres as nombres,u.usuario as usuario FROM usuario u,medico m  WHERE u.idusuario=m.idusuario and u.usuario ='".$this->param['param_usuario']."' and u.clave =md5('".$this->param['param_clave']."') and u.estado='a'");
			$querypaciente = mysqli_query($this->conn,"SELECT u.idusuario,u.rol,p.idpaciente,p.apellidos,p.nombres FROM usuario u,paciente p  WHERE u.idusuario=p.idusuario and u.usuario ='".$this->param['param_usuario']."' and u.clave =md5('".$this->param['param_clave']."') and u.estado='a'");
			
			$nrm = mysqli_num_rows($querymedico);				
			if ($nrm == 1 )  
				{
					while($mostrar=mysqli_fetch_array($querymedico))
					{
						$_SESSION['param_idusuario'] = $mostrar['idusuario'];
						$_SESSION['param_idmedico'] = $mostrar['idmedico'];
						$_SESSION['param_apellidos'] = $mostrar['apellidos'];
						$_SESSION['param_nombres'] = $mostrar['nombres'];
						$_SESSION['param_usuario'] = $mostrar['usuario'];						
					}
					$_SESSION['param_rol'] = 'm';
					header("Location: ../vista/index.php");
				}
			else{
				$nrp = mysqli_num_rows($querypaciente);
				if ($nrp == 1 ) {
					while($mostrar=mysqli_fetch_array($querypaciente))
					{
						$_SESSION['param_idusuario'] = $mostrar['idusuario'];
						$_SESSION['param_idpaciente'] = $mostrar['idpaciente'];
						$_SESSION['param_apellidos'] = $mostrar['apellidos'];
						$_SESSION['param_nombres'] = $mostrar['nombres'];
						$_SESSION['param_usuario'] = $mostrar['usuario'];						
					}
					$_SESSION['param_rol'] = 'p';
					header("Location: ../vista/index.php");
				}else
				{
					echo "<script> alert('Usuario o contraseña incorrecto.');window.location= '../index.html' </script>";
				}
			}
	    }	    
	}
 ?>