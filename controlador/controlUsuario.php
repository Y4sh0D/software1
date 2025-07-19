<?php 
	include_once '../modelo/modeloUsuario.php';

	$param = array();
	$param['param_opcion'] = 'iniciarSesion';
	if(isset($_POST['usuario']))
	{
		$param['param_usuario'] = $_POST['usuario'];
	}
	if(isset($_POST['clave']))
	{
		$param['param_clave'] = $_POST['clave'];
	}
			
	$Usuario = new Usuario_model();
	echo $Usuario->gestionar($param);
?>