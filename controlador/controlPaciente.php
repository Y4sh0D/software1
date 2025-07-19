<?php 
	//session_start();  
	include_once '../modelo/modeloPaciente.php';

	$param = array();
	if(isset($_POST['param_idatencion']))
	{
		$param['param_idatencion'] = $_POST['param_idatencion'];
	}
	if(isset($_POST['param_idpaciente']))
	{
		$param['param_idpaciente'] = $_POST['param_idpaciente'];
	}
	if(isset($_POST['param_apellidos']))
	{
		$param['param_apellidos'] = $_POST['param_apellidos'];
	}
	if(isset($_POST['param_nombres']))
	{
		$param['param_nombres'] = $_POST['param_nombres'];
	}
	if(isset($_POST['param_dni']))
	{
		$param['param_dni'] = $_POST['param_dni'];
	}
	if(isset($_POST['param_usuario']))
	{
		$param['param_usuario'] = $_POST['param_usuario'];
	}
	if(isset($_POST['param_lista']))
	{
		$param['param_lista'] = $_POST['param_lista'];
	}
	
	$param['param_opcion'] = $_POST['param_opcion'];
	$Paciente = new Paciente_model();
	echo $Paciente->gestionar($param);
?>