<?php 
	//session_start();  
	include_once '../modelo/modeloSintoma.php';

	$param = array();
	if(isset($_POST['param_idsintoma']))
	{
		$param['param_idsintoma'] = $_POST['param_idsintoma'];
	}
	if(isset($_POST['param_sintoma']))
	{
		$param['param_sintoma'] = $_POST['param_sintoma'];
	}
	$param['param_opcion'] = $_POST['param_opcion'];
	
	$Sintoma = new Sintoma_model();
	echo $Sintoma->gestionar($param);
?>