<?php
session_start();
require "conexion.php";

class Sintoma_model extends Conexion
{
	private $param = array();
	public function __construct()
	{
		parent::__construct();
	}

	public function gestionar($param)
	{
		$this->param = $param;
		switch ($this->param['param_opcion']) {
			case 'listaSintomas':
				echo $this->listaSintomas();
				break;
			case 'datosSintoma':
				echo $this->datosSintoma();
				break;
			case 'modificarSintoma':
				echo $this->modificarSintoma();
				break;
			case 'eliminarSintoma':
				echo $this->eliminarSintoma();
				break;
		}
	}

	private function listaSintomas()
	{
		$c = "<div class='tabla-sintomas-card'>";
		$c .= "<h3>Lista de Síntomas</h3>";
		$c .= "<div id='contenidolista'><table class='tabla-sintomas' width='100%'>";
		$c .= "<tr><th>Síntoma</th><th>Acción</th></tr>";
		$sql = "select sintoma.idsintoma,sintoma.sintoma from sintoma";
		$resultado = mysqli_query($this->conn, $sql);

		while ($mostrar = mysqli_fetch_array($resultado)) {
			$idsintoma = $mostrar['idsintoma'];
			$sintoma = $mostrar['sintoma'];
			$c .= "<tr><td>$sintoma</td>";
			$c .= "<td><a href=\"javascript:datosSintoma($idsintoma)\" class='btn-evaluar-mini'><i class='fa fa-search'></i> Ver</a></td></tr>";
		}
		$c .= "</table></div>";
		$c .= "<div id='contenidosintoma'></div>";
		$c .= "</div>";
		return $c;
	}

	private function datosSintoma()
	{
		$idsintoma = $this->param['param_idsintoma'];
		$c = "<div class='detalle-evaluacion-card'>";
		$c .= "<h4>Editar Síntoma</h4>";
		$sql = "select * from sintoma s where s.idsintoma=$idsintoma";
		$resultado = mysqli_query($this->conn, $sql);

		while ($mostrar = mysqli_fetch_array($resultado)) {
			$sintoma = $mostrar['sintoma'];
			$c .= "<div class='form-group'><label>ID Síntoma:</label><span class='datos-medico-value'>$idsintoma</span></div>";
			$c .= "<div class='form-group'><label>Síntoma:</label><input type='text' id='txtsintoma' class='form-control' value='$sintoma'></div>";
			$c .= "<div class='text-center' style='margin-top:1.2rem;'>";
			$c .= "<a href=\"javascript:modificarSintomaaccion($idsintoma)\" class='btn-evaluar'>Modificar</a> ";
			$c .= "<a href=\"javascript:eliminarSintomaaccion($idsintoma)\" class='btn-evaluar' style='background:#e74c3c;'>Eliminar</a>";
			$c .= "</div>";
		}
		$c .= "</div>";
		return $c;
	}
	private function modificarSintoma()
	{
		$idsintoma = $this->param['param_idsintoma'];
		$sintoma = $this->param['param_sintoma'];
		$sql = "update sintoma set sintoma.sintoma='$sintoma' where idsintoma=$idsintoma";
		mysqli_query($this->conn, $sql);
		return $this->listaSintomas();
	}
	private function eliminarSintoma()
	{
		$idsintoma = $this->param['param_idsintoma'];
		$sql = "delete from sintoma where idsintoma=$idsintoma";
		mysqli_query($this->conn, $sql);
		return $this->listaSintomas();
	}
}
